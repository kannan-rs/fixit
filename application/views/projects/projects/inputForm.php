<?php
	$prefix = "create";
	$formType = "create";

	/*
	Initilizing Values
	*/
	$project_sno 			= "";
	$projectTitle 			= "";
	$description 			= "";
	$db_project_type 		= "";
	$db_project_status 		= "";
	$start_date 			= "";
	$end_date 				= "";
	$contractorIdDb 		= "";
	$adjusterIdDb 			= "";
	$project_budget 		= "";
	$lend_amount 			= "";
	$project_lender 		= "";
	$deductible 			= "";
	$property_owner_id 		= "";
	$customer_id 			= "";
	$associated_claim_num 	= "";

	$contractorLable 	= "Selected Contractor from Search result";
	$adjusterLable		= "Selected Adjuster from Search result";
	
	if(isset($projects) && count($projects)) {
		$prefix = "update";
		$formType = "edit";
		$project = $projects[0];

		$project_sno 			= $project->proj_id;;
		$projectTitle 			= $project->project_name;
		$description 			= $project->project_descr;
		$db_project_type 		= $project->project_type;
		$db_project_status 		= $project->project_status;
		$start_date 			= $project->start_date;
		$end_date 				= $project->end_date;
		$contractorIdDb 		= $project->contractor_id;
		$adjusterIdDb 			= $project->adjuster_id;
		$project_budget 		= $project->project_budget;
		$lend_amount 			= $project->lend_amount;
		$project_lender 		= $project->project_lender;
		$deductible 			= $project->deductible;
		$property_owner_id 		= $project->property_owner_id;
		$customer_id 			= $project->customer_id;
		$associated_claim_num 	= $project->associated_claim_num;

		$contractorLable = "Contractor's List";
		$adjusterLable		= "Adjuster's List";

	} else {
		$header = "Create Project";
	}
?>

<!-- Title for the Page -->
<?php if(isset($header) && !empty($header)) { ?>
<h2>Create Project</h2>
<?php } ?>

