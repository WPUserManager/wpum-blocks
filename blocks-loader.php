<?php
/**
 * Hooks the blocks component to WordPress.
 *
 * @package     wpum-blocks
 * @copyright   Copyright (c) 2020 WP User Manager
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

class WPUM_Blocks {

	/**
	 * @var WPUM_Blocks
	 */
	protected static $instance;

	protected $loader;

	private function __construct() {
	}

	/**
	 * Get instance.
	 *
	 * @return static
	 * @since
	 * @access static
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	public function init() {
		/**
		 * Register the WP User Manager blocks category for the block editor.
		 */
		add_filter( 'block_categories_all', function ( $categories ) {
			return array_merge( $categories, array(
				array(
					'slug'  => 'wpum',
					'title' => 'WP User Manager',
				),
			) );
		} );

		/**
		 * Start the blocks up
		 */
		$this->loader = new WPUserManagerBlocks\Loader();
		$this->loader->init();

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_scripts' ), 1 );
		add_action( 'wp_loaded', array( $this, 'register_block_attrs' ), 100 );
		add_action( 'rest_api_init', array( $this, 'register_roles_route' ) );
		add_action( 'render_block', array( $this, 'maybe_restrict_content' ), 10, 2 );
		add_action( 'widget_display_callback', array( $this, 'maybe_restrict_widget' ), 10, 3 );
		add_action( 'widget_update_callback', array( $this, 'update_legacy_widget' ), 10, 2 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'wpum-blocks', WPUM_PLUGIN_URL . 'vendor/wp-user-manager/wpum-blocks/build/index.js', [
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components',
			'wp-editor',
		], WPUM_VERSION );

		wp_enqueue_style( 'wpum-blocks-admin', WPUM_PLUGIN_URL . 'vendor/wp-user-manager/wpum-blocks/build/style.css', [], WPUM_VERSION );
		wp_enqueue_style( 'wpum-blocks-admin-style', WPUM_PLUGIN_URL . 'assets/css/wpum.min.css', false, WPUM_VERSION );

		wp_localize_script( 'wpum-blocks', 'wpum_blocks', $this->loader->get_js_vars() );
	}

	/**
	 * Add Custom Attributes needed for src/extensions/ to all blocks
	 *
	 * This is needed because serverSideRender component isn’t able to handle the extra props passed to it.
	 *
	 * src: https://github.com/Codeinwp/blocks-css/pull/5/files
	 */
	public function register_block_attrs() {
		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ( $registered_blocks as $name => $block ) {

			$block->attributes['wpum_restrict_type']  = array(
				'type'    => 'string',
				'default' => "wpum_restrict_type_state",
			);
			$block->attributes['wpum_hide_state_in']  = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$block->attributes['wpum_hide_state_out'] = array(
				'type'    => 'boolean',
				'default' => false,
			);
			$block->attributes['wpum_hide_users']     = array(
				'type' => 'array',
			);
			$block->attributes['wpum_restrict_show_message']     = array(
				'type' => 'array',
			);
			$block->attributes['wpum_hide_roles']     = array(
				'type' => 'array',
			);
			$block->attributes['wpum_restrict_users']     = array(
				'type' => 'array',
			);
			$block->attributes['wpum_restrict_state'] = array(
				'type' => 'array',
			);
		}
	}

	public function register_roles_route() {
		register_rest_route( 'wp-user-manager', 'user-roles', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_user_roles' ),
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
		) );
	}

	/**
	 * Parse Blocks through render_block fn.
	 *
	 * @param string $block_content Content output per block from Gutenberg.
	 * @param array  $block         Array of block contents, attributes etc.
	 *
	 * @return string $block_content Modified Block Content.
	 */
	public function maybe_restrict_content( $block_content, $block ) {
		if ( ( is_admin() && isset( $_GET['action'] ) && $_GET['action'] === 'edit' ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST && isset( $_GET['context'] ) && $_GET['context'] === 'edit' ) ) {
			// Always show the block content in the editor
			return $block_content;
		}

		$attrs = $block['attrs'];

		if ( empty( $attrs ) ) {
			return $block_content;
		}

		$show_message = true;
		if ( isset( $attrs['wpum_restrict_show_message'] ) && ! $attrs['wpum_restrict_show_message'] ) {
			$show_message = false;
		}

		if ( isset( $attrs['wpum_restrict_state_in'] ) && $attrs['wpum_restrict_state_in'] && ! is_user_logged_in() ) {
			return $show_message ? $this->get_restricted_message() : '';
		}

		if ( ! empty( $attrs['wpum_restrict_state'] ) && 'in' === $attrs['wpum_restrict_state'] && ! is_user_logged_in() ) {
			return $show_message ? $this->get_restricted_message() : '';
		}

		if ( ! empty( $attrs['wpum_restrict_state'] ) && 'out' === $attrs['wpum_restrict_state'] && is_user_logged_in() ) {
			return $show_message ? $this->get_restricted_message() : '';
		}

		if ( ! isset( $attrs['wpum_restrict_type'] ) ) {
			return $block_content;
		}

		if ( 'wpum_restrict_type_role' === $attrs['wpum_restrict_type'] ) {
			$allowed_roles = empty( $attrs['wpum_restrict_roles'] ) || ! is_array( $attrs['wpum_restrict_roles'] ) ? array() : $attrs['wpum_restrict_roles'];
			$allowed_roles = array_map( 'trim', $allowed_roles );
			$current_user  = wp_get_current_user();
			if ( is_user_logged_in() && array_intersect( $current_user->roles, $allowed_roles ) ) {
				return $block_content;
			}
		}

		if ( 'wpum_restrict_type_user' === $attrs['wpum_restrict_type'] ) {
			$allowed_users = empty( $attrs['wpum_restrict_users'] ) || ! is_array( $attrs['wpum_restrict_users'] ) ? array() : $attrs['wpum_restrict_users'];
			$allowed_users = array_map( 'trim', $allowed_users );
			if ( is_user_logged_in() && in_array( wp_get_current_user()->ID, $allowed_users ) ) {
				return $block_content;
			}
		}

		return $show_message ? $this->get_restricted_message() : '';
	}

	/**
	 * @return string
	 */
	public function get_restricted_message( $widget = false ) {
		global $wpum_restricted_id, $post;

		if ( ! empty( $wpum_restricted_id ) && $wpum_restricted_id === $post->ID && $widget ) {
			return '';
		}

		if ( ! isset( $post->ID ) ) {
			return '';
		}

		$wpum_restricted_id = $post->ID;

		ob_start();
		$login_page = get_permalink( wpum_get_core_page_id( 'login' ) );
		$login_page = add_query_arg( [
			'redirect_to' => get_permalink(),
		], $login_page );

		$message = sprintf( __( 'This content is available to members only. Please <a href="%1$s">login</a> or <a href="%2$s">register</a> to view this area.', 'wp-user-manager' ), $login_page, get_permalink( wpum_get_core_page_id( 'register' ) ) );

		/**
		 * Filter: allow developers to modify the content restriction shortcode message.
		 *
		 * @param string $message the original message.
		 *
		 * @return string
		 */
		$message = apply_filters( 'wpum_content_restriction_message', $message );

		WPUM()->templates->set_template_data( [
			'message' => $message,
		] )->get_template_part( 'messages/general', 'warning' );

		$output = ob_get_clean();

		return $output;
	}

	function get_user_roles() {
		global $wp_roles;

		$roles      = array();
		$user_roles = $wp_roles->roles;

		foreach ( $user_roles as $key => $role ) {
			$roles[] = array(
				'value' => $key,
				'label' => $role['name'],
			);
		}

		return $roles;
	}

	/**
	 * support for legacy widget
	 *
	 */
	public function maybe_restrict_widget( $instance, $widget, $args ) {
		global $pagenow;

		if ( ( is_admin() && isset( $pagenow ) && $pagenow == 'widgets.php' ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return $instance;
		}

		$show_message = true;

		if ( isset( $instance['wpum_restrict_show_message'] ) && ! $instance['wpum_restrict_show_message'] ) {
			$show_message = false;
		}

		if ( isset( $instance['wpum_restrict_type'] ) && 'wpum_restrict_type_state' === $instance['wpum_restrict_type'] && ! empty( $instance['wpum_restrict_state'] ) && 'in' === $instance['wpum_restrict_state'] && ! is_user_logged_in() ) {
			echo $show_message ? $this->get_restricted_message() : '';
			return false;
		}


		if ( isset( $instance['wpum_restrict_type'] ) && 'wpum_restrict_type_state' === $instance['wpum_restrict_type'] && ! empty( $instance['wpum_restrict_state'] ) && 'in' === $instance['wpum_restrict_state'] && is_user_logged_in() ) {
			return $instance;
		}

		if ( isset( $instance['wpum_restrict_type'] ) && 'wpum_restrict_type_state' === $instance['wpum_restrict_type'] && ! empty( $instance['wpum_restrict_state'] ) && 'out' === $instance['wpum_restrict_state'] && is_user_logged_in() ) {
			echo $show_message ? $this->get_restricted_message() : '';
			return false;
		}

		if ( isset( $instance['wpum_restrict_type'] ) && 'wpum_restrict_type_state' === $instance['wpum_restrict_type'] && ! empty( $instance['wpum_restrict_state'] ) && 'out' === $instance['wpum_restrict_state'] && ! is_user_logged_in() ) {
			return $instance;
		}

			if ( ! isset( $instance['wpum_restrict_type'] ) ) {
			return $instance;
		}

		if ( 'wpum_restrict_type_role' === $instance['wpum_restrict_type'] ) {
			$allowed_roles = empty( $instance['wpum_restrict_roles'] ) || ! is_array( $instance['wpum_restrict_roles'] ) ? array() : $instance['wpum_restrict_roles'];
			$allowed_roles = array_map( 'trim', $allowed_roles );
			$current_user  = wp_get_current_user();

			if ( is_user_logged_in() && array_intersect( $current_user->roles, $allowed_roles ) ) {
				return $instance;
			}
		}

		if ( 'wpum_restrict_type_user' === $instance['wpum_restrict_type'] ) {
			$allowed_users = empty( $instance['wpum_restrict_users'] ) || ! is_array( $instance['wpum_restrict_users'] ) ? array() : $instance['wpum_restrict_users'];
			$allowed_users = array_map( 'trim', $allowed_users );
			if ( is_user_logged_in() && in_array( wp_get_current_user()->ID, $allowed_users ) ) {
				return $instance;
			}
		}

		echo $show_message ? $this->get_restricted_message() : '';
		return false;

	}

	public function update_legacy_widget( $instance, $new ) {
		$controls = [
			'wpum_restrict_type',
			'wpum_restrict_state',
			'wpum_restrict_users',
			'wpum_restrict_roles',
			'wpum_restrict_show_message',
		];

		foreach ( $controls as $control ) {
			if ( isset( $new[ $control ] ) ) {
				$instance[ $control ] = $new[ $control ];
			} else {
				unset( $instance[ $control ] );
			}
		}

		return $instance;
	}

}
