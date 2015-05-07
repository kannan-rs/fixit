<h2>Permissions</h3>
<div class="permissions">
	<p class="note error"></p>
	<p class="note success"></p>
	<section class="section">
		<h3>Users</h3>
		<label class="label">Select Users</label>
		<section class="dataSection">
			<select id="users" onchange="securityObj._permissions.getPermissions()">
				<option value="0">--Select Users--</option>
				<?php
					for($uIdx = 0; $uIdx < count($data["users"]); $uIdx++) {
						echo "<option value=\"".$data["users"][$uIdx]->sno."\" data_user_type=\"".$data["users"][$uIdx]->account_type."\">".$data["users"][$uIdx]->user_name."</option>";
					}
				?>
			</select>
			<span class="user_type_as"> type as <span id="user_type"></span>
		</section>
	</section>
	<section class="section">
		<h3>Role</h3>
		<label class="label">Select Roles</label>
		<section class="dataSection">
			<?php
				for( $rIdx = 0; $rIdx < count($data["roles"]); $rIdx++) {
					echo "<input type=\"radio\" name=\"role\" value=\"".$data["roles"][$rIdx]->sno."\" data_role_id=\"".$data["roles"][$rIdx]->role_id."\" />".$data["roles"][$rIdx]->role_name."<br/>";
				}
			?>
		</section>
	</section>
	<section class="section">
		<h3>Operations</h3>
		<label class="label">Select Operations</label>
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
		<h3>Functions</h3>
		<label class="label">Select Functions</label>
		<section class="dataSection">
			<?php
				for( $fIdx = 0; $fIdx < count($data["functions"]); $fIdx++) {
					echo "<input type=\"checkbox\" name=\"functions\" value=\"".$data["functions"][$fIdx]->sno."\" data_function_id=\"".$data["functions"][$fIdx]->fn_id."\" />".$data["functions"][$fIdx]->fn_name."<br/>";
				}
			?>
		</section>
	</section>
	<section class="section">
		<h3>Data Filters</h3>
		<label class="label">Select Data Filters</label>
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
			<button type="button" id="permission_submit" onclick="securityObj._permissions.setPermissions()">Set Permissions</button>
		</p>
	</section>
</div>