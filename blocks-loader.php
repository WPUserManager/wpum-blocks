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
