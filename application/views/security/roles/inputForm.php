<?php
$edit = false;
$prefix = "create";

if(isset($roles) && count($roles)) {
	$i = 0;
	$edit = true;
	$prefix = "update";
	$individualRole = $roles[0];
}

$sno 		= isset($individualRole) ? $individualRole->sno : "";
$role_id 	= isset($individualRole) ? $individualRole->role_id : "";
$role_name 	= isset($individualRole) ? $individualRole->role_name : "";
$role_desc 	= isset($individualRole) ? $individualRole->role_desc : "";

?>
<h2><?php echo $this->lang->line_arr('role->headers->'.$prefix); ?></h2>
<form id="<?php echo $prefix; ?>_role_form" name="update_role_form" class="inputForm">
	<input type="hidden" id='role_sno' value="<?php echo $sno; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('role->input_form->roleId'); ?></td>
				<td>
					<input type="text" name="roleId" id="roleId" value="<?php echo $role_id; ?>" placeholder="<?php echo $this->lang->line_arr('role->input_form->roleId_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('role->input_form->roleName'); ?></td>
				<td><input type="text" name="roleName" id="roleName" value="<?php echo $role_name; ?>" placeholder="<?php echo $this->lang->line_arr('role->input_form->roleName_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('role->input_form->roleDescr'); ?></td>
				<td><textarea rows="6" cols="30" name="roleDescr" id="roleDescr" placeholder="<?php echo $this->lang->line_arr('role->input_form->roleDescr_ph'); ?>"><?php echo $role_desc; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_role_submit" onclick="_roles.<?php echo $prefix; ?>Validate()"><?php echo $this->lang->line_arr('role->buttons_links->'.$prefix); ?></button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>