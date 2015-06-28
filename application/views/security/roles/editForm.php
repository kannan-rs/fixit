<?php
$i = 0;
?>
<h2>Edit Role</h2>
<form id="update_role_form" name="update_role_form">
	<input type="hidden" id='role_sno' value="<?php echo $roles[$i]->sno; ?>" />
	<div class='form'>
		<div class="label">Role Id</div>
		<div>
			<input type="text" name="roleId" id="roleId" value="<?php echo $roles[$i]->role_id; ?>" required>
		</div>
		<div class="label">Role Name</div>
		<div><input type="text" name="roleName" id="roleName" value="<?php echo $roles[$i]->role_name; ?>" required></div>
		<div class="label">Role Description</div>
		<div><textarea rows="6" cols="30" name="roleDescr" id="roleDescr"><?php echo $roles[$i]->role_desc; ?></textarea></div>
		<p class="button-panel">
			<button type="button" id="update_role_submit" onclick="securityObj._roles.updateValidate()">Update Role</button>
		</p>
	</div>
</form>