<?php
	$editFn = "projectObj._projects.editProject('".$projectId."')";
	$deleteFn = "projectObj._projects.delete('".$projectId."'')";
	$project = $projects[0];
?>
<div class="header-options">
	<h2>Project Details</h2>
	<span class="options-icon">
		<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="Edit Project"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="Delete Project"></a></span>	
	</span>
</div>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="viewOne projectViewOne">
		<tr>
			<td class='cell label'>Project Title:</td>
			<td class='cell' ><?php echo $project->project_name; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Project Start Date</td>
			<td class='cell' ><?php echo $project->start_date; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Projected End Date</td>
			<td class='cell' ><?php echo $project->end_date; ?></td>
		</tr>
		<tr>
			<td class='cell label'>Budget</td>
			<td class='cell' >-- What is the calculation --</td>
		</tr>
	</table>
	<div id="accordion" class="accordion">
		<h3><span class="inner_accordion">Customer Details</span></h3>
		<div>
			<table cellspacing="0" class="viewOne projectViewOne">
				<tr>
					<td class='cell label'>First Name</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
				<tr>
					<td class='cell label'>Last Name</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
				<tr>
					<td class='cell label'>Address</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
				<tr>
					<td class='cell label'>Phone</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
				<tr>
					<td class='cell label'>Email</td>
					<td class='cell' >-- Need to Take from customer --</td>
				</tr>
			</table>
		</div>
		<h3><span class="inner_accordion">Insurance Details</span></h3>
		<div>
			<table cellspacing="0" class="viewOne projectViewOne">
				<tr>
					<td class='cell label'>Insurance Provider</td>
					<td class='cell' >-- Need to Take from Insirence Provider --</td>
				</tr>
				<tr>
					<td class='cell label'>Provider Address</td>
					<td class='cell' >-- Need to Take from Insirence Provider --</td>
				</tr>
				<tr>
					<td class='cell label'>Provider Phone</td>
					<td class='cell' >-- Need to Take from Insirence Provider --</td>
				</tr>
			</table>
		</div>
		<h3><span class="inner_accordion">Contractor Details</span></h3>
		<div>
			<table cellspacing="0" class="viewOne projectViewOne">
				<tr>
					<td class='cell label'>Name</td>
					<td class='cell' ><?php echo $project->contractorName; ?></td>
				</tr>
				<tr>
					<td class='cell label'>Company</td>
					<td class='cell' ><?php echo ($contractors && $contractors[0]) ? $contractors[0]->company : "--NA--"; ?></td>
				</tr>
				<tr>
					<td class='cell label'>Prefered Contact Mode</td>
					<td class='cell' ><?php echo ($contractors && $contractors[0]) ? $contractors[0]->prefer : "--NA--"; ?></td>
				</tr>
				<tr>
					<td class='cell label'>Contact Office Email</td>
					<td class='cell' ><?php echo ($contractors && $contractors[0]) ? $contractors[0]->office_email : "--NA--"; ?></td>
				</tr>
				<tr>
					<td class='cell label'>Contact Office Number</td>
					<td class='cell' ><?php echo ($contractors && $contractors[0]) ? $contractors[0]->office_ph : "--NA--"; ?></td>
				</tr>
				<tr>
					<td class='cell label'>Contact Mobile Number</td>
					<td class='cell' ><?php echo ($contractors && $contractors[0]) ? $contractors[0]->mobile_ph : "--NA--"; ?></td>
				</tr>
				<tr>
					<td class='cell label'>Address</td>
					<td class='cell' ><?php echo ($contractors && $contractors[0]) ? $contractors[0]->address1.",<br/>".$contractors[0]->address2.",<br/>".$contractors[0]->address3.",<br/>".$contractors[0]->address4.",<br/>".$contractors[0]->city.",<br/>".$contractors[0]->state.",<br/>".$contractors[0]->country.",<br/>".$contractors[0]->pin_code : "--NA--"; ?></td>
				</tr>
			</table>
		</div>
	</div>
	<!-- Project Description -->
	<h3>Project Description</h3>
	<table cellspacing="0" class="viewOne">
		<tr>
			<td class='cell'><?php echo $project->project_descr; ?></td>
		</tr>
	</table>
</div>