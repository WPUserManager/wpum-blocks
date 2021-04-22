<?php
/**
 * Helper methods for the User Directory block.
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
class PostForm extends AbstractBlock {

	protected $name = 'post-form';
	protected $shortcode_function = 'wpumfr_post_form';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Post Form', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager Post Form block.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'post form', 'wp-user-manager' ),
				esc_html__( 'post', 'wp-user-manager' ),
				esc_html__( 'form', 'wp-user-manager' ),
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
		$post_forms = WPUMFR()->post_forms->get_forms();

		$default = 0;
		if ( isset( $post_forms[0])) {
			$default = $post_forms[0]->ID;
		}

		return [
			'form_id'      => [
				'type'    => 'integer',
				'label'   => esc_html__( 'Select Form', 'wp-user-manager' ),
				'default' => $default,
			],
		];
	}

}
