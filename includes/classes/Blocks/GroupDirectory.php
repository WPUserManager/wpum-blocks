<?php
/**
 * Helper methods for the Registration Form block.
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
 * Helper methods that handle registration and output of the registration block.
 */
class GroupDirectory extends AbstractBlock {

	protected $name = 'group-directory';
	protected $shortcode_function = 'wpum_group_directory';

	protected function get_labels() {
		return [
			'title'          => esc_html__( 'Group Directory', 'wp-user-manager' ),
			'description'    => esc_html__( 'WP User Manager groups directory.', 'wp-user-manager' ),
			'keywords'       => [
				esc_html__( 'group directory', 'wp-user-manager' ),
				esc_html__( 'groups', 'wp-user-manager' ),
				esc_html__( 'directory', 'wp-user-manager' ),
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
			'per_page'     => [
				'type' => 'integer',
				'default' => 10,
				'label'   => esc_html__( 'Number of groups per page', 'wp-user-manager' ),
			],
			'has_search_form'      => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show search form', 'wp-user-manager' ),
			],
			'show_public' => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show public groups', 'wp-user-manager' ),
			],
			'show_private' => [
				'type'    => 'boolean',
				'default' => true,
				'label'   => esc_html__( 'Show private groups', 'wp-user-manager' ),
			],
		];
	}
}
