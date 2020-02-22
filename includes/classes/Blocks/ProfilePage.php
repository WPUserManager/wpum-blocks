<?php
/**
 * Helper methods for the Login Link block.
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
 * Helper methods that handle registration and output of the profile page block.
 */
class ProfilePage extends AbstractBlock {

	protected $name = 'profile-page';
	protected $shortcode_function = 'wpum_profile';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Profile Page', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager profile page.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'profile', 'wp-user-manager' ),
				esc_html__( 'user profile', 'wp-user-manager' ),
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
		return [];
	}

}
