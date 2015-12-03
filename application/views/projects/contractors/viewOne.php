<?php
	$editFn 		= "_contractors.editForm({'openAs':'popup', 'popupType' : 2})";
	$deleteFn 		= "_contractors.deleteRecord('".$contractorId."')";
	$contractor		= $contractors[0];
?>
<?php
	if(!$openAs || $openAs != "popup") {
?>
<div class="header-options">
	<h2><?php echo $this->lang->line_arr('contractor->headers->view_one'); ?></h2>
	<span class="options-icon">
		<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('contractor->buttons_links->edit_hover_text'); ?>"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('contractor->buttons_links->delete_hover_text'); ?>"></a></span>	
	</span>
</div>
<?php
}
?>
<div class="clear"></div>
<form>
	<div class='form'>
	<!-- List all the Functions from database -->
			<!-- <div class='label'><?php echo $this->lang->line_arr('contractor->details_view->name'); ?>:</div>
			<div class="capitalize"><?php echo $contractor->name; ?></div> -->
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->company'); ?></div>
			<div class="capitalize"><?php echo $contractor->company; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->type'); ?></div>
			<div class="capitalize"><?php echo $contractor->type; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->license'); ?></div>
			<div class="capitalize"><?php echo $contractor->license; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->bbb'); ?></div>
			<div class="capitalize"><?php echo $contractor->bbb; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->dtatus'); ?></div>
			<div class="capitalize"><?php echo $contractor->status; ?></div>
			<?php
			echo $addressFile;
			?>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->office_email_id'); ?></div>
			<div><?php echo $contractor->office_email; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->office_number'); ?></div>
			<div><?php echo $contractor->office_ph; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->mobile_number'); ?></div>
			<div><?php echo $contractor->mobile_ph; ?></div>
			<!-- <div class='label'><?php echo $this->lang->line_arr('contractor->details_view->prefered_mode'); ?></div>
			<div>
				<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo $contractor->prefer; ?>" />
				<table class="innerOption">
					
						<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId" disabled></td>
						<td><?php echo $this->lang->line_arr('contractor->details_view->email'); ?></td>
						<td><input type="checkbox" name="prefContact" id="prefContactofficeNumber" value="officeNumber" disabled></td>
						<td><?php echo $this->lang->line_arr('contractor->details_view->office_phone'); ?></td>
						<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber" disabled></td>
						<td><?php echo $this->lang->line_arr('contractor->details_view->mobile_number'); ?></td>
					
				</table>
			</div> -->
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->webSite_url'); ?></div>
			<div><?php echo $contractor->website_url; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->serive_provided'); ?></div>
			<div><?php echo $contractor->service_area; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->created_by'); ?></div>
			<div><?php echo $contractor->created_by; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->created_on'); ?></div>
			<div><?php echo $contractor->created_on; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->updated_by'); ?></div>
			<div><?php echo $contractor->updated_by; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('contractor->details_view->updated_on'); ?></div>
			<div><?php echo $contractor->updated_on; ?></div>
	<!-- </table> -->
	</div>
</form>