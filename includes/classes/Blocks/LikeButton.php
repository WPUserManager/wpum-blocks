<?php
/**
 * Helper methods for the Like Button block.
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
class LikeButton extends AbstractBlock {

	protected $name = 'like-button';
	protected $shortcode_function = 'wpum_like_button';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Like Button', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager likes button.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'like', 'wp-user-manager' ),
				esc_html__( 'vote', 'wp-user-manager' ),
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
