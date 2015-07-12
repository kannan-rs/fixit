<?php
	$editFn 	= "projectObj._projects.editProject('".$projectId."')";
	$deleteFn 	= "projectObj._projects.deleteRecord('".$projectId."')";
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
	<input type="hidden" id="contractorIdDb" value="<?php echo $project->contractor_id;?>">
	<table cellspacing="0" class="viewOne projectViewOne">
		<tr>
			<td class='cell label'>Project Title:</td>
			<td class='cell' ><?php echo $project->project_name; ?></td>
		</tr>
	</table>
	<div id="accordion" class="accordion">
		<h3><span class="inner_accordion">Project Description</span></h3>
		<table cellspacing="0" class="viewOne">
			<tr>
				<td class='cell'><?php echo $project->project_descr; ?></td>
			</tr>
		</table>

		<h3><span class="inner_accordion">Project Date</span></h3>
		<div>
			<table cellspacing="0" class="viewOne">
				<tr>
					<td class='cell label'>Project Start Date</td>
					<td class='cell' ><?php echo $project->start_date; ?></td>
					<td class='cell label'>Projected End Date</td>
					<td class='cell' ><?php echo $project->end_date; ?></td>
				</tr>
			</table>
		</div>
		<h3><span class="inner_accordion">Project Address</span></h3>
		<div class="form">
			<?php
				echo $addressFile;
			?>
		</div>
		<h3><span class="inner_accordion">Budget</span></h3>
		<div>
			<table cellspacing="0" class="viewOne projectViewOne">
				<tr>
					<td class='cell label'>Project Budget</td>
					<td class='cell' ><?php echo $project->project_budget; ?></td>
				</tr>
				<tr>
					<td class='cell label'>Paid from Budget</td>
					<td class='cell' >
						<?php echo $project->paid_from_budget; ?>
						<span>
							<a style="margin-left: 30px;" href="javascript:void(0);" onclick="projectObj._remainingBudget.getListWithForm({'openAs': 'popup', 'popupType' : '2', 'projectId' :  <?php echo $projectId; ?>})">Update remaining budget</a>
						</span>
					</td>
				</tr>
				<tr>
					<td class='cell label'>Remaining Bbudget</td>
					<td class='cell' ><?php echo ($project->project_budget - $project->paid_from_budget); ?></td>
				</tr>
				<tr>
					<td class='cell label'>Deductible</td>
					<td class='cell' ><?php echo $project->deductible; ?></td>
				</tr>
				<?php
				if($userType == "admin") {
				?>
				<tr>
					<td class='cell label'>Referral Fee</td>
					<td class='cell' ><?php echo (($project->project_budget - $project->deductible)/100) * 7; ?></td>
				</tr>
				<?php
				}
				?>
			</table>
		</div>
		<?php echo $customerFile; ?>
		
		<h3>
			<span class="inner_accordion">Contractor Details</span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._contractors.createForm({'projectId': '<?php echo $projectId; ?>', 'popup': true, 'openAs': 'popup'});" title="Add Contractor"></a>
		</h3>
		<div>
			<div id="contractor_accordion" class="accordion">
				<?php
				if(count($contractors)) {
					for($i = 0; $i < count($contractors); $i++) {
				?>
				<h3><span class="inner_accordion">Contractor Name: <?php echo $contractors[$i]->name; ?></span></h3>
				<div>
					<table cellspacing="0" class="viewOne projectViewOne">
						<tr>
							<td class='cell label'>Name</td>
							<td class='cell' ><?php echo $contractors[$i]->name; ?></td>
						</tr>
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
		<h3>
			<span class="inner_accordion">Tasks List</span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._projects.addTask();" title="Add Task"></a>
		</h3>
		<div class="project_section" id="task_content"></div>
		<h3>
			<span class="inner_accordion">Notes</span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._projects.addProjectNote();" title="Add Note"></a>
		</h3>
		<div class="project_section" id="note_content"></div>
		<h3>
			<span class="inner_accordion">Documents</span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._projects.addDocumentForm();" title="Add Documents"></a>
		</h3>
		<div class="project_section" id="attachment_content"></div>

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

	</div>
</div>