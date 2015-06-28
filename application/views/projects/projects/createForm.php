<!-- Add Function Start -->
<h2>Create Project</h2>
<form id="create_project_form" name="create_project_form" class="inputForm">
	<div class='form'>
		<div class="label">Project Title:</div>
		<div>
			<input type="text" name="projectTitle" id="projectTitle" value="" required>
		</div>
		<div class="label">Description:</div>
		<div>
			<textarea type="text" name="description" id="description" rows="10" cols="70" required></textarea>
		</div>
		<div class="label notMandatory">Associated Claim Number</div>
		<div>
			<input type="text" name="associated_claim_num" id="associated_claim_num" value="">
		</div>
		<div class="label">Project Type</div>
		<div>
			<select name="project_type" id="project_type">
			<option value="">--Select Project Type--</option>
			<option value="commercial">Commercial</option>
			<option value="personal">Personal</option>
			</select>
		</div>
		<div class="label">Project Status</div>
		<div>
			<select name="project_status" id="project_status">
				<option value="">--Select Project Status--</option>
				<option value="project created">Project Created</option>
				<option value="not assigned">Not Assigned</option>
				<option value="assigned to contractor">Assigned to Contractor</option>
				<option value="work in progress">Work in Progress</option>
				<option value="completed">Completed</option>
			</select>
		</div>
		<div class="label">Property Owner ID</div>
		<div>
			<input type="text" name="property_owner_id" id="property_owner_id" value="">
		</div>
		<div class="contractor-search-selected">
			<div class="label">Drop Contractor from Search result</div>
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
			Do you want to add new contractor? <a href="javascript:void(0);" onclick="projectObj._contractors.createForm('popup')">Click Here</a>.
		</div>
		<div class="label">Adjuster Name</div>
		<div>
			<input type="text" name="searchAdjusterName" id="searchAdjusterName" value="" list="adjusterDataList" onkeyup="projectObj._projects.showAdjusterListInDropDown()">
			<input type="hidden" value="" name="adjuster_id" id="adjuster_id">
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
			<input type="hidden" value="" name="customer_id" id="customer_id">
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
		<div class="label">Project Budget</div>
		<div>
			<input type="text" name="project_budget" id="project_budget" value="" >
		</div>
		<div class="label">Paid from Budget</div>
		<div>
			<input type="text" name="paid_from_budget" id="paid_from_budget" value="" >
		</div>
		<!-- <div class="label">Remaining Bbudget</div>
		<div>
			<input type="text" name="remaining_budget" id="remaining_budget" value="">
		</div> -->
		<?php
		if($userType == "admin") {
		?>
		<div class="label">Deductible</div>
		<div>
			<input type="text" name="deductible" id="deductible" value="">
		</div>
		<!-- <div class="label">Referral Fee</div>
		<div>
			<input type="text" name="referral_fee" id="referral_fee" value="">
		</div> -->
		<?php
		}
		?>
		<div class="label">Project Lender</div>
		<div>
			<input type="text" name="project_lender" id="project_lender" value="">
		</div>
		<div class="label">Lend Amount</div>
		<div>
			<input type="text" name="lend_amount" id="lend_amount" value="" required>
		</div>
		<?php
			//echo $addressFile;
		?>
		<p class="button-panel">
			<button type="button" id="create_project_submit" onclick="projectObj._projects.createValidate()">Create Project</button>
		</p>
	</div>
</form>
<!-- Add Function Ends -->
