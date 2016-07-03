<?php
$contractorLable 	= "Selected Service Provider from Search result";
$contractorIdDb 	= "";

if(isset($projects) && count($projects)) {
	$project 			= $projects[0];
	$contractorIdDb 	= $project->contractor_id;
}

if(in_array(OPERATION_CHOOSE, $contractorPermission['operation']) || in_array(OPERATION_CREATE, $contractorPermission['operation']) || in_array(OPERATION_UPDATE, $contractorPermission['operation'])) {
?>
<form id="choose_sp_project_form" name="choose_sp_project_form" class="inputForm">
	<input type="hidden" id="contractorIdDb" value="<?php echo $contractorIdDb;?>">
	<table class='form'>
		<tbody>
			<!-- Project Service Provider Search and Adding -->
			<!-- List of added contractor from the serach result-->
			<tr class="contractor-search-selected">
				<td class="label"><?php echo $contractorLable; ?></td>
				<td>
					<ul id="contractorSearchSelected" class="connectedSortable" onclick="_projects.searchContractorAction(event)">
					</ul>
				</td>
			</tr>
			<!--<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->contractorZipCode'); ?></td>
				<td>
					<input type="text" name="contractorZipCode" id="contractorZipCode" value="" Placeholder="<?php echo $this->lang->line_arr('projects->input_form->contractorZipCode_ph'); ?>">
					<span class="fi-zoom-in size-21 searchIcon" onclick="_projects.getContractorListUsingServiceZip('')"></span>
				</td>
			</tr>-->
			<tr>
				<td class="label notMandatory">Search Service Provider By City</td>
				<td style="padding:0px;">
					<table style="margin:0px; height:24px;">
						<tr>
							<td style="padding:0px; width:240px;">
								<select name="contractorcity" id="contractorcity" 
									onkeyup="_utils.getAndSetMatchCity(this.value,'<?php echo $prefix; ?>_project_form','contractorcity')">
								</select>
							</td>
							<td style="padding:0px;">
								<span class="fi-zoom-in size-21 searchIcon" onclick="_projects.getContractorListUsingServiceCity('')"></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="contractor-search-result">
				<td class="label notMandatory">&nbsp;</td>
				<td>
					<ul id="contractorSearchResult" class="connectedSortable" onclick="_projects.searchContractorAction(event)"></ul>
				</td>
			</tr>
			<?php 
			if( in_array(OPERATION_CREATE, $contractorPermission['operation']) ) {
			?>
				<tr>
					<td  class="label notMandatory">&nbsp;</td>
					<td>
						<?php
							$start = "<a href=\"javascript:void(0);\" onclick=\"_contractors.createForm(event, {'openAs': 'popup', 'popupType' : '2'})\">";
							$end = "</a>";
							echo str_replace(["##replace1##", "##replace2##"], [$start, $end], $this->lang->line_arr('projects->buttons_links->new_contractor'));
						?>
					</td>
				</tr>
			<?php
			}
			?>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="update_project_submit" onclick="_projects.updateSPSubmit()"><?php echo $this->lang->line_arr('projects->buttons_links->update'); ?></button>
						<button type="button" onclick="_projects.closeDialog()"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php
}
?>
