<?php
	$editFn 		= "projectObj._issuess.editForm({'openAs':'popup', 'popupType' : 2})";
	$deleteFn 		= "projectObj._issuess.deleteRecord('".$issueId."')";
	$issues			= $issues && count($issues) ? $issues[0] : [];

	if(count($issues) == 0) {
		echo "This Issue is either cancelled or closed";
		return;
	}

	$assignedToUser 	= "";

	if($issues->assigned_to_user_type == "contractor") {
		if($assigneeDetails && $assigneeDetails["contractorDetails"] && count($assigneeDetails["contractorDetails"])) {
			$contractorDetails = $assigneeDetails["contractorDetails"][0];
			$assignedToUser = $contractorDetails->name." from ".$contractorDetails->company;
		}
	}

	if($issues->assigned_to_user_type == "adjuster") {
		if($assigneeDetails && $assigneeDetails["adjusterDetails"] && count($assigneeDetails["adjusterDetails"])) {
			$adjusterDetails = $assigneeDetails["adjusterDetails"][0];
			$assignedToUser = $adjusterDetails->name." from ".$adjusterDetails->company_name;
		}
	}

	if($issues->assigned_to_user_type == "customer") {
		if($assigneeDetails && $assigneeDetails["customerDetails"] && count($assigneeDetails["customerDetails"])) {
			$customerDetails = $assigneeDetails["customerDetails"][0];
			$assignedToUser = $customerDetails->first_name." ".$customerDetails->last_name;
		}
	}

	$assignedToUser = !empty($assignedToUser) ? $assignedToUser : "-NA-";
?>
<?php
	if(!$openAs || $openAs != "popup") {
?>
<div class="header-options">
	<h2><?php echo $this->lang->line_arr('issues->headers->view_one'); ?></h2>
	<span class="options-icon">
		<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('issues->details_view->edit_single_issue_title'); ?>"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('issues->details_view->delete_title'); ?>"></a></span>	
	</span>
</div>
<?php
}
?>
<div class="clear"></div>
<form>
	<div class='form'>
	<!-- List all the Functions from database -->
			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->issue_name'); ?>:</div>
			<div class="capitalize"><?php echo $issues->issue_name; ?></div>

			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->issue_description'); ?>:</div>
			<div class="capitalize"><?php echo $issues->issue_desc; ?></div>

			<div class='label'>Assigned to Type</div>
			<div class="capitalize"><?php echo $issues->assigned_to_user_type; ?></div>

			<?php if(!empty($issues->assigned_to_user_type)) { ?>
			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->assigned_to'); ?> <?php echo $issues->assigned_to_user_type; ?></div>
			<div class="capitalize"><?php echo $assignedToUser; ?></div>
			<?php } ?>

			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->issue_from_date'); ?></div>
			<div><?php echo $issues->issue_from_date_for_view; ?></div>

			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->assigned_date'); ?></div>
			<div><?php echo $issues->assigned_date_for_view; ?></div>

			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->issue_status'); ?></div>
			<div><?php echo $issues->status; ?></div>

			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->issue_notes'); ?></div>
			<div><?php echo $issues->notes; ?></div>

			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->created_by'); ?></div>
			<div><?php echo $issues->created_by_name; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->created_on'); ?></div>
			<div><?php echo $issues->created_on_for_view; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->updated_by'); ?></div>
			<div><?php echo $issues->updated_by_name; ?></div>
			<div class='label'><?php echo $this->lang->line_arr('issues->details_view->updated_on'); ?></div>
			<div><?php echo $issues->updated_on_for_view; ?></div>

	</div>
</form>
