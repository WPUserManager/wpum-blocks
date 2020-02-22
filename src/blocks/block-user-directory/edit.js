import Inspector from "./inspector";
import isUndefined from "lodash/isUndefined";
import pickBy from "lodash/pickBy";

const { __ } = wp.i18n;
const { Placeholder, Spinner } = wp.components;
const { serverSideRender } = wp;
const { withSelect } = wp.data;
const { Component, Fragment } = wp.element;
const { compose } = wp.compose;

const el = wp.element.createElement;

let blockName = "user-directory";

// Build the editor settings.
class UserDirectoryBlock extends Component {
	render() {
		// Setup the attributes
		const { attributes, setAttributes, postsList } = this.props;

		// Check if there are posts
		const hasPosts = Array.isArray(postsList) && postsList.length;

		if (!hasPosts) {
			return (
				<Fragment>
					<Inspector {...{ setAttributes, ...this.props }} />
					<Placeholder
						icon="admin-post"
						label={__(
							"WP User Manager | User Directory",
							"wp-user-manager"
						)}
					>
						{!Array.isArray(postsList) ? (
							<Spinner />
						) : (
							__("No Directories Found.", "wp-user-manager")
						)}
					</Placeholder>
				</Fragment>
			);
		}

		return [
			<Inspector {...{ setAttributes, ...this.props }} />,
			el(serverSideRender, {
				block: "wpum/" + blockName,
				attributes: attributes
			})
		];
	}
}
export default compose([
	withSelect((select, props) => {
		const { getEntityRecords } = select("core");

		const latestPostsQuery = pickBy(
			{
				exclude: [wp.data.select("core/editor").getCurrentPostId()]
			},
			value => !isUndefined(value)
		);

		return {
			postsList: getEntityRecords(
				"postType",
				"wpum_directory",
				latestPostsQuery
			)
		};
	})
])(UserDirectoryBlock);
