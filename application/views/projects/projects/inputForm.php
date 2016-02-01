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
<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('projects->headers->create'); ?></h2>
</div>
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
				<td class="label"><?php echo $this->lang->line_arr('projects->input_form->projectTitle'); ?></td>
				<td>
					<input type="text" name="projectTitle" id="projectTitle" value="<?php echo $projectTitle; ?>" placeholder="<?php echo $this->lang->line_arr('projects->input_form->projectTitle'); ?>" required>
				</td>
			</tr>

			<!-- Project Description -->
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('projects->input_form->description'); ?>:</td>
				<td>
					<textarea type="text" name="description" id="description" rows="10" cols="70" placeholder="<?php echo $this->lang->line_arr('projects->input_form->description'); ?>" required><?php echo $description; ?></textarea>
				</td>
			</tr>

			<!-- Project Type -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->project_type'); ?></td>
				<td>
					<select name="project_type" id="project_type">
					<option value=""><?php echo $this->lang->line_arr('projects->input_form->project_type_option_0'); ?></option>
					<option value="commercial">Commercial</option>
					<option value="residential">Residential</option>
					</select>
				</td>
			</tr>

			<!-- Project Status -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->project_status'); ?></td>
				<td>
					<select name="project_status" id="project_status">
						<option value=""><?php echo $this->lang->line_arr('projects->input_form->project_status_option_0'); ?></option>
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
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->start_date'); ?></td>
				<td>
					<input type="text" name="start_date" id="start_date" placeholder="<?php echo $this->lang->line_arr('projects->input_form->start_date_ph'); ?>" value="<?php echo $start_date; ?>">
				</td>
			</tr>

			<!-- Project End Date -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->end_date'); ?></td>
				<td>
					<input type="text" name="end_date" id="end_date" placeholder="<?php echo $this->lang->line_arr('projects->input_form->end_date_ph'); ?>" value="<?php echo $end_date; ?>">
				</td>
			</tr>

			<!-- Project Address -->
			<?php
				echo $addressFile;
			?>

			<?php
			if(in_array('view', $contractorPermission['operation'])) {
			?>
				<!-- Project Contractor Search and Adding -->
				<!-- List of added contractor from the serach result-->
				<tr class="contractor-search-selected">
					<td class="label"><?php echo $contractorLable; ?></td>
					<td>
						<ul id="contractorSearchSelected" class="connectedSortable" onclick="_projects.searchContractorAction()">
						</ul>
					</td>
				</tr>
				<tr>
					<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->contractorZipCode'); ?></td>
					<td>
						<input type="text" name="contractorZipCode" id="contractorZipCode" value="" Placeholder="<?php echo $this->lang->line_arr('projects->input_form->contractorZipCode_ph'); ?>">
						<span class="fi-zoom-in size-21 searchIcon" onclick="_projects.getContractorListUsingServiceZip('')"></span>
					</td>
				</tr>
				<tr class="contractor-search-result">
					<td class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="contractorSearchResult" class="connectedSortable dropdown" onclick="_projects.searchContractorAction()"></ul>
					</td>
				</tr>

				<?php 
				if(in_array('create', $contractorPermission['operation'])) {
				?>
					<tr>
						<td  class="label notMandatory">&nbsp;</td>
						<td>
							<?php
								$start = "<a href=\"javascript:void(0);\" onclick=\"_contractors.createForm(event, {'openAs': 'popup', 'popupType' : '2'})\">";
								$end = "</a>";
								echo str_replace(["##replace1##", "##replace2##"], [$start, $end], $this->lang->line_arr('projects->buttons_links->new_contractor'));
							?>
						</td>
					</tr>
				<?php
				}
				?>
			<?php
			}
			?>

			<!-- Project Budget -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->project_budget'); ?></td>
				<td>
					<input type="text" name="project_budget" id="project_budget" placeholder="<?php echo $this->lang->line_arr('projects->input_form->project_budget_ph'); ?>" value="<?php echo $project_budget; ?>" >
				</td>
			</tr>

			<!-- Project Loan Amount -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->lend_amount'); ?></td>
				<td>
					<input type="text" name="lend_amount" id="lend_amount" placeholder="<?php echo $this->lang->line_arr('projects->input_form->lend_amount_ph'); ?>" value="<?php echo $lend_amount; ?>">
				</td>
			</tr>

			<!-- Project Lender -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->project_lender'); ?></td>
				<td>
					<input type="text" name="project_lender" id="project_lender" placeholder="<?php echo $this->lang->line_arr('projects->input_form->project_lender_ph'); ?>" value="<?php echo $project_lender; ?>">
				</td>
			</tr>

				<?php
				if($role_disp_name == "admin") {
				?>
			<!-- Project Deductible -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->deductible'); ?></td>
				<td>
					<input type="text" name="deductible" id="deductible" placeholder="<?php echo $this->lang->line_arr('projects->input_form->deductible_ph'); ?>" value="<?php echo $deductible; ?>">
				</td>
			</tr>
				<?php
				}
				?>

			<!-- Project Owner ID -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->property_owner_id'); ?></td>
				<td>
					<input type="text" name="property_owner_id" id="property_owner_id" placeholder="<?php echo $this->lang->line_arr('projects->input_form->property_owner_id_ph'); ?>" value="<?php echo $property_owner_id; ?>">
				</td>
			</tr>

			<?php
			if(in_array('view', $customerPermission['operation'])) {
			?>
				<!-- Project Customer Name Search and Adding -->
				<tr>
					<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->searchCustomerName'); ?></td>
					<td>
						<input type="text" name="searchCustomerName" id="searchCustomerName" value="" 
							placeholder="<?php echo $this->lang->line_arr('projects->input_form->searchCustomerName_ph'); ?>" 
							onkeyup="_projects.showCustomerListInDropDown()">
						<span class="fi-zoom-in size-21 searchIcon" onclick="_projects.showCustomerListInDropDown()"></span>
					</td>
				</tr>
				<tr class="customer-search-result">
				<td  class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="customerNameList" class="connectedSortable dropdown"></ul>
					</td>
				</tr>

				<?php
				if(in_array('create', $customerPermission['operation']) && count($customerPermission['data_filter']) > 1) {
				?>
				<tr>
					<td  class="label notMandatory">&nbsp;</td>
					<td>
						<?php
							$start = "<a href=\"javascript:void(0);\" onclick=\"_users.createForm({'openAs': 'popup', 'popupType' : '2', 'belongsTo':'customer', requestFrom:'projects'})\">";
							$end = "</a>";
							echo str_replace(["##replace1##", "##replace2##"], [$start, $end], $this->lang->line_arr('projects->buttons_links->new_customer'));
						?>
					</td>
				</tr>
				<?php
				}
			?>
			<?php
			}
			?>

			<?php
			if(in_array('view', $adjusterPermission['operation'])) {
			?>
				<!-- Project Adjuster Search and Adding -->
				<tr class="adjuster-search-selected">
					<td class="label"><?php echo $adjusterLable; ?></td>
					<td>
						<ul id="adjusterSearchSelected" class="connectedSortable" onclick="_projects.searchAdjusterAction()">
						</ul>
					</td>
				</tr>
				<tr>
					<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->searchAdjusterName'); ?></td>
					<td>
						<input type="text" name="searchAdjusterName" id="searchAdjusterName" placeholder="<?php echo $this->lang->line_arr('projects->input_form->searchAdjusterName_ph'); ?>" value="" list="adjusterDataList">
						<span class="fi-zoom-in size-21 searchIcon" onclick="_projects.getAdjusterListUsingNameCompany()"></span>
					</td>
				</tr>
				<tr class="adjuster-search-result">
					<td  class="label notMandatory">&nbsp;</td>
					<td>
						<ul id="adjusterSearchResult" class="connectedSortable dropdown" onclick="_projects.searchAdjusterAction()"></ul>
					</td>
				</tr>
				<?php 
				if(in_array('create', $adjusterPermission['operation'])) {
				?>
				<tr>
					<td  class="label notMandatory">&nbsp;</td>
					<td>
						<?php
							$start = "<a href=\"javascript:void(0);\" onclick=\"_users.createForm({'openAs': 'popup', 'popupType' : '2', 'belongsTo':'adjuster', requestFrom:'projects'})\">";
							$end = "</a>";
							echo str_replace(["##replace1##", "##replace2##"], [$start, $end], $this->lang->line_arr('projects->buttons_links->new_adjuster'));
						?>
					</td>
				</tr>
				<?php
				}
				?>
			<?php
			}
			?>

			<!-- Project Associate Claim Number -->
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('projects->input_form->associated_claim_num'); ?></td>
				<td>
					<input type="text" name="associated_claim_num" id="associated_claim_num" placeholder="<?php echo $this->lang->line_arr('projects->input_form->associated_claim_num_ph'); ?>" value="<?php echo $associated_claim_num; ?>">
				</td>
			</tr>
				
			<tr>
				<td colspan="2">
				<?php
				if($formType == "create") {
				?>
			
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_project_submit" onclick="_projects.createValidate()"><?php echo $this->lang->line_arr('projects->buttons_links->create'); ?></button>
						<button type="reset" id="resetButton" onclick=""><?php echo $this->lang->line_arr('buttons->clear'); ?></button>
					</p>
				<?php
				} else {
				?>
				<p class="button-panel">
					<button type="button" id="update_project_submit" onclick="_projects.updateValidate()"><?php echo $this->lang->line_arr('projects->buttons_links->update'); ?></button>
					<button type="button" onclick="_projects.closeDialog()"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
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
