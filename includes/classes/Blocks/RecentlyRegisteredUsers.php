<?php
/**
 * Helper methods for the Recently Registered Users block.
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
class RecentlyRegisteredUsers extends AbstractBlock {

	protected $name = 'recently-registered-users';
	protected $shortcode_function = 'wpum_recently_registered';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Recently Registered', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager Recently Registered Users Block.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'users', 'wp-user-manager' ),
				esc_html__( 'user', 'wp-user-manager' ),
				esc_html__( 'registered', 'wp-user-manager' ),
				esc_html__( 'recently registered', 'wp-user-manager' ),
				esc_html__( 'recently registered users', 'wp-user-manager' ),
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
			'amount'      => [
				'type'    => 'text',
				'label'   => esc_html__( 'How many users to display', 'wp-user-manager' ),
			],
			'link_to_profile' => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show profile link', 'wp-user-manager' ),
			],
		];
	}

}
