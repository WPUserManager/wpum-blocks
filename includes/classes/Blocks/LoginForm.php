<?php
/**
 * Helper methods for the Login Form block.
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
 * Helper methods that handle registration and output of the login form block.
 */
class LoginForm extends AbstractBlock {

	protected $name = 'login-form';
	protected $shortcode_function = 'wpum_login_form';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Login Form', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager login form.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'login', 'wp-user-manager' ),
				esc_html__( 'login form', 'wp-user-manager' ),
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
			'psw_link'      => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show password reset link', 'wp-user-manager' ),
			],
			'register_link' => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show register link', 'wp-user-manager' ),
			],
		];
	}

}
