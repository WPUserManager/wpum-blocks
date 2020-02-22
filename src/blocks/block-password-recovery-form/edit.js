const { __ } = wp.i18n;
const { PanelBody, ToggleControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { serverSideRender } = wp;

const el = wp.element.createElement;

let blockName = "password-recovery-form";

// Build the editor settings.
export default function(props) {
	const { attributes, setAttributes } = props;

	const { login_link, register_link } = attributes;

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
				label:
					wpum_blocks.blocks[blockName].attributes.login_link.label,
				checked: login_link,
				onChange: function() {
					setAttributes({
						login_link: !login_link
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
