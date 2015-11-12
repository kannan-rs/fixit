<?php
	$editFn 	= "projectObj._projects.editProject('".$projectId."')";
	$deleteFn 	= "projectObj._projects.deleteRecord('".$projectId."')";
	
	$issueCount 	= $project->issueCount ? $project->issueCount : "";

	$issueFnOptions = "{'projectId' :".$projectId.", 'openAs' : 'popup', 'popupType' : '' }";
	$issueFn 		= "projectObj._issues.viewAll(".$issueFnOptions.")";

	$exportFn 		= "projectObj._projects.exportCSV('".$projectId."')";
?>
<div class="header-options">
	<h2><?php echo $this->lang->line_arr('projects->headers->view_one'); ?></h2>
	<span class="options-icon">
		<span>
			<a class="step fi-alert size-21 <?php echo $issueCount ? "red" : ""; ?>" href="javascript:void(0);" onclick="<?php echo $issueFn; ?>" title="<?php echo $this->lang->line_arr('projects->buttons_links->project_issue_title'); ?>">
				<span class="size-9"><?php echo $issueCount; ?></span>
			</a>
		</span>
		<span><a  class="step fi-page-edit size-21" href="javascript:void(0);" onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('projects->buttons_links->edit_project'); ?>"></a></span>
		<span><a  class="step fi-page-csv size-21" href="javascript:void(0);" onclick="<?php echo $exportFn; ?>" title="<?php echo $this->lang->line_arr('projects->buttons_links->export_csv'); ?>"></a></span>
		<span><a  class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('projects->buttons_links->delete_project'); ?>"></a></span>
	</span>
