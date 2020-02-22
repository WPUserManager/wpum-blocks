const { __ } = wp.i18n;
/**
 * Import blocks.
 */
import "./extensions/restriction-settings";

/**
 * Import blocks.
 */
import "./blocks/block-account-page/index.js";
import "./blocks/block-login-form/index.js";
import "./blocks/block-login-link/index.js";
import "./blocks/block-logout-link/index.js";
import "./blocks/block-password-recovery-form/index.js";
import "./blocks/block-profile-card/index.js";
import "./blocks/block-profile-page/index.js";
import "./blocks/block-recently-registered-users/index.js";
import "./blocks/block-registration-form/index.js";
import "./blocks/block-user-directory/index.js";

import "./style.scss";

// Setup custom icon for the WPUM blocks category.
wp.blocks.updateCategory("wpum", {
	icon: (
		<img
			style={{ height: "20px", "margin-top": "-2px" }}
			src={wpum_blocks.wpum_svg_logo}
			alt={__("WP User Manager")}
		/>
	)
});
