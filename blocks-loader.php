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

function get_user_roles() {
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

add_action('rest_api_init', function () use ( $loader ) {
	register_rest_route(
		'wp-user-manager',
		'user-roles',
		array(
			'methods' => 'GET',
			'callback' => 'get_user_roles'
		 )
	);
});

/**
 * Add Custom Attributes needed for src/extensions/ to all blocks
 *
 * This is needed because serverSideRender component isn’t able to handle the extra props passed to it.
 *
 * src: https://github.com/Codeinwp/blocks-css/pull/5/files
 */
add_action( 'wp_loaded', function () {

	$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

	foreach( $registered_blocks as $name => $block ) {

		$block->attributes['wpum_restrict_type'] = array(
			'type'    => 'string',
			'default' => "wpum_restrict_type_state",
		);
		$block->attributes['wpum_hide_state_in'] = array(
			'type'    => 'boolean',
			'default' => false,
		);
		$block->attributes['wpum_hide_state_out'] = array(
			'type'    => 'boolean',
			'default' => false,
		);
		$block->attributes['wpum_hide_users']    = array(
			'type'    => 'array',
		);
		$block->attributes['wpum_hide_roles']    = array(
			'type'    => 'array',
		);
	}

}, 100);

/**
 * Parse Blocks through render_block fn.
 *
 * @param string $block_content Content output per block from Gutenberg.
 * @param array  $block         Array of block contents, attributes etc.
 *
 * @return string $block_content Modified Block Content.
 */
function renderBlocksFindingAttrs( $block_content, $block ) {
	$attrs         = $block['attrs'];
	$block_content = removeBlocksMatchingCriteria( $attrs, $block_content );

	return $block_content;
}

add_action( 'render_block', 'renderBlocksFindingAttrs', 10, 2 );

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
