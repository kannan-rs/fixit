<div class="create-link">
	<?php echo $internalLink; ?>
</div>
<h2>View Project Details</h3>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0">
	<?php
		if(count($projects) > 0) {
			$i = 0;
	?>
		<tr>
			<td class='cell label'>Project Title:</td>
			<td class='cell'><?php echo $projects[$i]->project_name; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Description:</td>
			<td class='cell'><?php echo $projects[$i]->project_descr; ?></td>
		</tr>
		<tr>
			<td class='cell label'>% Complete:</td>
			<td class='cell'><?php echo $projects[$i]->percentage; ?>%</td>
		</tr>
		<tr>
			<td class='cell label'>Associated Claim Number:</td>
			<td class='cell'><?php echo $projects[$i]->associated_claim_num; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Project Type:</td>
			<td class='cell'><?php echo $projects[$i]->project_type; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Start Date:</td>
			<td class='cell'><?php echo $projects[$i]->start_date; ?></td>
		</tr>
		<tr>
			<td class='cell label'>End Date:</td>
			<td class='cell'><?php echo $projects[$i]->end_date; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Project Status:</td>
			<td class='cell'><?php echo $projects[$i]->project_status; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Project budget:</td>
			<td class='cell'><?php echo $projects[$i]->project_budget; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Projerty Owner ID:</td>
			<td class='cell'><?php echo $projects[$i]->property_owner_id; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Contractor ID:</td>
			<td class='cell'><?php echo $projects[$i]->contractor_id; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Adjuster ID:</td>
			<td class='cell'><?php echo $projects[$i]->adjuster_id; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Customer ID:</td>
			<td class='cell'><?php echo $projects[$i]->customer_id; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Paind From budget:</td>
			<td class='cell'><?php echo $projects[$i]->paid_from_budget; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Remaining Budget:</td>
			<td class='cell'><?php echo $projects[$i]->remaining_budget; ?></td>
		</tr>
		<?php
		if($userType == "admin") {
		?>
		<tr>
			<td class='cell label'>Referral Fee:</td>
			<td class='cell'><?php echo $projects[$i]->referral_fee; ?></td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td class='cell label'>Project Lender:</td>
			<td class='cell'><?php echo $projects[$i]->project_lender; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Lend Amount:</td>
			<td class='cell'><?php echo $projects[$i]->lend_amount; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Created By</td>
			<td class='cell'><?php echo $projects[$i]->created_by_name; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Created On:</td>
			<td class='cell'><?php echo $projects[$i]->created_on_for_view; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Updated By</td>
			<td class='cell'><?php echo $projects[$i]->updated_by_name; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Last Updated on</td>
			<td class='cell'><?php echo $projects[$i]->updated_on_for_view; ?></td>
		</tr>
	<?php
		}
	?>
	</table>
</div>