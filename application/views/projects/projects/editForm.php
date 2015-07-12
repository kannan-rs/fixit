<?php
	$project = $projects[0];
?>
<form id="update_project_form" name="update_project_form" class="inputForm">
	<input type="hidden" id='project_sno' value="<?php echo $project->proj_id; ?>" />
	<div class='form'>
		<div class="label">Project Title:</div>
		<div>
			<input type="text" name="projectTitle" id="projectTitle" value="<?php echo $project->project_name; ?>" required>
		</div>
		
		<div class="label">Description</div>
		<div><textarea rows="6" cols="30" name="description" id="description" required><?php echo $project->project_descr; ?></textarea></div>
		
		<div class="label">Project Type</div>
		<div>
			<input type="hidden" id="db_project_type" name="db_project_type" value="<?php echo $project->project_type;?>">
			<select name="project_type" id="project_type">
				<option value="">--Select Project Type--</option>
				<option value="commercial">Commercial</option>
				<option value="personal">Personal</option>
			</select>
		</div>

		<div class="label">Project Status</div>
		<div>
			<input type="hidden" id="db_project_status" name="db_project_status" value="<?php echo $project->project_status;?>">
			<select name="project_status" id="project_status">
				<option value="">--Select Project Status--</option>
				<option value="project created">Project Created</option>
				<option value="not assigned">Not Assigned</option>
				<option value="assigned to contractor">Assigned to Contractor</option>
				<option value="work in progress">Work in Progress</option>
				<option value="completed">Completed</option>
			</select>
		</div>

		<div class="label">Project Start Date</div>
		<div>
			<input type="text" name="start_date" id="start_date" value="<?php echo $project->start_date; ?>">
		</div>
		<div class="label">Project End Date</div>
		<div>
			<input type="text" name="end_date" id="end_date" value="<?php echo $project->end_date; ?>">
		</div>

		<?php
			echo $addressFile;
		?>

		<div class="contractor-search-selected">
			<input type="hidden" id="contractorIdDb" value="<?php echo $project->contractor_id;?>">
			<div class="label">Contractor's List</div>
			<div>
				<ul id="contractorSearchSelected" class="connectedSortable" ondrop="projectObj._projects.drop(event)" ondragover="projectObj._projects.allowDrop(event)">
				</ul>
			</div>
			<div style="clear:both;"></div>
		</div>
		<div class="label">Search Contractor By Zip Code</div>
		<div>
			<input type="text" name="contractorZipCode" id="contractorZipCode" value="" Placeholder="Zip Code for search">
			<span class="fi-zoom-in size-21 searchIcon" onclick="projectObj._projects.getContractorListUsingZip()"></span>
		</div>
		<div class="contractor-search-result">
			<div>
				<ul id="contractorSearchResult" class="connectedSortable" ondrop="projectObj._projects.drop(event)" ondragover="projectObj._projects.allowDrop(event)"></ul>
			</div>
		</div>
		<div  class="label notMandatory">&nbsp;</div>
		<div>
			Do you want to add new contractor? <a href="javascript:void(0);" onclick="projectObj._contractors.createForm({'openAs': 'popup', 'popupType' : '2'})">Click Here</a>.
		</div>

		<div class="label">Project Budget</div>
		<div>
			<input type="text" name="project_budget" id="project_budget" value="<?php echo $project->project_budget;?>" >
		</div>

		<div class="label">Loan Amount</div>
		<div>
			<input type="text" name="lend_amount" id="lend_amount" value="<?php echo $project->lend_amount;?>">
		</div>

		<div class="label">Project Lender</div>
		<div>
			<input type="text" name="project_lender" id="project_lender" value="<?php echo $project->project_lender;?>">
		</div>

		<?php
		if($userType == "admin") {
		?>
		<div class="label">Deductible</div>
		<div>
			<input type="text" name="deductible" id="deductible" value="<?php echo $project->deductible;?>">
		</div>
		<?php
		}
		?>

		<div class="label">Property Owner ID</div>
		<div>
			<input type="text" name="property_owner_id" id="property_owner_id" value="<?php echo $project->property_owner_id;?>">
		</div>
		
		<div class="label">Adjuster Name</div>
		<div>
			<input type="text" name="searchAdjusterName" id="searchAdjusterName" value="" list="adjusterDataList" onkeyup="projectObj._projects.showAdjusterListInDropDown()">
			<input type="hidden" value="<?php echo $project->adjuster_id;?>" name="adjuster_id" id="adjuster_id">
		</div>
		<div class="adjuster-search-result">
			<div>
				<ul id="adjusterNameList" class="connectedSortable"></ul>
			</div>
		</div>
		<div  class="label notMandatory">&nbsp;</div>
		<div>
			Do you want to add new adjuster? <a href="javascript:void(0);" onclick="">Click Here</a>.
		</div>
		<div class="label">Customer Name</div>
		<div>
			<input type="text" name="searchCustomerName" id="searchCustomerName" value="" onkeyup="projectObj._projects.showCustomerListInDropDown()">
			<input type="hidden" value="<?php echo $project->customer_id;?>" name="customer_id" id="customer_id">
		</div>
		<div class="customer-search-result">
			<div>
				<ul id="customerNameList" class="connectedSortable"></ul>
			</div>
		</div>
		<div  class="label notMandatory">&nbsp;</div>
		<div>
			Do you want to add new customer? <a href="javascript:void(0);" onclick="">Click Here</a>.
		</div>

		<div class="label notMandatory">Associated Claim Number</div>
		<div>
			<input type="text" name="associated_claim_num" id="associated_claim_num" value="<?php echo $project->associated_claim_num;?>">
		</div>
		
		
		
		<p class="button-panel">
			<button type="button" id="update_project_submit" onclick="projectObj._projects.updateValidate()">Update Project</button>
			<button type="button" onclick="projectObj._projects.closeDialog()">Cancel</button>
		</p>
	</div>
</form>
