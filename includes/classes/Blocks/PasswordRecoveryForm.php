<?php
/**
 * Helper methods for the Password Recovery block.
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
class PasswordRecoveryForm extends AbstractBlock {

	protected $name = 'password-recovery-form';
	protected $shortcode_function = 'wpum_password_recovery';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Password Recovery Form', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager password recovery form.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'password', 'wp-user-manager' ),
				esc_html__( 'recovery', 'wp-user-manager' ),
				esc_html__( 'password recovery', 'wp-user-manager' ),
				esc_html__( 'forgot password', 'wp-user-manager' ),
				esc_html__( 'forgot', 'wp-user-manager' ),
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
			'login_link'      => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show login link', 'wp-user-manager' ),
			],
			'register_link' => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show registration link', 'wp-user-manager' ),
			],
		];
	}

}
