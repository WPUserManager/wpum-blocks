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
		label: __("Hide block by users state"),
		value: "wpum_restrict_type_state"
	},
	{
		label: __("Hide block from user role(s)"),
		value: "wpum_restrict_type_role"
	},
	{
		label: __("Hide block from certain user(s)"),
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
		wpum_hide_state_in: {
			type: "boolean",
			default: false
		},
		wpum_hide_state_out: {
			type: "boolean",
			default: false
		},
		wpum_hide_users: {
			type: "array"
		},
		wpum_hide_roles: {
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
			wpum_hide_state_in,
			wpum_hide_state_out,
			wpum_hide_users,
			wpum_hide_roles
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
							!wpum_hide_state_in && (
								<ToggleControl
									label={__("Hide from logged out users")}
									help={
										!wpum_hide_state_out
											? __(
													"Visible to all users.",
													"wp-user-manager"
											  )
											: __(
													"Hidden from logged out users.",
													"wp-user-manager"
											  )
									}
									checked={wpum_hide_state_out}
									onChange={toggleStateOut => {
										props.setAttributes({
											wpum_hide_state_out: toggleStateOut
										});
									}}
								/>
							)}

						{wpum_restrict_type == "wpum_restrict_type_state" &&
							!wpum_hide_state_out && (
								<ToggleControl
									label={__("Hide from logged in users")}
									help={
										!wpum_hide_state_in
											? __(
													"Visible to all users.",
													"wp-user-manager"
											  )
											: __(
													"Hidden from logged in users.",
													"wp-user-manager"
											  )
									}
									checked={wpum_hide_state_in}
									onChange={toggleStateIn => {
										props.setAttributes({
											wpum_hide_state_in: toggleStateIn
										});
									}}
								/>
							)}

						{wpum_restrict_type == "wpum_restrict_type_user" && (
							<SelectControl
								label={__(
									"Pick user(s) to hide this block from..."
								)}
								multiple
								value={wpum_hide_users}
								options={options}
								onChange={selectedUsers => {
									props.setAttributes({
										wpum_hide_users: selectedUsers
									});
								}}
							/>
						)}

						{wpum_restrict_type == "wpum_restrict_type_role" && (
							<SelectControl
								label={__(
									"Pick user role(s) to hide this block from..."
								)}
								multiple
								value={wpum_hide_roles}
								options={roleOptions}
								onChange={selectedRoles => {
									props.setAttributes({
										wpum_hide_roles: selectedRoles
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
