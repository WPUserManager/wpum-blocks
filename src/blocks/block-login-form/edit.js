const { __ } = wp.i18n;
const { PanelBody, ToggleControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { serverSideRender } = wp;

const el = wp.element.createElement;

let blockName = "login-form";

// Build the editor settings.
export default function(props) {
	const { attributes, setAttributes } = props;

	const { psw_link, register_link } = attributes;

	const settings = el(
		InspectorControls,
		null,

		// Query settings panel.
		el(
			PanelBody,
			{
				title: wpum_blocks.blocks[blockName].labels.panel_settings
			},
			el(ToggleControl, {
				label: wpum_blocks.blocks[blockName].attributes.psw_link.label,
				checked: psw_link,
				onChange: function() {
					setAttributes({
						psw_link: !psw_link
					});
				}
			}),
			el(ToggleControl, {
				label:
					wpum_blocks.blocks[blockName].attributes.register_link
						.label,
				checked: register_link,
				onChange: function() {
					setAttributes({
						register_link: !register_link
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
