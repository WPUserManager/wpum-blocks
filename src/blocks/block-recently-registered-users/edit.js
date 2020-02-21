const { __ } = wp.i18n;
const { PanelBody, ToggleControl, TextControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { serverSideRender } = wp;

const el = wp.element.createElement;

let blockName = "recently-registered-users";

// Build the editor settings.
export default function(props) {
	const { attributes, setAttributes } = props;
	const { amount, link_to_profile } = attributes;

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
				label: wpum_blocks.blocks[blockName].attributes.amount.label,
				value: amount,
				type: "number",
				onChange: function(amount) {
					setAttributes({
						amount
					});
				}
			}),
			el(ToggleControl, {
				label:
					wpum_blocks.blocks[blockName].attributes.link_to_profile
						.label,
				checked: link_to_profile,
				onChange: function() {
					setAttributes({
						link_to_profile: !link_to_profile
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
