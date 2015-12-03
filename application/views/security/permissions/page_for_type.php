<h2><?php echo $this->lang->line_arr('permission->headers->title'); ?></h2>
<div class="permission internal-tab-as-links" onclick="_permissions.showPermissionsPage(event)">
	<a href="javascript:void(0);" data-option="default" 
		title="<?php echo $this->lang->line_arr('permission->buttons_links->default_title'); ?>">
			<?php echo $this->lang->line_arr('permission->buttons_links->default'); ?>
	</a>
	<a href="javascript:void(0);" data-option="user" 
		title="<?php echo $this->lang->line_arr('permission->buttons_links->user_permission_title'); ?>">
			<?php echo $this->lang->line_arr('permission->buttons_links->user_permission'); ?>
	</a>
</div>
<p class="note error"></p>
<p class="note success"></p>
<div class="permissions">
	
	<?php
	if($type != 'default') {
	?>
	<!-- Users List Drop Down -->
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->user'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->user'); ?></label>
		<section class="dataSection">
			<select id="user" onchange="_permissions.getPermissions('<?php echo $type; ?>', 'user')">
				<option value=""><?php echo $this->lang->line_arr('permission->input_form->user_option_0'); ?></option>
				<?php
					for($uIdx = 0; $uIdx < count($data["users"]); $uIdx++) {
						echo "<option value=\"".$data["users"][$uIdx]->sno."\" data_user_type=\"".$data["users"][$uIdx]->account_type."\">".$data["users"][$uIdx]->user_name."</option>";
					}
				?>
			</select>
			<span class="user_type_as"> type as <span id="user_type"></span>
		</section>
	</section>
	<?php } ?>

	<!-- Role List Drop Down -->
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->role'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->role'); ?></label>
		<section class="dataSection">
			<select id="role" onchange="_permissions.getPermissions('<?php echo $type; ?>', 'role')">
				<option value=""><?php echo $this->lang->line_arr('permission->input_form->role_option_0'); ?></option>
			<?php
				for( $rIdx = 0; $rIdx < count($data["roles"]); $rIdx++) {
					echo "<option value=\"".$data["roles"][$rIdx]->sno."\" data_role_type=\"".$data["roles"][$rIdx]->role_name."\">".$data["roles"][$rIdx]->role_name."</option>";
					//echo "<input type=\"radio\" name=\"role\" value=\"".$data["roles"][$rIdx]->sno."\" data_role_id=\"".$data["roles"][$rIdx]->role_id."\" />".$data["roles"][$rIdx]->role_name."<br/>";
				}
			?>
			</select>
			<!-- <span class="role_type_as"> type as <span id="role_type"></span> -->
		</section>
	</section>

	<div id="permissionList">
		<table cellspacing="0" id="permissionListTable">
			<tr class="header">
				<!-- <td class="permUserName">User Name</td> -->
				<td clas="permRole">Role</td>
				<td clas="permFunction">Function</td>
				<td clas="permOperation">Operation</td>
				<td clas="permDataFilter">DataFilter</td>
				<td clas="permAction">Action</td>
			</tr>
			<tr class="cell NoData">
				<td colspan="6">No Permissions Set</td>
			</tr>
		</table>
	</div>

	<table id="addNewButtons">
		<tr>
			<td>
				<p class="button-panel">
					<button type="button" id="show_add_new_permission" 
						onclick="_permissions.inputForm('<?php echo $type; ?>')">Add Permission</button>
				</p>
			</td>
		</tr>
	</table>
</div>