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
	"wpum/user-directory"
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

const options = [];
wp.apiFetch({ path: "/wp/v2/users" }).then(posts =>
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
		wpum_restrict_state_in: {
			type: "boolean",
			default: false
		},
		wpum_restrict_users: {
			type: "array"
		},
		wpum_restrict_roles: {
			type: "array"
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
			wpum_restrict_state_in,
			wpum_restrict_users,
			wpum_restrict_roles
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
							 <ToggleControl
								 label={__("Display only to logged in users")}
								 help={
									 !wpum_restrict_state_in
									 ? __(
										 "Visible to all users.",
										 "wp-user-manager"
									 )
									 : __(
										 "Hidden from logged out users.",
										 "wp-user-manager"
									 )
								 }
								 checked={wpum_restrict_state_in}
								 onChange={toggleStateIn => {
									 props.setAttributes({
										 wpum_restrict_state_in: toggleStateIn
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
								value={wpum_restrict_roles}
								options={roleOptions}
								onChange={selectedRoles => {
									props.setAttributes({
										wpum_restrict_roles: selectedRoles
									});
								}}
							/>
						)}
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
