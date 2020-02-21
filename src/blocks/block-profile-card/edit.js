const { __ } = wp.i18n;
const { PanelBody, ToggleControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { serverSideRender } = wp;

const el = wp.element.createElement;

let blockName = "profile-card";

// Build the editor settings.
export default function(props) {
	const { attributes, setAttributes } = props;

	const {
		user_id,
		link_to_profile,
		display_buttons,
		display_cover
	} = attributes;

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
					wpum_blocks.blocks[blockName].attributes.link_to_profile
						.label,
				checked: link_to_profile,
				onChange: function() {
					setAttributes({
						link_to_profile: !link_to_profile
					});
				}
			}),
			el(ToggleControl, {
				label:
					wpum_blocks.blocks[blockName].attributes.display_buttons
						.label,
				checked: display_buttons,
				onChange: function() {
					setAttributes({
						display_buttons: !display_buttons
					});
				}
			}),
			el(ToggleControl, {
				label:
					wpum_blocks.blocks[blockName].attributes.display_cover
						.label,
				checked: display_cover,
				onChange: function() {
					setAttributes({
						display_cover: !display_cover
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
