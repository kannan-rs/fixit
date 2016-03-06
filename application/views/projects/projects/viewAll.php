<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('projects->headers->view_all'); ?></h2>
	<div class="projects internal-tab-as-links" onclick="_projects.showProjectsList(event)">
		<?php 
		if(isset($projects) && count($projects)) {
		?>
		<a href="javascript:void(0);" data-option="open" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->open_title'); ?>">
				<?php echo $this->lang->line_arr('projects->buttons_links->open'); ?>
		</a>
		<a href="javascript:void(0);" data-option="completed" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->completed_title'); ?>">
				<?php echo $this->lang->line_arr('projects->buttons_links->completed'); ?>
		</a>

		<?php if($role_disp_name == ROLE_ADMIN) { ?>
			
			<a href="javascript:void(0);" data-option="deleted" 
				title="<?php echo $this->lang->line_arr('projects->buttons_links->deleted_title'); ?>">
					<?php echo $this->lang->line_arr('projects->buttons_links->deleted'); ?>
			</a>

		<?php } ?>
		
		<?php if(in_array(OPERATION_VIEW, $issuesPermission['operation'])) { ?>
		<a href="javascript:void(0);" data-option="issues" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->issues_title'); ?>">
				<?php echo $this->lang->line_arr('projects->buttons_links->issues'); ?>
		</a>
		<?php } ?>
		<a href="javascript:void(0);" data-option="all"
			title="<?php echo $this->lang->line_arr('projects->buttons_links->all_title'); ?>">
				<?php echo $this->lang->line_arr('projects->buttons_links->all'); ?>
		</a>
		<?php
		}
		?>
	</div>
</div>

<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="projects-table-list">
	
	<?php
		if(isset($projects) && count($projects) > 0) {
	?>
			<tr class='heading'>
			<td class='cell'><?php echo $this->lang->line_arr('projects->summary_table->project_name'); ?></td>
			<td class='cell percentage'><?php echo $this->lang->line_arr('projects->summary_table->complete'); ?></td>
			<td class='cell date'><?php echo $this->lang->line_arr('projects->summary_table->start_date'); ?></td>
			<td class='cell date'><?php echo $this->lang->line_arr('projects->summary_table->end_date'); ?></td>
			<td class='cell table-action'></td>
			</tr>
	<?php
		}

		if(isset($projects)) {
			for($i = 0; $i < count($projects); $i++) {
				/*$deleteText = "Delete";
				$deleteFn = $deleteText ? "_projects.deleteRecord(".$projects[$i]->proj_id.")" : "";*/

				$cssStatus = $projects[$i]->project_status == "completed" || $projects[$i]->percentage == "100" ? "completed" : "open";
				$cssStatus = $projects[$i]->is_deleted ? "deleted" : $cssStatus;
		?>
				<tr class='row viewAll <?php echo $cssStatus; ?> <?php echo $issueCount ? "issues" : ""; ?>'>
					<td class='cell'>
						<a href="javascript:void(0);" onclick="_projects.viewOne('<?php echo $projects[$i]->proj_id; ?>')">
							<?php echo $projects[$i]->project_name; ?>
						</a>
					</td>
					<td class="cell percentage"><?php echo $projects[$i]->percentage; ?>%</td>
					<td class="cell date"><?php echo $projects[$i]->start_date; ?></td>
					<td class="cell date"><?php echo $projects[$i]->end_date; ?></td>
					<td class='cell table-action'>
					<span>
					<?php 
					if(in_array(OPERATION_VIEW, $issuesPermission['operation'])) {
						$issueCount 	= $projects[$i]->issueCount;
						$issueFnOptions = "{'projectId' :".$projects[$i]->proj_id.", 'openAs' : 'popup', 'popupType' : '' }";
						$issueFn = "_issues.viewAll(".$issueFnOptions.")";

						if($issueCount) { ?>
							<a class="step fi-alert size-21 red" href="javascript:void(0);" 
								onclick="<?php echo $issueFn; ?>" title="<?php echo $this->lang->line_arr('projects->buttons_links->project_issue_title'); ?>">
								<span class="size-9"><?php echo $issueCount; ?></span></a>
					<?php 
						}
					} ?>
					</span>
					</td>
				</tr>
		<?php
			}
		} else {
		?>
		<div> No projects found for you </div>
		<?php
		}
	?>
	</table>
</div>