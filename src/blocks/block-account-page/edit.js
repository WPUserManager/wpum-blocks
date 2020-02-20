const { __ } = wp.i18n;
const el = wp.element.createElement;

import { ServerSideRender } from "@wordpress/components";

let blockName = "account-page";

// Build the editor settings.
export default function(props) {
	return [
		el(ServerSideRender, {
			block: "wpum/" + blockName,
			attributes: props.attributes
		})
	];
}
