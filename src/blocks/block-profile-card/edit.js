const { __ } = wp.i18n;
const { PanelBody, ToggleControl, SelectControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { serverSideRender, apiFetch } = wp;

const el = wp.element.createElement;

let blockName = "profile-card";

const options = [];
wp.apiFetch({ path: "/wp/v2/users" }).then(posts =>
	posts.map(function(user) {
		options.push({ value: user.id, label: user.name });
	})
);
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
			el(SelectControl, {
				label: wpum_blocks.blocks[blockName].attributes.user_id.label,
				value: user_id,
				options: options,
				onChange: function(user_id) {
					setAttributes({
						user_id
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
