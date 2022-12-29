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
class PrivateContent extends AbstractBlock {

	protected $name = 'private-content';
	protected $shortcode_function = 'wpumpr_private_content';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Private Content', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager private user content.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'private content', 'wp-user-manager' ),
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
