<h2><?php echo $this->lang->line_arr('permission->headers->title'); ?></h2>
<div class="permission internal-tab-as-links" onclick="_projects.showProjectsList(event)">
	<a href="javascript:void(0);" data-option="default" 
		title="<?php echo $this->lang->line_arr('permission->buttons_links->default_title'); ?>">
			<?php echo $this->lang->line_arr('permission->buttons_links->default'); ?>
	</a>
	<a href="javascript:void(0);" data-option="userPermission" 
		title="<?php echo $this->lang->line_arr('permission->buttons_links->user_permission_title'); ?>">
			<?php echo $this->lang->line_arr('permission->buttons_links->user_permission'); ?>
	</a>
</div>
<div class="permissions">
	<p class="note error"></p>
	<p class="note success"></p>
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->user'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->user'); ?></label>
		<section class="dataSection">
			<select id="users" onchange="_permissions.getPermissions()">
				<option value="0"><?php echo $this->lang->line_arr('permission->input_form->user_option_0'); ?></option>
				<?php
					for($uIdx = 0; $uIdx < count($data["users"]); $uIdx++) {
						echo "<option value=\"".$data["users"][$uIdx]->sno."\" data_user_type=\"".$data["users"][$uIdx]->role_id."\">".$data["users"][$uIdx]->user_name."</option>";
					}
				?>
			</select>
			<span class="user_type_as"> type as <span id="user_type"></span>
		</section>
	</section>
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->role'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->role'); ?></label>
		<section class="dataSection">
			<?php
				for( $rIdx = 0; $rIdx < count($data["roles"]); $rIdx++) {
					echo "<input type=\"radio\" name=\"role\" value=\"".$data["roles"][$rIdx]->sno."\" data_role_id=\"".$data["roles"][$rIdx]->role_id."\" />".$data["roles"][$rIdx]->role_name."<br/>";
				}
			?>
		</section>
	</section>
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->operation'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->operation'); ?></label>
		<section class="dataSection">
			<?php
				for( $oIdx = 0; $oIdx < count($data["operations"]); $oIdx++) {
					echo "<input type=\"checkbox\" name=\"operations\" value=\"".$data["operations"][$oIdx]->sno."\" data_operation_id=\"".$data["operations"][$oIdx]->ope_id."\" />".$data["operations"][$oIdx]->ope_name."<br/>";
				}
			?>
		</section>
	</section>
	</section>
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->function'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->function'); ?></label>
		<section class="dataSection">
			<?php
				for( $fIdx = 0; $fIdx < count($data["functions"]); $fIdx++) {
					echo "<input type=\"checkbox\" name=\"functions\" value=\"".$data["functions"][$fIdx]->sno."\" data_function_id=\"".$data["functions"][$fIdx]->fn_id."\" />".$data["functions"][$fIdx]->fn_name."<br/>";
				}
			?>
		</section>
	</section>
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->data_filter'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->data_filter'); ?></label>
		<section class="dataSection">
			<?php
				for( $dIdx = 0; $dIdx < count($data["dataFilters"]); $dIdx++) {
					echo "<input type=\"checkbox\" name=\"dataFilters\" value=\"".$data["dataFilters"][$dIdx]->sno."\" data_data_filter_id=\"".$data["dataFilters"][$dIdx]->data_filter_id."\" />".$data["dataFilters"][$dIdx]->data_filter_name."<br/>";
				}
			?>
		</section>
	</section>
	<!--
	<section class="section">
		<h3>API for this User</h3>
		<section class="dataSection" id="print_api">
		</section>
	</section>
	-->
	<section>
		<p class="button-panel">
			<button type="button" id="permission_submit" onclick="_permissions.setPermissions()"><?php echo $this->lang->line_arr('permission->buttons_links->set'); ?></button>
		</p>
	</section>
</div>