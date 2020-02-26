<?php
/**
 * Helper methods for the Logout Link block.
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
 * Helper methods that handle registration and output of the logout link block.
 */
class LogoutLink extends AbstractBlock {

	protected $name = 'logout-link';
	protected $shortcode_function = 'wpum_logout_link';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Logout Link', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager logout link.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'logout', 'wp-user-manager' ),
				esc_html__( 'logout link', 'wp-user-manager' ),
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
			'redirect'      => [
				'type'    => 'string',
				'default' => '',
				'label'   => esc_html__( 'URL to redirect to after logout', 'wp-user-manager' ),
			],
			'label' => [
				'type'    => 'string',
				'default' => esc_html__( 'Logout', 'wp-user-manager' ),
				'label'   => esc_html__( 'Link label', 'wp-user-manager' ),
			],
		];
	}

}
