<?php
/**
 * Helper methods for the User Directory block.
 *
 * @package     wpum-blocks
 * @copyright   Copyright (c) 2020, WP User Manager
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace WPUserManagerBlocks\Blocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Helper methods that handle registration and output of the password recovery block.
 */
class UserDirectory extends AbstractBlock {

	protected $name = 'user-directory';
	protected $shortcode_function = 'wpum_directory';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'User Directory', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager User Directory block.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'users', 'wp-user-manager' ),
				esc_html__( 'user', 'wp-user-manager' ),
				esc_html__( 'directory', 'wp-user-manager' ),
				esc_html__( 'user directory', 'wp-user-manager' ),
				esc_html__( 'user group', 'wp-user-manager' ),
				esc_html__( 'group', 'wp-user-manager' ),
			],
			'panel_settings' => esc_html__( 'Settings', 'wp-user-manager' ),
		];
	}

	/**
	 * Retrieve the list of attributes.
	 *
	 * @return array
	 */
	protected function get_attributes() {
		return [
			'id'      => [
				'type'    => 'text',
				'label'   => esc_html__( 'Select Directory', 'wp-user-manager' ),
			],
		];
	}

}
