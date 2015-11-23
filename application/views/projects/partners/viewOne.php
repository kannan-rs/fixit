<?php
	$editFn 		= "projectObj._partners.editForm({'openAs':'popup', 'popupType' : 2})";
	$deleteFn 		= "projectObj._partners.deleteRecord('".$partnerId."')";
	$partner		= $partners[0];
?>
<?php
	if(!$openAs || $openAs != "popup") {
?>
<div class="header-options">
	<h2><?php echo $this->lang->line_arr('partner->headers->view_one'); ?></h2>
	<span class="options-icon">
		<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('partner->details_view->edit_title'); ?>"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('partner->details_view->delete_title'); ?>"></a></span>	
	</span>
</div>
<?php
}
?>
<div class="clear"></div>
<form>
	<div class='form'>
	<!-- List all the Functions from database -->
			<!-- <div class='label'><?php echo $this->lang->line_arr('partner->details_view->name'); ?>:</div>
			<div class="capitalize"><?php echo $partner->name; ?></div> -->
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->company'); ?></div>
			<div class="capitalize"><?php echo $partner->company_name; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->type'); ?></div>
			<div class="capitalize"><?php echo $partner->type; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->status'); ?></div>
			<div class="capitalize"><?php echo $partner->status; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->license'); ?></div>
			<div class="capitalize"><?php echo $partner->license; ?></div>
			<?php
			echo $addressFile;
			?>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->office_email_iD'); ?></div>
			<div><?php echo $partner->work_email_id; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->office_number'); ?></div>
			<div><?php echo $partner->work_phone; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->personal_email_id'); ?></div>
			<div><?php echo $partner->personal_email_id; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->personal_mobile_number'); ?></div>
			<div><?php echo $partner->mobile_no; ?></div>
			<!-- <div class='label'><?php echo $this->lang->line_arr('partner->details_view->prefered_mode_for_contact'); ?></div>
			<div>
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
			</div> -->
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->website_url'); ?></div>
			<div><?php echo $partner->website_url; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->created_by'); ?></div>
			<div><?php echo $partner->created_by; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->created_on'); ?></div>
			<div><?php echo $partner->created_on; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->updated_by'); ?></div>
			<div><?php echo $partner->updated_by; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('partner->details_view->updated_on'); ?></div>
			<div><?php echo $partner->updated_on; ?></div>
	<!-- </table> -->
	</div>
</form>