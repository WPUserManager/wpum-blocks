<?php
/**
 * Helper methods for the Profile Card block.
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
class ProfileCard extends AbstractBlock {

	protected $name = 'profile-card';
	protected $shortcode_function = 'wpum_profile_card';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Profile Card', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager user profile card.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'profile', 'wp-user-manager' ),
				esc_html__( 'user', 'wp-user-manager' ),
				esc_html__( 'card', 'wp-user-manager' ),
				esc_html__( 'profile card', 'wp-user-manager' ),
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
		return [
			'user_id'      => [
				'type'    => 'text',
				'label'   => esc_html__( 'Select User', 'wp-user-manager' ),
			],
			'link_to_profile' => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Profile link', 'wp-user-manager' ),
			],
			'display_buttons' => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show buttons', 'wp-user-manager' ),
			],
			'display_cover' => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Display profile cover', 'wp-user-manager' ),
			],
		];
	}

}
