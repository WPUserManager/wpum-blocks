const { __ } = wp.i18n;
const { PanelBody, ToggleControl, SelectControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { serverSideRender, apiFetch } = wp;

const el = wp.element.createElement;

let blockName = "post-form";

const options = [];
if ( typeof wpum_blocks.blocks[ blockName ] !== 'undefined' ) {
	wp.apiFetch({ path: "/wpum-fr/forms" }).then(posts =>
		posts.map(function(form) {
			options.push({ value: form.form_id, label: form.name });
		})
	);
}
// Build the editor settings.
export default function(props) {
	const { attributes, setAttributes } = props;
	const {
		form_id,
	} = attributes;

	if ( typeof wpum_blocks.blocks[ blockName ] !== 'undefined' ) {
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
					label: wpum_blocks.blocks[blockName].attributes.form_id.label,
					value: form_id,
					options: options,
					onChange: function(form_id) {
						setAttributes({
							form_id
						});
					}
				}),
			)
		);

		return [
			settings,
			el(serverSideRender, {
				block: "wpum/" + blockName,
				attributes: props.attributes
			})
		];
	}else{
		return [];
	}
}
