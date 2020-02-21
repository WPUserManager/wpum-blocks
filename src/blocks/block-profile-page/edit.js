const { __ } = wp.i18n;
const el = wp.element.createElement;
const { serverSideRender } = wp;

let blockName = "profile-page";

// Build the editor settings.
export default function(props) {
	return [
		el(serverSideRender, {
			block: "wpum/" + blockName,
			attributes: props.attributes
		})
	];
}
