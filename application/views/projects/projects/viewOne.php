<?php
	$editFn = "projectObj._projects.editProject('".$projectId."')";
	$deleteFn = "projectObj._projects.deleteRecord('".$projectId."')";
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
	<input type="hidden" id="projectCustomerName" value="<?php echo $project->contractor_id;?>">
	<input type="hidden" id="contractorIdDb" value="<?php echo $project->contractor_id;?>">
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
			<div id="contractor_accordion" class="accordion">
				<?php
				if(count($contractors)) {
					for($i = 0; $i < count($contractors); $i++) {
				?>
				<h3><span class="inner_accordion">Contractor Name: <?php echo $contractors[$i]->name; ?></span></h3>
				<div>
					<table cellspacing="0" class="viewOne projectViewOne">
						<!--<tr>
							<td class='cell label'>Name</td>
							<td class='cell' ><?php echo $contractors[$i]->name; ?></td>
						</tr>-->
						<tr>
							<td class='cell label'>Company</td>
							<td class='cell' ><?php echo $contractors[$i]->company; ?></td>
						</tr>
						<tr>
							<td class='cell label'>Prefered Contact Mode</td>
							<td class='cell' ><?php echo$contractors[$i]->prefer; ?></td>
						</tr>
						<tr>
							<td class='cell label'>Contact Office Email</td>
							<td class='cell' ><?php echo $contractors[$i]->office_email; ?></td>
						</tr>
						<tr>
							<td class='cell label'>Contact Office Number</td>
							<td class='cell' ><?php echo $contractors[$i]->office_ph; ?></td>
						</tr>
						<tr>
							<td class='cell label'>Contact Mobile Number</td>
							<td class='cell' ><?php echo $contractors[$i]->mobile_ph; ?></td>
						</tr>
						<tr>
							<td class='cell label'>Address</td>
							<td class='cell' ><?php echo $contractors[$i]->address1.",<br/>".$contractors[$i]->address2.",<br/>".$contractors[$i]->address3.",<br/>".$contractors[$i]->address4.",<br/>".$contractors[$i]->city.",<br/>".$contractors[$i]->state.",<br/>".$contractors[$i]->country.",<br/>".$contractors[$i]->pin_code; ?></td>
						</tr>
					</table>
				</div>
				<?php
					}
				} else {
				?>
					<span>Yet to assign contractor</span>
				<?php
				}
				?>
			</div>
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