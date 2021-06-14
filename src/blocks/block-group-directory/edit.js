const { __ } = wp.i18n;
const { PanelBody, ToggleControl, TextControl } = wp.components;
const { InspectorControls } = wp.blockEditor;
const { serverSideRender } = wp;
const el = wp.element.createElement;

let blockName = "group-directory";

// Build the editor settings.
export default function(props) {
	const { attributes, setAttributes } = props;
	const { per_page, has_search_form, show_public, show_private } = attributes;

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
				el(TextControl, {
					label: wpum_blocks.blocks[blockName].attributes.per_page.label,
					value: per_page,
					onChange: function(per_page) {
						setAttributes({
							per_page
						});
					}
				}),
				el(ToggleControl, {
					label: wpum_blocks.blocks[blockName].attributes.has_search_form.label,
					checked: has_search_form,
					onChange: function() {
						setAttributes({
							has_search_form: !has_search_form
						});
					}
				}),
				el(ToggleControl, {
					label: wpum_blocks.blocks[blockName].attributes.show_public.label,
					checked: show_public,
					onChange: function() {
						setAttributes({
							show_public: !show_public
						});
					}
				}),
				el(ToggleControl, {
					label:
						wpum_blocks.blocks[blockName].attributes.show_private.label,
					checked: show_private,
					onChange: function() {
						setAttributes({
							show_private: !show_private
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
	}else{
		return [];
	}
}
