<?php
/**
 * Hooks the blocks component to WordPress.
 *
 * @package     wpum-blocks
 * @copyright   Copyright (c) 2020 WP User Manager
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

use WPUserManagerBlocks\Loader;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Register the WP User Manager blocks category for the block editor.
 */
add_filter( 'block_categories', function ( $categories ) {
	return array_merge( $categories, array(
			array(
				'slug'  => 'wpum',
				'title' => 'WP User Manager',
			),
		) );
} );

/**
 * Start the blocks up
 */
$loader = new Loader();
$loader->init();

/**
 * Register the script file containing all the blocks for the block editor.
 */
add_action( 'enqueue_block_editor_assets', function () use ( $loader ) {
	wp_enqueue_script( 'wpum-blocks', WPUM_PLUGIN_URL . 'vendor/wp-user-manager/wpum-blocks/build/index.js', [
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components',
			'wp-editor',
		], WPUM_VERSION, true );

	wp_enqueue_style( 'wpum-blocks-admin', WPUM_PLUGIN_URL . 'vendor/wp-user-manager/wpum-blocks/build/style.css', [], WPUM_VERSION );
	wp_enqueue_style( 'wpum-blocks-admin-style', WPUM_PLUGIN_URL . 'assets/css/wpum.min.css', false, WPUM_VERSION );

	wp_localize_script( 'wpum-blocks', 'wpum_blocks', $loader->get_js_vars() );
} );

function wpum_blocks_get_user_roles() {
	global $wp_roles;

	$roles      = array();
	$user_roles = $wp_roles->roles;

	foreach ( $user_roles as $key => $role ) {
		$roles[] = array(
			'value' => $key,
			'label' => $role['name'],
		);
	}

	return $roles;
}

add_action( 'rest_api_init', 'wpum_blocks_register_roles_route' );

function wpum_blocks_register_roles_route() {
	register_rest_route( 'wp-user-manager', 'user-roles', array(
		'methods'             => 'GET',
		'callback'            => 'wpum_blocks_get_user_roles',
		'permission_callback' => function () {
			return current_user_can( 'edit_posts' );
		},
	) );
}

add_action( 'wp_loaded', 'wpum_blocks_register_block_attrs', 100 );
/**
 * Add Custom Attributes needed for src/extensions/ to all blocks
 *
 * This is needed because serverSideRender component isn’t able to handle the extra props passed to it.
 *
 * src: https://github.com/Codeinwp/blocks-css/pull/5/files
 */
function wpum_blocks_register_block_attrs() {
	$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

	foreach ( $registered_blocks as $name => $block ) {

		$block->attributes['wpum_restrict_type']  = array(
			'type'    => 'string',
			'default' => "wpum_restrict_type_state",
		);
		$block->attributes['wpum_hide_state_in']  = array(
			'type'    => 'boolean',
			'default' => false,
		);
		$block->attributes['wpum_hide_state_out'] = array(
			'type'    => 'boolean',
			'default' => false,
		);
		$block->attributes['wpum_hide_users']     = array(
			'type' => 'array',
		);
		$block->attributes['wpum_hide_roles']     = array(
			'type' => 'array',
		);
	}
}

/**
 * Parse Blocks through render_block fn.
 *
 * @param string $block_content Content output per block from Gutenberg.
 * @param array  $block         Array of block contents, attributes etc.
 *
 * @return string $block_content Modified Block Content.
 */
function wpum_blocks_maybe_restrict_content( $block_content, $block ) {
	if ( is_admin() && isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) {
		// Always show the block content in the editor
		return $block_content;
	}

	$attrs = $block['attrs'];

	if ( isset( $attrs['wpum_restrict_state_in'] ) && $attrs['wpum_restrict_state_in'] && ! is_user_logged_in() ) {
		return wpum_blocks_get_restricted_message();
	}

	if ( ! isset( $attrs['wpum_restrict_type'] ) ) {
		return $block_content;
	}

	if ( 'wpum_restrict_type_role' === $attrs['wpum_restrict_type'] ) {
		$allowed_roles = array_map( 'trim', $attrs['wpum_restrict_roles'] );
		$current_user  = wp_get_current_user();
		if ( is_user_logged_in() && array_intersect( $current_user->roles, $allowed_roles ) ) {
			return $block_content;
		}
	}

	if ( 'wpum_restrict_type_user' === $attrs['wpum_restrict_type'] ) {
		$allowed_users = array_map( 'trim', $attrs['wpum_restrict_users'] );
		if ( is_user_logged_in() && in_array( wp_get_current_user()->ID, $allowed_users ) ) {
			return $block_content;
		}
	}

	return wpum_blocks_get_restricted_message();
}

add_action( 'render_block', 'wpum_blocks_maybe_restrict_content', 10, 2 );

/**
 * @return string
 */
function wpum_blocks_get_restricted_message() {
	global $wpum_restricted_id, $post;

	if ( ! empty( $wpum_restricted_id ) && $wpum_restricted_id === $post->ID ) {
		return '';
	}

	$wpum_restricted_id = $post->ID;

	ob_start();
	$login_page = get_permalink( wpum_get_core_page_id( 'login' ) );
	$login_page = add_query_arg( [
		'redirect_to' => get_permalink(),
	], $login_page );

	$message = sprintf( __( 'This content is available to members only. Please <a href="%1$s">login</a> or <a href="%2$s">register</a> to view this area.', 'wp-user-manager' ), $login_page, get_permalink( wpum_get_core_page_id( 'register' ) ) );

	/**
	 * Filter: allow developers to modify the content restriction shortcode message.
	 *
	 * @param string $message the original message.
	 *
	 * @return string
	 */
	$message = apply_filters( 'wpum_content_restriction_message', $message );

	WPUM()->templates->set_template_data( [
				'message' => $message,
			] )->get_template_part( 'messages/general', 'warning' );

	$output = ob_get_clean();

	return $output;
}

/**
 * Hide Block Content where attr matches choice.
 *
 * @param array  $attrs Blocks Attributes from the Editor.
 * @param string $block_content Content output per block from Gutenberg.
 *
 * @return string $block_content Modified Block Content.
 */
function removeBlocksMatchingCriteria( $attrs, $block_content )
{

	/**
	 * TODO: Logic for restricting content dependent on extension.
	 *
	 * Attrs to use:
	 ** wpum_restrict_type - All other attrs are dependent on this one
	 ** wpum_hide_state_in,
	 ** wpum_hide_state_out
	 ** wpum_hide_users
	 ** wpum_hide_roles
	 *
	 * Return:
	 ** return nothing to hide block
	 ** return $bock_content to render block
	 */
	return $block_content;
}
