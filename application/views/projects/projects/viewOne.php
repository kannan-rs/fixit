<?php
	if(in_array(OPERATION_UPDATE, $projectPermission['operation'])) {
		$editFn 	= "_projects.editProject('".$projectId."')";
	}
	if(in_array(OPERATION_DELETE, $projectPermission['operation'])) {
		$deleteFn 	= "_projects.deleteRecord('".$projectId."')";
	}
	
	if(in_array(OPERATION_VIEW, $issuesPermission['operation'])) {
		$issueCount 	= $project->issueCount ? $project->issueCount : "";

		$issueFnOptions = "{'projectId' :".$projectId.", 'openAs' : 'popup', 'popupType' : '' }";
		$issueFn 		= "_issues.viewAll(".$issueFnOptions.")";
	}

	if(in_array(OPERATION_EXPORT, $issuesPermission['operation'])) {
		$exportFn 		= "_projects.exportCSV('".$projectId."')";
	}
?>
<div class="header-options">
	<h2 class="ui-accordion-header"><?php echo $this->lang->line_arr('projects->headers->view_one'); ?></h2>
	<span class="options-icon left-icon-list">
		<span class="ui-accordion-header-icon ui-icon ui-icon-plus expand-all" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->expand_all'); ?>" 
			onclick="_utils.viewOnlyExpandAll('accordion')"></span>
		<span class="ui-accordion-header-icon ui-icon ui-icon-minus collapse-all" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->collapse_all'); ?>" 
			onclick="_utils.viewOnlyCollapseAll('accordion')"></span>
	</span>
	<span class="options-icon">
		
		<?php
		if(in_array("service provider users", $projectPermission['data_filter']) && $role_disp_name == ROLE_SERVICE_PROVIDER_ADMIN) {
		?>
		<span>
			<a class="step fi-torsos-all size-21" href="javascript:void(0);" onclick="_projects.getContractorUserList()" 
				title="Assign Service Provider User to this project" >
			</a>
		</span>
		<?php	
		}

		if(in_array(OPERATION_VIEW, $issuesPermission['operation'])) { 
		?>
		<span>
			<a class="step fi-alert size-21 <?php echo $issueCount ? "red" : ""; ?>" 
				href="javascript:void(0);" onclick="<?php echo $issueFn; ?>" 
				title="<?php echo $this->lang->line_arr('projects->buttons_links->project_issue_title'); ?>">
				<span class="size-9"><?php echo $issueCount; ?></span>
			</a>
		</span>
		<?php
		}
		if(in_array(OPERATION_UPDATE, $projectPermission['operation'])) {
		?>
		<span>
			<a class="step fi-page-edit size-21" href="javascript:void(0);" 
				onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('projects->buttons_links->edit_project'); ?>">
			</a>
		</span>
		<?php
		} 
		if(in_array(OPERATION_EXPORT, $issuesPermission['operation'])) {
		?>
		<span>
			<a class="step fi-page-csv size-21" href="javascript:void(0);" 
				onclick="<?php echo $exportFn; ?>" title="<?php echo $this->lang->line_arr('projects->buttons_links->export_csv'); ?>">
			</a>
		</span>
		<?php 
		}
		if(in_array(OPERATION_DELETE, $projectPermission['operation'])) {
		?>
		<span>
			<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" 
				onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('projects->buttons_links->delete_project'); ?>">
			</a>
		</span>
		<?php
		}
		?>
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
		<div>
			<table cellspacing="0" class="viewOne">
				<tr>
					<td class='cell'><?php echo $project->project_descr; ?></td>
				</tr>
			</table>
		</div>

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
			<?php if(in_array(OPERATION_UPDATE, $budgetPermission['operation']) || 
				in_array(OPERATION_CREATE, $budgetPermission['operation'])) { ?>
			<a class="step fi-page-edit size-21 accordion-icon icon-right" href="javascript:void(0);" 
			onclick="_remainingbudget.getListWithForm(event, {'openAs': 'popup', 'popupType' : '2'})" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->update_budget_title'); ?>"></a>
			<?php } ?>
		</h3>
		<div id= "viewOneProjectBudget">
		<?php echo $projectBudgetFile; ?>
		</div>
		
		<!-- Project Customer Details -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->customer_details'); ?></span>
		</h3>
		<div>
		<?php echo $customerFile; ?>
		</div>
		<!-- Project Service Provider Details -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->contractor_details'); ?></span>
			<?php if(in_array(OPERATION_CREATE, $contractorPermission['operation'])) { ?>
			<a class="step fi-page-add size-21 accordion-icon icon-right" 
				href="javascript:void(0);"  
				onclick="_contractors.createForm(event, {'projectId': '<?php echo $projectId; ?>', 'popup': true, 'openAs': 'popup'});" 
				title="<?php echo $this->lang->line_arr('projects->buttons_links->add_contractor_title'); ?>"></a>
			<?php } ?>
		</h3>
		<div>
			<?php echo $contractorFile ?>
		</div>

		<!-- Project Adjuster Details -->
		<h3>
			<span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->partners_details'); ?></span>
			<?php if(in_array(OPERATION_CREATE, $adjusterPermission['operation'])) { ?>
			<a class="step fi-page-add size-21 accordion-icon icon-right" 
			href="javascript:void(0);"  
			onclick="_partners.createForm(event, {'projectId': '<?php echo $projectId; ?>', 'popup': true, 'openAs': 'popup'});" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->add_project_title'); ?>"></a>
			<?php } ?>
		</h3>
		<div>
			<?php echo $adjusterFile ?>
		</div>

		<!-- Project Task List table -->
		<h3>
			<span class="inner_accordion">
				<?php echo $this->lang->line_arr('projects->headers->tasks_list'); ?>
				<span id="taskCountDisplay"></span>
			</span>
			<?php if(in_array(OPERATION_CREATE, $tasksPermission['operation'])) { ?>
			<a class="step fi-page-add size-21 accordion-icon icon-right" 
			href="javascript:void(0);"  
			onclick="_projects.addTask(event);" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->add_task_title'); ?>"></a>
			<?php } ?>
		</h3>
		<div class="project_section" id="task_content"></div>

		<!-- Project Notes List table -->
		<h3>
			<span class="inner_accordion">
				<?php echo $this->lang->line_arr('projects->headers->notes'); ?>
				<span id="notesCountForProjectDisplay"></span>
			</span>
			<?php if(in_array(OPERATION_CREATE, $notesPermission['operation'])) { ?>
			<a class="step fi-page-add size-21 accordion-icon icon-right" 
				href="javascript:void(0);" 
				onclick="_projects.addProjectNote(event);" 
				title="<?php echo $this->lang->line_arr('projects->buttons_links->add_note_title'); ?>"></a>
			<?php } ?>
		</h3>
		<div class="project_section" id="note_content"></div>

		<!-- Project Docs List table -->
		<h3>
			<span class="inner_accordion">
				<?php echo $this->lang->line_arr('projects->headers->documents'); ?>
				<span id="docsCountDisplay"></span>
			</span>
			<?php if(in_array(OPERATION_CREATE, $docsPermission['operation'])) { ?>
			<a class="step fi-page-add size-21 accordion-icon icon-right" 
				href="javascript:void(0);"  
				onclick="_projects.addDocumentForm(event);" 
				title="<?php echo $this->lang->line_arr('projects->buttons_links->add_docs_title'); ?>"></a>
			<?php } ?>
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