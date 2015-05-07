<?php
	$deleteText = "Delete Project";
	$deleteFn = $deleteText ? "projectObj._projects.delete(".$projects[0]->proj_id.")" : "";
?>
<div class="create-link">
	<a href="javascript:void(0);" onclick="projectObj._tasks.viewAll(<?php echo $projects[0]->proj_id; ?>)">Tasks</a>
	<a href="javascript:void(0);" onclick="projectObj._notes.viewAll('<?php echo $projects[0]->proj_id; ?>')">Notes</a>
	<a href="javascript:void(0);" onclick="projectObj._docs.viewAll('<?php echo $projects[0]->proj_id; ?>')">Documents</a>
	<a href="javascript:void(0);" onclick="projectObj._projects.editProject(<?php echo $projects[0]->proj_id; ?>)">Update Project</a>
	<a href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>"> <?php echo $deleteText; ?></a>
</div>
<h2>View Project Details</h3>
<div>
	<!-- List all the Functions from database -->
	<table>
	<?php
		if(count($projects) > 0) {
			$i = 0;
	?>
		<tr>
			<td class='cell'>Project Title:</td>
			<td class='cell'><?php echo $projects[$i]->project_name; ?></td>
		</tr>
		<tr>
			<td class='cell'>Description:</td>
			<td class='cell'><?php echo $projects[$i]->project_descr; ?></td>
		</tr>
		<tr>
			<td class='cell'>% Complete:</td>
			<td class='cell'><?php echo $projects[$i]->percentage; ?>%</td>
		</tr>
		<tr>
			<td class='cell'>Associated Claim Number:</td>
			<td class='cell'><?php echo $projects[$i]->associated_claim_num; ?></td>
		</tr>
		<tr>
			<td class='cell'>Project Type:</td>
			<td class='cell'><?php echo $projects[$i]->project_type; ?></td>
		</tr>
		<tr>
			<td class='cell'>Start Date:</td>
			<td class='cell'><?php echo $projects[$i]->start_date; ?></td>
		</tr>
		<tr>
			<td class='cell'>End Date:</td>
			<td class='cell'><?php echo $projects[$i]->end_date; ?></td>
		</tr>
		<tr>
			<td class='cell'>Project Status:</td>
			<td class='cell'><?php echo $projects[$i]->project_status; ?></td>
		</tr>
		<tr>
			<td class='cell'>Project budget:</td>
			<td class='cell'><?php echo $projects[$i]->project_budget; ?></td>
		</tr>
		<tr>
			<td class='cell'>Projerty Owner ID:</td>
			<td class='cell'><?php echo $projects[$i]->property_owner_id; ?></td>
		</tr>
		<tr>
			<td class='cell'>Contractor ID:</td>
			<td class='cell'><?php echo $projects[$i]->contractor_id; ?></td>
		</tr>
		<tr>
			<td class='cell'>Adjuster ID:</td>
			<td class='cell'><?php echo $projects[$i]->adjuster_id; ?></td>
		</tr>
		<tr>
			<td class='cell'>Customer ID:</td>
			<td class='cell'><?php echo $projects[$i]->customer_id; ?></td>
		</tr>
		<tr>
			<td class='cell'>Paind From budget:</td>
			<td class='cell'><?php echo $projects[$i]->paid_from_budget; ?></td>
		</tr>
		<tr>
			<td class='cell'>Remaining Budget:</td>
			<td class='cell'><?php echo $projects[$i]->remaining_budget; ?></td>
		</tr>
		<tr>
			<td class='cell'>Referral Fee:</td>
			<td class='cell'><?php echo $projects[$i]->referral_fee; ?></td>
		</tr>
		<tr>
			<td class='cell'>Project Lender:</td>
			<td class='cell'><?php echo $projects[$i]->project_lender; ?></td>
		</tr>
		<tr>
			<td class='cell'>Lend Amount:</td>
			<td class='cell'><?php echo $projects[$i]->lend_amount; ?></td>
		</tr>
		<tr>
			<td class='cell'>Created By</td>
			<td class='cell'><?php echo $projects[$i]->created_by_name; ?></td>
		</tr>
		<tr>
			<td class='cell'>Created On:</td>
			<td class='cell'><?php echo $projects[$i]->created_on_for_view; ?></td>
		</tr>
		<tr>
			<td class='cell'>Updated By</td>
			<td class='cell'><?php echo $projects[$i]->updated_by_name; ?></td>
		</tr>
		<tr>
			<td class='cell'>Last Updated on</td>
			<td class='cell'><?php echo $projects[$i]->updated_on_for_view; ?></td>
		</tr>
	<?php
		}
	?>
	</table>
</div>