<?php
/**
 * Helper methods for the Social Login Buttons block.
 *
 * @package     wpum-blocks
 * @copyright   Copyright (c) 2022, WP User Manager
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace WPUserManagerBlocks\Blocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Helper methods that handle registration and output of the password recovery block.
 */
class SocialLoginButtons extends AbstractBlock {

	protected $name = 'social-login-buttons';
	protected $shortcode_function = 'wpumsl_display_social_login_buttons';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Social Login Buttons', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager social login buttons.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'social login', 'wp-user-manager' ),
				esc_html__( 'twitter', 'wp-user-manager' ),
				esc_html__( 'facebook', 'wp-user-manager' ),
				esc_html__( 'google', 'wp-user-manager' ),
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
