<?php
$createFnOptions = "{'projectId' :".$projectId.", 'openAs' : '".$openAs."', 'popupType' : '".$popupType."', 'taskId' : '".$taskId."'}";
$createFn 		= "projectObj._issues.createForm(".$createFnOptions.")";
$headerText = "";

if(!$openAs || $openAs != "popup") {
	$headerText = "Issues List";
}
?>

<div class="header-options">
	<?php echo $headerText ? "<h2>".$headerText."</h2>" : ""; ?> 
	<!-- <span class="options-icon"> -->
		<!-- Show Links above the table -->
		<div class="issues internal-tab-as-links" onclick="projectObj._issues.showIssuesList(event)">
			<a href="javascript:void(0);" data-option="open">Open Issues</a>
			<a href="javascript:void(0);" data-option="closed">Closed Issues</a>
			<a href="javascript:void(0);" data-option="cancelled">Cancelled Issues</a>
			<a href="javascript:void(0);" data-option="all">All Issues</a>
			<span style="float:right;" ><a  class="step fi-page-add size-21" href="javascript:void(0);" onclick="<?php echo $createFn; ?>" title="Add issues to this project"></a></span>
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
			<td class='cell text'>Issue Name</td>
			<td class='cell text'>Issue Status</td>
			<td class='cell date'>Issue From Date</td>
			<td class='cell action'></td>
		</tr>
	<?php
	for($i = 0; $i < count($issues); $i++) { 
		$issue = $issues[$i];
		$deleteText = "Delete";
		$deleteFn = $deleteText ? "projectObj._issues.deleteRecord(".$issue->issue_id.")" : "";
		$editFnOptions = "{'projectId' :".$projectId.", 'openAs' : '".$openAs."', 'popupType' : '".$popupType."', 'taskId' : '".$taskId."', 'issueId' : ".$issue->issue_id."}";
		$issueEditFn	= "projectObj._issues.editForm(".$editFnOptions.")";

		$cssStatus = ($issue->status == "open" || $issue->status == "inProgress") ? "open" : $issue->status;
	?>
		<tr class='row viewAll <?php echo $cssStatus; ?>'>
			<td class='cell capitalize'>
				<a href="javascript:void(0);" onclick="projectObj._issues.viewOne('<?php echo $issues[$i]->issue_id; ?>')">
					<?php echo $issue->issue_name; ?>
				</a>
			</td>
			<td class="cell capitalize"><?php echo $issue->status; ?></td>
			<td class="cell capitalize date"><?php echo $issue->issue_from_date; ?></td>
			<td class='cell table-action'>
				<span>
					<a class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $issueEditFn; ?>" title="Edit Issues"><span class="size-9"></a>
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