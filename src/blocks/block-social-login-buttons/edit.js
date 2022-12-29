const { __ } = wp.i18n;
const { serverSideRender } = wp;

const el = wp.element.createElement;

let blockName = "social-login-buttons";

// Build the editor settings.
export default function(props) {
	return [
		el(serverSideRender, {
			block: "wpum/" + blockName,
			attributes: props.attributes
		})
	];
}
