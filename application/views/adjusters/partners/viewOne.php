<?php
	$partner		= $partners[0];
?>
<?php
	if(!$openAs || $openAs != "popup") {
?>
<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('partner->headers->view_one'); ?></h2>
	<span class="options-icon left-icon-list">
		<span class="ui-accordion-header-icon ui-icon ui-icon-plus expand-all" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->expand_all'); ?>" 
			onclick="_utils.viewOnlyExpandAll('partner_accordion')">
		</span>
		<span class="ui-accordion-header-icon ui-icon ui-icon-minus collapse-all" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->collapse_all'); ?>" 
			onclick="_utils.viewOnlyCollapseAll('partner_accordion')">
		</span>
	</span>
	<span class="options-icon">
		<?php
		//if( $role_disp_name == ROLE_SERVICE_PROVI)
		if(in_array(OPERATION_UPDATE, $adjusterPermission['operation'])) {
			$editFn 		= "_partners.editForm({'openAs':'popup', 'popupType' : 2})";
		?>
			<span>
				<a class="step fi-page-edit size-21" href="javascript:void(0);" 
					onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('partner->details_view->edit_title'); ?>">
				</a>
			</span>
		<?php
		}
		if(in_array(OPERATION_DELETE, $adjusterPermission['operation'])) {
			$deleteFn 		= "_partners.deleteRecord('".$partnerId."')";
		?>
			<span>
				<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" 
					onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('partner->details_view->delete_title'); ?>">
				</a>
			</span>
		<?php
		}
		?>	
	</span>
</div>
<?php
}
?>
<div class="clear"></div>

<div id="partner_accordion" class="accordion">
	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('partner->headers->partner_details'); ?></span></h3>
	<div>
		<table cellspacing="0" class="viewOne">
			<tbody>
				<tr>
					<!-- <td class='label'><?php echo $this->lang->line_arr('partner->details_view->name'); ?>:</td>
					<td class="capitalize"><?php echo $partner->name; ?></td> -->
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->company'); ?></td>
					<td class="capitalize"><?php echo $partner->company_name; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->default_contract_user'); ?></td>
					<td class="capitalize"><?php echo isset($partner->default_contact_user_disp_str) ? $partner->default_contact_user_disp_str : ""; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->type'); ?></td>
					<td class="capitalize"><?php echo $partner->type; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->status'); ?></td>
					<td class="capitalize"><?php echo $partner->status; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->license'); ?></td>
					<td class="capitalize"><?php echo $partner->license; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('partner->headers->partner_address'); ?></span></h3>
	<div>
		<table cellspacing="0" class="viewOne">
			<tbody>
				<?php
				echo $addressFile;
				?>
			</tbody>
		</table>
	</div>
	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('partner->headers->partner_address'); ?></span></h3>
	<div>
		<table cellspacing="0" class="viewOne">
			<tbody>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->office_email_iD'); ?></td>
					<td><?php echo $partner->work_email_id; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->office_number'); ?></td>
					<td><?php echo $partner->work_phone; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->personal_email_id'); ?></td>
					<td><?php echo $partner->personal_email_id; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->personal_mobile_number'); ?></td>
					<td><?php echo $partner->mobile_no; ?></td>
				</tr>
				<tr>
					<!-- <td class='label'><?php echo $this->lang->line_arr('partner->details_view->prefered_mode_for_contact'); ?></td>
					<td>
						<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo $partner->contact_pref; ?>" />
						<table class="innerOption">
							<tr>
								<td><input type="checkbox" name="prefContact" id="prefwNumber" value="wNumber" disabled></td>
								<td><?php echo $this->lang->line_arr('partner->details_view->pref_office_phone_number'); ?></td>
								<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="wEmailId" disabled></td>
								<td><?php echo $this->lang->line_arr('partner->details_view->pref_office_email_id'); ?></td>
							</tr>
							<tr>
								<td><input type="checkbox" name="prefContact" id="prefmNumber" value="mNumber" disabled></td>
								<td><?php echo $this->lang->line_arr('partner->details_view->pref_personal_mobile_number'); ?></td>
								<td><input type="checkbox" name="prefContact" id="prefwEmailId" value="pEmailId" disabled></td>
								<td><?php echo $this->lang->line_arr('partner->details_view->pref_personal_email_id'); ?></td>
							</tr>
						</table>
					</td> -->
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('partner->details_view->website_url'); ?></td>
					<td><?php echo $partner->website_url; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('partner->headers->partner_others'); ?></span></h3>
	<div>
		<table cellspacing="0" class="viewOne">
			<tbody>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('common_text->created_by'); ?></td>
					<td><?php echo $partner->created_by; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('common_text->created_on'); ?></td>
					<td><?php echo $partner->created_on; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('common_text->updated_by'); ?></td>
					<td><?php echo $partner->updated_by; ?></td>
				</tr>
				<tr>
					<td class='label'><?php echo $this->lang->line_arr('common_text->updated_on'); ?></td>
					<td><?php echo $partner->updated_on; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>