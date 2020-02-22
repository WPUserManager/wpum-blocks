const { __ } = wp.i18n;
const { PanelBody, TextControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { serverSideRender } = wp;

const el = wp.element.createElement;

let blockName = "login-link";

// Build the editor settings.
export default function(props) {
	const { attributes, setAttributes } = props;

	const { redirect, label } = attributes;

	const settings = el(
		InspectorControls,
		null,

		// Query settings panel.
		el(
			PanelBody,
			{
				title: wpum_blocks.blocks[blockName].labels.panel_settings
			},
			el(TextControl, {
				label: wpum_blocks.blocks[blockName].attributes.redirect.label,
				value:
					wpum_blocks.blocks[blockName].attributes.redirect.default,
				onChange: function() {
					setAttributes({
						redirect: !redirect
					});
				}
			}),
			el(TextControl, {
				label: wpum_blocks.blocks[blockName].attributes.label.label,
				value: wpum_blocks.blocks[blockName].attributes.label.default,
				onChange: function() {
					setAttributes({
						label: !label
					});
				}
			})
		)
	);

	return [
		settings,
		el(serverSideRender, {
			block: "wpum/" + blockName,
			attributes: props.attributes
		})
	];
}