<form id="<?php echo $prefix; ?>_project_form" name="<?php echo $prefix; ?>_project_form" class="inputForm">
	
	<input type="hidden" id='project_sno' value="<?php echo $project_sno; ?>" />
	<input type="hidden" id="db_project_type" name="db_project_type" value="<?php echo $db_project_type;?>">
	<input type="hidden" id="db_project_status" name="db_project_status" value="<?php echo $db_project_status;?>">
	<input type="hidden" id="contractorIdDb" value="<?php echo $contractorIdDb;?>">
	<input type="hidden" value="<?php echo $adjusterIdDb;?>" name="adjusterIdDb" id="adjusterIdDb">
	<input type="hidden" value="<?php echo $customer_id;?>" name="customer_id" id="customer_id">

	<table class='form'>
		<tbody>
			<!-- Project title -->
			<tr>
				<td class="label">Project Title:</td>
				<td>
					<input type="text" name="projectTitle" id="projectTitle" value="<?php echo $projectTitle; ?>" required>
				</td>
			</tr>

			<!-- Project Description -->
			<tr>
				<td class="label">Description:</td>
				<td>
					<textarea type="text" name="description" id="description" rows="10" cols="70" required><?php echo $description; ?></textarea>
				</td>
			</tr>

			<!-- Project Type -->
			<tr>
				<td class="label notMandatory">Project Type</td>
				<td>
					<select name="project_type" id="project_type">
					<option value="">--Select Project Type--</option>
					<option value="commercial">Commercial</option>
					<option value="personal">Personal</option>
					</select>
				</td>
			</tr>

			<!-- Project Status -->
			<tr>
				<td class="label notMandatory">Project Status</td>
				<td>
					<select name="project_status" id="project_status">
						<option value="">--Select Project Status--</option>
						<option value="project created">Project Created</option>
						<option value="not assigned">Not Assigned</option>
						<option value="assigned to contractor">Assigned to Contractor</option>
						<option value="work in progress">Work in Progress</option>
						<option value="completed">Completed</option>
					</select>
				</td>
			</tr>

			<!-- Project Start Date -->
			<tr>
				<td class="label notMandatory">Project Start Date</td>
				<td>
					<input type="text" name="start_date" id="start_date" value="<?php echo $start_date; ?>">
				</td>
			</tr>

			<!-- Project End Date -->
			<tr>
				<td class="label notMandatory">Project End Date</td>
				<td>
					<input type="text" name="end_date" id="end_date" value="<?php echo $end_date; ?>">
				</td>
			</tr>

			<!-- Project Address -->
			<?php
				echo $addressFile;
			?>

			<!-- Project Contractor Search and Adding -->
			
			<tr class="contractor-search-selected">
				<td class="label"><?php echo $contractorLable; ?></td>
				<td>
					<ul id="contractorSearchSelected" class="connectedSortable" onclick="projectObj._projects.searchContractorAction()">
					</ul>
				</td>
			</tr>
			<tr>
				<td class="label notMandatory">Search Contractor By Zip Code</td>
				<td>
					<input type="text" name="contractorZipCode" id="contractorZipCode" value="" Placeholder="Zip Code for search">
					<span class="fi-zoom-in size-21 searchIcon" onclick="projectObj._projects.getContractorListUsingServiceZip('')"></span>
				</td>
			</tr>
			<tr>
				<td  class="label notMandatory">&nbsp;</td>
				<td class="contractor-search-result">
					<ul id="contractorSearchResult" class="connectedSortable" onclick="projectObj._projects.searchContractorAction()"></ul>
				</td>
			</tr>
			<tr>
				<td  class="label notMandatory">&nbsp;</td>
				<td>
					Do you want to add new contractor? <a href="javascript:void(0);" onclick="projectObj._contractors.createForm({'openAs': 'popup', 'popupType' : '2'})">Click Here</a>.
				</td>
			</tr>

			<!-- Project Budget -->
			<tr>
				<td class="label notMandatory">Project Budget</td>
				<td>
					<input type="text" name="project_budget" id="project_budget" value="<?php echo $project_budget; ?>" >
				</td>
			</tr>

			<!-- Project Loan Amount -->
			<tr>
				<td class="label notMandatory">Loan Amount</td>
				<td>
					<input type="text" name="lend_amount" id="lend_amount" value="<?php echo $lend_amount; ?>">
				</td>
			</tr>

			<!-- Project Lender -->
			<tr>
				<td class="label notMandatory">Project Lender</td>
				<td>
					<input type="text" name="project_lender" id="project_lender" value="<?php echo $project_lender; ?>">
				</td>
			</tr>

				<?php
				if($userType == "admin") {
				?>
			<!-- Project Deductible -->
			<tr>
				<td class="label notMandatory">Deductible</td>
				<td>
					<input type="text" name="deductible" id="deductible" value="<?php echo $deductible; ?>">
				</td>
			</tr>
				<?php
				}
				?>

			<!-- Project Owner ID -->
			<tr>
				<td class="label notMandatory">Property Owner ID</td>
				<td>
					<input type="text" name="property_owner_id" id="property_owner_id" value="<?php echo $property_owner_id; ?>">
				</td>
			</tr>

			<!-- Project Customer Name Search and Adding -->
			<tr>
				<td class="label notMandatory">Customer Name</td>
				<td>
					<input type="text" name="searchCustomerName" id="searchCustomerName" value="" onkeyup="projectObj._projects.showCustomerListInDropDown()">
				</td>
			</tr>
			<tr>
			<td  class="label notMandatory">&nbsp;</td>
				<td class="customer-search-result">
					<ul id="customerNameList" class="connectedSortable"></ul>
				</td>
			</tr>
			<tr>
				<td  class="label notMandatory">&nbsp;</td>
				<td>
					Do you want to add new customer? <a href="javascript:void(0);" onclick="securityObj._users.createForm({'openAs': 'popup', 'popupType' : '2', 'belongsTo':'customer', requestFrom:'projects'})">Click Here</a>.
				</td>
			</tr>

			<!-- Project Adjuster Search and Adding -->
			<tr class="adjuster-search-selected">
				<td class="label"><?php echo $adjusterLable; ?></td>
				<td>
					<ul id="adjusterSearchSelected" class="connectedSortable" onclick="projectObj._projects.searchAdjusterAction()">
					</ul>
				</td>
			</tr>
			<tr>
				<td class="label notMandatory">Search Adjuster By Name or Company Name</td>
				<td>
					<input type="text" name="searchAdjusterName" id="searchAdjusterName" value="" list="adjusterDataList">
					<span class="fi-zoom-in size-21 searchIcon" onclick="projectObj._projects.getAdjusterListUsingNameCompany()"></span>
				</td>
			</tr>
			<tr>
				<td  class="label notMandatory">&nbsp;</td>
				<td class="adjuster-search-result">
					<ul id="adjusterSearchResult" class="connectedSortable" onclick="projectObj._projects.searchAdjusterAction()"></ul>
				</td>
			</tr>
			<tr>
				<td  class="label notMandatory">&nbsp;</td>
				<td>
					Do you want to add new adjuster? <a href="javascript:void(0);" onclick="securityObj._users.createForm({'openAs': 'popup', 'popupType' : '2', 'belongsTo':'adjuster', requestFrom:'projects'})">Click Here</a>.
				</td>
			</tr>

			<!-- Project Associate Claim Number -->
			<tr>
				<td class="label notMandatory">Associated Claim Number</td>
				<td>
					<input type="text" name="associated_claim_num" id="associated_claim_num" value="<?php echo $associated_claim_num; ?>">
				</td>
			</tr>
				
			<tr>
				<td colspan="2">
				<?php
				if($formType == "create") {
				?>
			
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_project_submit" onclick="projectObj._projects.createValidate()">Create Project</button>
						<button type="reset" id="resetButton" onclick="">Clear</button>
					</p>
				<?php
				} else {
				?>
				<p class="button-panel">
					<button type="button" id="update_project_submit" onclick="projectObj._projects.updateValidate()">Update Project</button>
					<button type="button" onclick="projectObj._projects.closeDialog()">Cancel</button>
				</p>
				<?php
				}
				?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>
<!-- Add Function Ends -->
