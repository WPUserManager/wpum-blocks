/**
 * Inspector Controls
 */

import pickBy from "lodash/pickBy";

const { __ } = wp.i18n;
const { PanelBody, SelectControl } = wp.components;
const { withSelect } = wp.data;
const { Component, Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;

class Inspector extends Component {
	constructor() {
		super(...arguments);
	}

	render() {
		// Setup the attributes
		const { attributes, postsList } = this.props;

		const options = [];
		if (postsList) {
			options.push({ value: "", label: "Select a Directory" });
			postsList.forEach(post => {
				options.push({ value: post.id, label: post.title.rendered });
			});
		} else {
			options.push({ value: "0", label: "Loading..." });
		}

		return (
			<InspectorControls>
				<PanelBody title={__("Settings", "wp-user-manager")}>
					<SelectControl
						options={options}
						value={attributes.id}
						onChange={value =>
							this.props.setAttributes({
								id: value
							})
						}
					/>
				</PanelBody>
			</InspectorControls>
		);
	}
}

export default withSelect((select, props) => {
	const { getEntityRecords } = select("core");
	const postsQuery = pickBy({
		exclude: [wp.data.select("core/editor").getCurrentPostId()]
	});
	return {
		postsList: getEntityRecords("postType", "wpum_directory", postsQuery)
	};
})(Inspector);
