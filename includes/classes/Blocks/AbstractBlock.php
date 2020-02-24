<?php
/**
 * Helper methods for a block.
 *
 * @package     wpum-blocks
 * @copyright   Copyright (c) 2020, WP User Manager
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace WPUserManagerBlocks\Blocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

abstract class AbstractBlock {

	protected $name;
	protected $shortcode_function;

	abstract protected function get_labels();

	abstract protected function get_attributes();

	/**
	 * Handles output of the block.
	 *
	 * @param array $attributes settings sent through.
	 *
	 * @return string
	 */
	public function render_callback( $attributes ) {
		return call_user_func( apply_filters( 'wpum_blocks_block_callback', $this->shortcode_function, $this->name ), $attributes );
	}

	public function register() {
		add_filter( 'wpum_blocks', function ( $blocks ) {
			$blocks[ $this->name ] = array(
				'labels'     => $this->get_labels(),
				'attributes' => $this->get_attributes(),
			);

			return $blocks;
		} );

		register_block_type( 'wpum/' . $this->name, [
			'attributes'      => $this->get_attributes(),
			'render_callback' => [ $this, 'render_callback' ],
		] );
	}
}
