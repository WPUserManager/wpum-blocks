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
 * Helper methods that handle registration and output of the login link block.
 */
class AccountPage extends AbstractBlock {

	protected $name = 'account-page';
	protected $shortcode_function = 'wpum_account_page';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Account Page', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager account page.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'account', 'wp-user-manager' ),
				esc_html__( 'edit account', 'wp-user-manager' ),
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
