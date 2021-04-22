const { assign } = lodash;

const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, SelectControl, ToggleControl } = wp.components;
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

// Disable restriction controls on the following blocks...
// * Add to the array... i.e ["core/paragraph", "core/image"]
const disableRestrictionControlsOnTheseBlocks = [
	"wpum/account-page",
	"wpum/login-form",
	"wpum/login-link",
	"wpum/logout-form",
	"wpum/password-recovery-form",
	"wpum/profile-card",
	"wpum/profile-page",
	"wpum/recently-registered-users",
	"wpum/registration-form",
	"wpum/password-recovery-form",
	"wpum/user-directory",
	"wpum/post-form",
	"wpum/group-directory"
];

// Available restriction control options
const restrictTypeOptions = [
	{
		label: __("Show block by users state"),
		value: "wpum_restrict_type_state"
	},
	{
		label: __("Show block by user role"),
		value: "wpum_restrict_type_role"
	},
	{
		label: __("Show block for certain users"),
		value: "wpum_restrict_type_user"
	}
];

const restrictStateOptions = [
	{
		label: __("Show to all users"),
		value: ""
	},
	{
		label: __("Show only to logged in users"),
		value: "in"
	},
	{
		label: __("Show only to logged out users"),
		value: "out"
	}
];

const options = [];
wp.apiFetch({ path: "/wp/v2/users?per_page=100" }).then(posts =>
	posts.map(function(user) {
		options.push({ value: user.id, label: user.name });
	})
);

const roleOptions = [];
wp.apiFetch({ path: "/wp-user-manager/user-roles" }).then(roles =>
	roles.map(function(role) {
		roleOptions.push({ value: role.value, label: role.label });
	})
);

/**
 * Add restriction control attributes to block.
 *
 * @param {object} settings Current block settings.
 * @param {string} name Name of block.
 *
 * @returns {object} Modified block settings.
 */
const addRestrictionControlAttributes = (settings, name) => {
	// Do nothing if it's another block than our defined ones.
	if (disableRestrictionControlsOnTheseBlocks.includes(name)) {
		return settings;
	}

	// Use Lodash's assign to gracefully handle if attributes are undefined
	settings.attributes = assign(settings.attributes, {
		wpum_restrict_type: {
			type: "string",
			default: "wpum_restrict_type_state"
		},
		wpum_restrict_state: {
			type: "string",
			default: ""
		},
		wpum_restrict_users: {
			type: "array"
		},
		wpum_restrict_roles: {
			type: "array"
		},
		wpum_restrict_show_message: {
			type: "boolean",
			default: true
		}
	});
	return settings;
};

addFilter(
	"blocks.registerBlockType",
	"wp-user-manager/attribute/restrictions",
	addRestrictionControlAttributes
);

/**
 * Create HOC to add restriction controls to inspector controls of block.
 */
const withRestrictionControls = createHigherOrderComponent(BlockEdit => {
	return props => {
		// Do nothing if it's another block than our defined ones.
		if (disableRestrictionControlsOnTheseBlocks.includes(props.name)) {
			return <BlockEdit {...props} />;
		}

		const {
			wpum_restrict_type,
			wpum_restrict_state,
			wpum_restrict_users,
			wpum_restrict_roles,
			wpum_restrict_show_message
		} = props.attributes;

		return (
			<Fragment>
				<BlockEdit {...props} />
				<InspectorControls>
					<PanelBody
						title={__("WP User Manager - Restriction")}
						initialOpen={false}
					>
						<SelectControl
							// label={__("How do you want to hide this block?")}
							value={wpum_restrict_type}
							options={restrictTypeOptions}
							onChange={selectedUsers => {
								props.setAttributes({
									wpum_restrict_type: selectedUsers
								});
							}}
						/>

						{wpum_restrict_type == "wpum_restrict_type_state" &&
						 (
							 <SelectControl
								 label={__(
									 "Select the type of users to show to"
								 )}
								 value={wpum_restrict_state}
								 options={restrictStateOptions}
								 onChange={selectedState => {
									 props.setAttributes({
										 wpum_restrict_state: selectedState
									 });
								 }}
							 />
						 )}

						{wpum_restrict_type == "wpum_restrict_type_user" && (
							<SelectControl
								label={__(
									"Display only to these users"
								)}
								multiple
								className='wpum-multiple-select'
								value={wpum_restrict_users}
								options={options}
								onChange={selectedUsers => {
									props.setAttributes({
										wpum_restrict_users: selectedUsers
									});
								}}
							/>
						)}

						{wpum_restrict_type == "wpum_restrict_type_role" && (
							<SelectControl
								label={__(
									"Display only to users with these roles"
								)}
								multiple
								className='wpum-multiple-select'
								value={wpum_restrict_roles}
								options={roleOptions}
								onChange={selectedRoles => {
									props.setAttributes({
										wpum_restrict_roles: selectedRoles
									});
								}}
							/>
						)}

						<ToggleControl
							label={__("Display restricted message")}
							help={
								!wpum_restrict_show_message
								? __(
									"Hide block content",
									"wp-user-manager"
								)
								: __(
									"Hide block content and show message",
									"wp-user-manager"
								)
							}
							checked={wpum_restrict_show_message}
							onChange={selected => {
								props.setAttributes({
									wpum_restrict_show_message: selected
								});
							}}
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, "withRestrictionControls");

addFilter(
	"editor.BlockEdit",
	"wp-user-manager/with-restriction-controls",
	withRestrictionControls
);