</div>
<div>
	<!-- List all the Functions from database -->
	<input type="hidden" id="contractorIdDb" value="<?php echo $project->contractor_id;?>">
	<input type="hidden" id="adjusterIdDb" value="<?php echo $project->adjuster_id;?>">

	<!-- Project Title -->
	<table cellspacing="0" class="viewOne projectViewOne">
		<tr>
			<td class='cell label project-title fl'><?php echo $this->lang->line_arr('projects->details_view->project_title'); ?>:</td>
			<td class='cell fl' ><?php echo $project->project_name; ?></td>
		</tr>
	</table>

	<div id="accordion" class="accordion">
		<!-- Project Description -->
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->project_description'); ?></span></h3>
		<table cellspacing="0" class="viewOne">
			<tr>
				<td class='cell'><?php echo $project->project_descr; ?></td>
			</tr>
		</table>

		<!-- Project start and end dates -->
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->project_date'); ?></span></h3>
		<div>
			<table cellspacing="0" class="viewOne">
				<tr>
					<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view->project_start_date'); ?></td>
					<td class='cell' ><?php echo $project->start_date; ?></td>
					<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view->project_end_date'); ?></td>
					<td class='cell' ><?php echo $project->end_date; ?></td>
				</tr>
			</table>
		</div>

		<!-- Project Address -->
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->project_address'); ?></span></h3>
		<div class="form">
			<?php
				echo $addressFile;
			?>
		</div>

		<!-- Project Budget -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->budget'); ?></span>
			<a class="step fi-page-edit size-21 accordion-icon icon-right" href="javascript:void(0);" onclick="projectObj._remainingbudget.getListWithForm({'openAs': 'popup', 'popupType' : '2'})" title="<?php echo $this->lang->line_arr('projects->buttons_links->update_budget_title'); ?>"></a>
		</h3>
		<div id= "viewOneProjectBudget">
		<?php echo $projectBudgetFile; ?>
		</div>
		
		<!-- Project Customer Details -->
		<?php echo $customerFile; ?>
		
		<!-- Project Contractor Details -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->contractor_details'); ?></span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._contractors.createForm({'projectId': '<?php echo $projectId; ?>', 'popup': true, 'openAs': 'popup'});" title="<?php echo $this->lang->line_arr('projects->buttons_links->add_contractor_title'); ?>"></a>
		</h3>
		<div>
			<div id="contractor_accordion" class="accordion">
				<?php
				if(count($contractors)) {
					for($i = 0; $i < count($contractors); $i++) {
				?>
				<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->contractor_name'); ?>: <?php echo $contractors[$i]->name; ?></span></h3>
				<div>
					<table cellspacing="0" class="viewOne projectViewOne">
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_contractor->name'); ?></td>
							<td class='cell' ><?php echo $contractors[$i]->name; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_contractor->company'); ?></td>
							<td class='cell' ><?php echo $contractors[$i]->company; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_contractor->prefered_contact_mode'); ?></td>
							<td class='cell' ><?php echo $contractors[$i]->prefer; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_contractor->contact_office_email'); ?></td>
							<td class='cell' ><?php echo $contractors[$i]->office_email; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_contractor->contact_office_number'); ?></td>
							<td class='cell' ><?php echo $contractors[$i]->office_ph; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_contractor->contact_mobile_number'); ?></td>
							<td class='cell' ><?php echo $contractors[$i]->mobile_ph; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_contractor->address'); ?></td>
							<td class='cell' ><?php echo $contractors[$i]->address1.",<br/>".$contractors[$i]->address2.",<br/>".$contractors[$i]->city.",<br/>".$contractors[$i]->state.",<br/>".$contractors[$i]->country.",<br/>".$contractors[$i]->pin_code; ?></td>
						</tr>
					</table>
				</div>
				<?php
					}
				} else {
				?>
					<span><?php echo $this->lang->line_arr('projects->details_view_contractor->yet_to_assign'); ?></span>
				<?php
				}
				?>
			</div>
		</div>

		<!-- Project Adjuster Details -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->partners_details'); ?></span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._partners.createForm({'projectId': '<?php echo $projectId; ?>', 'popup': true, 'openAs': 'popup'});" title="<?php echo $this->lang->line_arr('projects->buttons_links->add_project_title'); ?>"></a>
		</h3>
		<div>
			<div id="partner_accordion" class="accordion">
				<?php
				if(count($partners)) {
					for($i = 0; $i < count($partners); $i++) {
				?>
				<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->partner_name'); ?>: <?php echo $partners[$i]->name; ?></span></h3>
				<div>
					<table cellspacing="0" class="viewOne projectViewOne">
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->name'); ?></td>
							<td class='cell' ><?php echo $partners[$i]->name; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->company'); ?></td>
							<td class='cell' ><?php echo $partners[$i]->company_name; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->prefered_contact_mode'); ?></td>
							<td class='cell' ><?php echo $partners[$i]->contact_pref; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->contact_office_email'); ?></td>
							<td class='cell' ><?php echo $partners[$i]->work_email_id; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->contact_office_number'); ?></td>
							<td class='cell' ><?php echo $partners[$i]->work_phone; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->contact_personal_email'); ?></td>
							<td class='cell' ><?php echo $partners[$i]->personal_email_id; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->contact_mobile_number'); ?></td>
							<td class='cell' ><?php echo $partners[$i]->mobile_no; ?></td>
						</tr>
						<tr>
							<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->address'); ?></td>
							<td class='cell' ><?php echo $partners[$i]->address1.",<br/>".$partners[$i]->address2.",<br/>".$partners[$i]->city.",<br/>".$partners[$i]->state.",<br/>".$partners[$i]->country.",<br/>".$partners[$i]->zip_code; ?></td>
						</tr>
					</table>
				</div>
				<?php
					}
				} else {
				?>
					<span><?php echo $this->lang->line_arr('projects->details_view_partner->yet_to_assign'); ?></span>
				<?php
				}
				?>
			</div>
		</div>

		<!-- Project Task List table -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->tasks_list'); ?><span id="taskCountDisplay"></span></span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._projects.addTask();" title="<?php echo $this->lang->line_arr('projects->buttons_links->add_task_title'); ?>"></a>
		</h3>
		<div class="project_section" id="task_content"></div>

		<!-- Project Notes List table -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->notes'); ?><span id="notesCountForProjectDisplay"></span></span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._projects.addProjectNote();" title="<?php echo $this->lang->line_arr('projects->buttons_links->add_note_title'); ?>"></a>
		</h3>
		<div class="project_section" id="note_content"></div>

		<!-- Project Docs List table -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->documents'); ?><span id="docsCountDisplay"></span></span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);"  onclick="projectObj._projects.addDocumentForm();" title="<?php echo $this->lang->line_arr('projects->buttons_links->add_docs_title'); ?>"></a>
		</h3>
		<div class="project_section" id="attachment_content"></div>

		<!-- Project Insurense Details -->
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->insurance_details'); ?></span></h3>
		<div>
			<table cellspacing="0" class="viewOne projectViewOne">
				<tr>
					<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_insurance->provider'); ?></td>
					<td class='cell' ><?php echo $this->lang->line_arr('projects->details_view_insurance->dummy_text'); ?></td>
				</tr>
				<tr>
					<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_insurance->provider_address'); ?></td>
					<td class='cell' ><?php echo $this->lang->line_arr('projects->details_view_insurance->dummy_text'); ?></td>
				</tr>
				<tr>
					<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_insurance->provider_phone'); ?></td>
					<td class='cell' ><?php echo $this->lang->line_arr('projects->details_view_insurance->dummy_text'); ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>