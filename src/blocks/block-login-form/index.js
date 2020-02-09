/**
 * Listings query block.
 */

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

// Import block editor settings
import EditBlock from './edit';

let blockName = 'login-form';

// Register the block.
registerBlockType( 'wpum/' + blockName, {
	title: wpum_blocks.blocks[blockName].labels.title,
	description: wpum_blocks.blocks[blockName].labels.description,
	icon: wpum_blocks.blocks[blockName].labels.icon,
	category: 'wpum',
	keywords: wpum_blocks.blocks[blockName].labels.keywords,
	attributes: wpum_blocks.blocks[blockName].attributes,

	edit: EditBlock,

	save() {
		return null;
	}
});
