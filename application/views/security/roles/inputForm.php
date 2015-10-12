<?php
$edit = false;
$prefix = "create";
$headerText = "Create Role";

if(isset($roles) && count($roles)) {
	$i = 0;
	$edit = true;
	$prefix = "update";
	$headerText = "Edit Role";
	$individualRole = $roles[0];
}

$sno 		= isset($individualRole) ? $individualRole->sno : "";
$role_id 	= isset($individualRole) ? $individualRole->role_id : "";
$role_name 	= isset($individualRole) ? $individualRole->role_name : "";
$role_desc 	= isset($individualRole) ? $individualRole->role_desc : "";

?>
<h2><?php echo $headerText; ?></h2>
<form id="<?php echo $prefix; ?>_role_form" name="update_role_form" class="inputForm">
	<input type="hidden" id='role_sno' value="<?php echo $sno; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label">Role Id</td>
				<td>
					<input type="text" name="roleId" id="roleId" value="<?php echo $role_id; ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label">Role Name</td>
				<td><input type="text" name="roleName" id="roleName" value="<?php echo $role_name; ?>" required></td>
			</tr>
			<tr>
				<td class="label">Role Description</td>
				<td><textarea rows="6" cols="30" name="roleDescr" id="roleDescr"><?php echo $role_desc; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_role_submit" onclick="securityObj._roles.<?php echo $prefix; ?>Validate()"><?php echo $prefix; ?> Role</button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>


<!-- Add Role Start -->
<!-- <h2></h2>
	<form id="create_role_form" name="create_role_form" class="inputForm">
	
				<div class="label">Role ID:</div>
				<div><input type="text" name="roleId" id="roleId" value="" required></div>
			</tr>
			<tr>
				<div class="label">Role Name:</div>
				<div><input type="text" name="roleName" id="roleName" required></div>
			</tr>
			<tr>
				<div class="label">Role Description:</div>
				<div><textarea rows="6" cols="30" name="roleDescr" id="roleDescr"></textarea></div>
			</tr>
			<tr>
				<p class="button-panel">
					<button type="button" id="create_role_submit" onclick="securityObj._roles.createValidate()">Create Role</button>
				</p>
			</tr>
		</tbody>
	</table>
</form> -->
<!-- Add Role Ends -->