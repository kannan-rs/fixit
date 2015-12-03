<?php
$createFnOptions = "{'projectId' :".$projectId.", 'openAs' : '".$openAs."', 'popupType' : '".$popupType."', 'taskId' : '".$taskId."'}";
$createFn 		= "_issues.createForm(".$createFnOptions.")";
$headerText = "";

if(!$openAs || $openAs != "popup") {
	$headerText = $this->lang->line_arr('issues->headers->view_all');
}
?>

<div class="header-options">
	<?php echo $headerText ? "<h2>".$headerText."</h2>" : ""; ?> 
	<!-- <span class="options-icon"> -->
		<!-- Show Links above the table -->
		<div class="issues internal-tab-as-links" onclick="_issues.showIssuesList(event)">
			<a href="javascript:void(0);" data-option="open" 
				title="<?php echo $this->lang->line_arr('issues->buttons_links->open_title'); ?>">
				<?php echo $this->lang->line_arr('issues->buttons_links->open'); ?>
			</a>
			
			<a href="javascript:void(0);" data-option="closed" 
				title="<?php echo $this->lang->line_arr('issues->buttons_links->closed_title'); ?>">
				<?php echo $this->lang->line_arr('issues->buttons_links->closed'); ?>
			</a>

			<a href="javascript:void(0);" data-option="cancelled" 
				title="<?php echo $this->lang->line_arr('issues->buttons_links->cancelled_title'); ?>">
				<?php echo $this->lang->line_arr('issues->buttons_links->cancelled'); ?>
			</a>

			<a href="javascript:void(0);" data-option="all" 
				title="<?php echo $this->lang->line_arr('issues->buttons_links->all_title'); ?>">
				<?php echo $this->lang->line_arr('issues->buttons_links->all'); ?>
			</a>

			<span style="float:right;" >
				<a  class="step fi-page-add size-21" href="javascript:void(0);" 
					onclick="<?php echo $createFn; ?>" 
					title="<?php echo $this->lang->line_arr('issues->buttons_links->add_issues_title'); ?>">
				</a>
			</span>
		</div>
	<!-- </span> -->
</div>
<div>
	<!-- List all the Functions from database -->
	<?php
		if(count($issues) > 0) {
	?>
	<table cellspacing="0" class="issues-table-list">
		<tr class='heading'>
			<td class='cell text'><?php echo $this->lang->line_arr('issues->summary_table->issue_name'); ?></td>
			<td class='cell text'><?php echo $this->lang->line_arr('issues->summary_table->issue_status'); ?></td>
			<td class='cell date'><?php echo $this->lang->line_arr('issues->summary_table->issue_from_date'); ?></td>
			<td class='cell action'></td>
		</tr>
	<?php
	for($i = 0; $i < count($issues); $i++) { 
		$issue = $issues[$i];
		$deleteText = "Delete";
		$deleteFn = $deleteText ? "_issues.deleteRecord(".$issue->issue_id.")" : "";
		$editFnOptions = "{'projectId' :".$projectId.", 'openAs' : '".$openAs."', 'popupType' : '".$popupType."', 'taskId' : '".$taskId."', 'issueId' : ".$issue->issue_id."}";
		$issueEditFn	= "_issues.editForm(".$editFnOptions.")";

		$cssStatus = ($issue->status == "open" || $issue->status == "inProgress") ? "open" : $issue->status;
	?>
		<tr class='row viewAll <?php echo $cssStatus; ?>'>
			<td class='cell capitalize'>
				<a href="javascript:void(0);" onclick="_issues.viewOne('<?php echo $issues[$i]->issue_id; ?>')">
					<?php echo $issue->issue_name; ?>
				</a>
			</td>
			<td class="cell capitalize"><?php echo $issue->status; ?></td>
			<td class="cell capitalize date"><?php echo $issue->issue_from_date; ?></td>
			<td class='cell table-action'>
				<span>
					<a class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $issueEditFn; ?>" title="<?php echo $this->lang->line_arr('issues->buttons_links->edit_issue_title'); ?>"><span class="size-9"></a>
				</span>
			</td>
		</tr>
	<?php
		}
	?>
	</table>
	<?php
	} else {
	?>
	- No Issues Found -
	<?php
	}
	?>
</div>