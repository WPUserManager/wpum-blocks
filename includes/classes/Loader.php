<?php
/**
 * Helper methods for running the blocks functionalities.
 *
 * @package     wpum-blocks
 * @copyright   Copyright (c) 2020, WP User Manager
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

namespace WPUserManagerBlocks;

use WPUserManagerBlocks\Blocks\AccountPage;
use WPUserManagerBlocks\Blocks\LoginForm;
use WPUserManagerBlocks\Blocks\LoginLink;
use WPUserManagerBlocks\Blocks\LogoutLink;
use WPUserManagerBlocks\Blocks\PasswordRecoveryForm;
use WPUserManagerBlocks\Blocks\ProfileCard;
use WPUserManagerBlocks\Blocks\ProfilePage;
use WPUserManagerBlocks\Blocks\RecentlyRegisteredUsers;
use WPUserManagerBlocks\Blocks\RegistrationForm;
use WPUserManagerBlocks\Blocks\UserDirectory;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Helper methods for running the blocks.
 */
class Loader {

	public function init() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register server side blocks for the editor.
	 */
	public function register() {
		( new AccountPage() )->register();
		( new LoginForm() )->register();
		( new LoginLink() )->register();
		( new LogoutLink() )->register();
		( new PasswordRecoveryForm() )->register();
		( new ProfileCard() )->register();
		( new ProfilePage() )->register();
		( new RecentlyRegisteredUsers() )->register();
		( new RegistrationForm() )->register();
		( new UserDirectory() )->register();
	}

	/**
	 * Get the js variables required for the block editor.
	 *
	 * @return array
	 */
	public function get_js_vars() {
		return [
			'wpum_svg_logo' => WPUM_PLUGIN_URL . 'assets/images/logo.svg',
			'ajax'          => admin_url( 'admin-ajax.php' ),
			'blocks'        => apply_filters( 'wpum_blocks', [] ),
		];
	}
}
