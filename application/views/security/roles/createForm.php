<!-- Add Role Start -->
<h2>Create Role</h3>
	<form id="create_role_form" name="create_role_form">
	<div class='form'>
		<p>
			<div class="label">Role ID:</div>
			<div>
				<input type="text" name="roleId" id="roleId" value="" required>
			</div>
		</p>
		<p>
			<div class="label">Role Name:</div>
			<div><input type="text" name="roleName" id="roleName" required></div>
		</p>
		<p>
			<div class="label">Role Description:</div>
			<div><textarea rows="6" cols="30" name="roleDescr" id="roleDescr"></textarea></div>
		</p>
		<p class="button-panel">
			<button type="button" id="create_role_submit" onclick="securityObj._roles.createValidate()">Create Role</button>
		</p>
	</div>
</form>
<!-- Add Role Ends -->