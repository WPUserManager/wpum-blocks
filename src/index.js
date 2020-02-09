const { __ } = wp.i18n;

/**
 * Import blocks.
 */
import './blocks/block-login-form/index.js';
import './style.scss'

// Setup custom icon for the WPUM blocks category.
wp.blocks.updateCategory( 'wpum', { icon: <img style={{ height: '20px', 'margin-top': '-2px' }} src={ wpum_blocks.wpum_svg_logo } alt={__('WP User Manager')} /> });
