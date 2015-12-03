	<?php
	//print_r($permission);
	$fnId = "";
	$opId = "";
	$dfId = "";
	if($permission && $permission[0]) {
		$fnId = $permission[0]->function_id;
		$opId = $permission[0]->op_id;
		$dfId = $permission[0]->data_filter_id;
	}
	?>
	<!--User Display String -->
	<section class="section">
		<h3>Selected User : <span id="selectedUserDisplayStr"></span></h3>
	</section>

	<!-- Role Display String  -->
	<section class="section">
		<h3>Selected Role : <span id="selectedRoleDisplayStr"></span></h3>
	</section>

	<input type="hidden" name="dbPermissionId" id="dbPermissionId" value="<?php echo $permissionId; ?>">
	<input type="hidden" name="dbFunctionId" id="dbFunctionId" value="<?php echo $fnId; ?>">
	<!-- Function Dropdown  -->
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->function'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->function'); ?></label>
		<section class="dataSection">
			<select id="functions" onchange="_permissions.getPermissions('<?php echo $type; ?>')">
				<option value="0"><?php echo $this->lang->line_arr('permission->input_form->function_option_0'); ?></option>
			<?php
				for( $fIdx = 0; $fIdx < count($data["functions"]); $fIdx++) {
					echo "<option value=\"".$data["functions"][$fIdx]->sno."\" 
						data_function_type=\"".$data["functions"][$fIdx]->fn_id."\">".$data["functions"][$fIdx]->fn_name."</option>";
					//echo "<input type=\"checkbox\" name=\"functions\" value=\"".$data["functions"][$fIdx]->sno."\" data_function_id=\"".$data["functions"][$fIdx]->fn_id."\" />".$data["functions"][$fIdx]->fn_name."<br/>";
				}
			?>
			</select>
		</section>
	</section>

	<input type="hidden" name="dbOperationId" id="dbOperationId" value="<?php echo $opId; ?>">
	<!-- Operations List Multi Select Option -->
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

	<!-- Data Filter List  -->
	<input type="hidden" name="dbdataFilterId" id="dbdataFilterId" value="<?php echo $dfId; ?>">
	<section class="section">
		<h3><?php echo $this->lang->line_arr('permission->headers->data_filter'); ?></h3>
		<label class="label"><?php echo $this->lang->line_arr('permission->input_form->data_filter'); ?></label>
		<section class="dataSection">
			<?php
				for( $dIdx = 0; $dIdx < count($data["dataFilters"]); $dIdx++) {
					echo "<input type=\"checkbox\" name=\"dataFilters\" value=\"".$data["dataFilters"][$dIdx]->sno."\" 
						data_data_filter_id=\"".$data["dataFilters"][$dIdx]->data_filter_id."\" />".$data["dataFilters"][$dIdx]->data_filter_name."<br/>";
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
			<button type="button" id="permission_submit" onclick="_permissions.setPermissions('<?php echo $type; ?>')"><?php echo $this->lang->line_arr('permission->buttons_links->set'); ?></button>
		</p>
	</section>