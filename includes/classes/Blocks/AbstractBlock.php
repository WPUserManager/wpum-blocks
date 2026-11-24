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
		$output = call_user_func( apply_filters( 'wpum_blocks_block_callback', $this->shortcode_function, $this->name ), $attributes );

		return is_string( $output ) ? self::escape_shortcode_brackets( $output ) : $output;
	}

	/**
	 * Block output goes into the post content before WordPress runs shortcodes
	 * on it, so any square brackets in it, such as a submitted value repeated in
	 * a form error or text from a user's profile, would be parsed as shortcodes.
	 * Encode them so they display as typed. The browser decodes the entities in
	 * text and attribute values. Script and style contents are left as they are,
	 * because entities are not decoded there.
	 *
	 * @param string $output Block output.
	 *
	 * @return string
	 */
	public static function escape_shortcode_brackets( $output ) {
		if ( false === strpos( $output, '[' ) && false === strpos( $output, ']' ) ) {
			return $output;
		}

		$parts = preg_split( '#(<script\b[^>]*>.*?</script>|<style\b[^>]*>.*?</style>)#is', $output, -1, PREG_SPLIT_DELIM_CAPTURE );

		if ( false === $parts ) {
			return str_replace( array( '[', ']' ), array( '&#91;', '&#93;' ), $output );
		}

		foreach ( $parts as $i => $part ) {
			if ( 1 === $i % 2 ) {
				continue;
			}

			$parts[ $i ] = str_replace( array( '[', ']' ), array( '&#91;', '&#93;' ), $part );
		}

		return implode( '', $parts );
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
