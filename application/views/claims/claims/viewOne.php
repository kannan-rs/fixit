<?php
	$claim		= $claims[0];
?>

<div id="claim_tabs" class="page-tabs">
	<ul>
		<li><a href="#tabs_claim_details"><?php echo $this->lang->line_arr('claim->headers->view_one'); ?></a></li>
		<li><a href="#tabs_claim_subrogation" 
			onclick="_claim_subrogation.viewAll('<?php echo $claim_id;?>')">
				<?php echo $this->lang->line_arr('claim->headers->subrogation'); ?>
			</a>
		</li>
	</ul>
	<div id="tabs_claim_details">
		<div class="header-options">
			<span class="options-icon">
				<?php 
				if(in_array('update', $claimPermission['operation'])) {
					$editFn 		= "_claims.editForm({openAs : 'popup'})";
				?>
					<span>
						<a class="step fi-page-edit size-21" href="javascript:void(0);" 
							onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('claim->details_view->edit_title'); ?>">
						</a>
					</span>
				<?php
				}
				if(in_array('delete', $claimPermission['operation'])) {
					$deleteFn 		= "_claims.deleteRecord('".$claim_id."')";
				?>
					<span>
						<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" 
							onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('claim->details_view->delete_title'); ?>">
						</a>
					</span>
				<?php
				}
				?>	
			</span>
		</div>
		<div class="clear"></div>
		<div id="accordion" class="accordion">
			<!-- Project Description -->
			<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->claim_details'); ?></span></h3>
			<div>
				<table cellspacing="0" class="viewOne">
					<tr>
						<td class='cell label'><?php echo $this->lang->line_arr('claim->details_view->claim_number'); ?></td>
						<td class='cell'><?php echo $claim->claim_number; ?></td>
					</tr>
					<tr>
						<td class='cell label'><?php echo $this->lang->line_arr('claim->details_view->customer_name'); ?></td>
						<td class='cell'><?php echo $claim->customer_name; ?></td>
					</tr>
					<tr>
						<td class='cell label'><?php echo $this->lang->line_arr('claim->details_view->contact_number'); ?></td>
						<td class='cell'><?php echo $claim->customer_contact_no; ?></td>
					</tr>
					<tr>
						<td class='cell label'><?php echo $this->lang->line_arr('claim->details_view->contact_email'); ?></td>
						<td class='cell'><?php echo $claim->customer_email_id; ?></td>
					</tr>
					<tr>
						<td colspan="2">Address For Communication</td>
					</tr>
					<?php echo $customer_address_file; ?>
				</table>
			</div>

			<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->claim_description'); ?></span></h3>
			<div>
				<table cellspacing="0" class="viewOne">
					<tr>
						<td class='cell'><?php echo $claim->claim_description; ?></td>
					</tr>
				</table>
			</div>

			<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->claim_address'); ?></span></h3>
			<div class="form">
				<?php echo $property_address_file; ?>
			</div>

			<h3>
				<span class="inner_accordion">
					<?php echo $this->lang->line_arr('claim->headers->claim_notes'); ?>
					<span id="notesCountForClaimDisplay"></span>
				</span>
				<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);" 
					onclick="_claim_notes.createForm(event, {'openAs': 'popup', 'popupType' : ''})" 
					title="<?php echo $this->lang->line_arr('projects->buttons_links->add_notes_title'); ?>">
				</a>
			</h3>
			<div class="project_section" id="note_content"></div>

			<h3>
				<span class="inner_accordion">
					<?php echo $this->lang->line_arr('claim->headers->claim_dairy_update'); ?>
					<span id="dairyUpdatesCountForClaimDisplay"></span>
				</span>
				<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);" 
					onclick="_claim_dairy_updates.createForm(event, {'openAs': 'popup', 'popupType' : ''})" 
					title="<?php echo $this->lang->line_arr('claim->buttons_links->add_dairy_updates_title'); ?>">
				</a>
			</h3>
			<div class="project_section" id="dairy_update_content"></div>

			<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->claim_payment'); ?></span></h3>
			<div class="form">
				<table>
					<tbody>
					<?php
					if(isset($budget_details) && count($budget_details)) {
						$claim_total_budget = 0;
						$claim_remediation_payment = 0;
						for($i = 0, $count = count($budget_details); $i < $count; $i++) {
							$claim_total_budget 		+= (float)$budget_details[$i]->project_budget;
							$claim_remediation_payment	+= (float)$budget_details[$i]->remediation_payment;
					?>
							<tr>
								<td>
									<table class="viewOne">
										<tr>
											<td class="cell label">
												<?php echo $this->lang->line_arr('claim->details_view->budget_project_name');?>
												</td>
											<td cell="cell" style="width:99%;"><?php echo $budget_details[$i]->project_name;?></td>
										</tr>
										<tr>
											<td class="cell label">
												<?php echo $this->lang->line_arr('claim->details_view->budget_starting_budget');?>
												</td>
											<td cell="cell" style="width:99%;"><?php echo number_format((float)$budget_details[$i]->project_budget, 2, ".", ",");?></td>
										</tr>
										<tr>
											<td class="cell label">
												<?php echo $this->lang->line_arr('claim->details_view->budget_remediation_budget');?>
												</td>
											<td cell="cell" style="width:99%;"><?php echo number_format((float)$budget_details[$i]->remediation_payment, 2, ".", ",");?></td>
										</tr>
										<tr>
											<td class="cell label">
												<?php echo $this->lang->line_arr('claim->details_view->budget_remaining_budget');?>
												</td>
											<td cell="cell" style="width:99%;"><?php echo number_format((float)$budget_details[$i]->project_budget - (float)$budget_details[$i]->remediation_payment,2, ".", ",");?></td>
										</tr>
									</table>
								</td>
							</tr>
					<?php
						}
					?>
							<tr>
								<td>
									<table class="viewOne">
										<tr>
											<td class="cell label"><b>
												<?php echo $this->lang->line_arr('claim->details_view->budget_total_claim_budget');?>
												</b></td>
											<td cell="cell" style="width:99%;"><b><?php echo number_format((float)$claim_total_budget, 2, ".", ",");?></b></td>
										</tr>
										<tr>
											<td class="cell label"><b>
												<?php echo $this->lang->line_arr('claim->details_view->budget_total_remediation_budget');?>
												</b></td>
											<td cell="cell" style="width:99%;"><b><?php echo number_format((float)$claim_remediation_payment, 2, ".", ",");?></b></td>
										</tr>
										<tr>
											<td class="cell label"><b>
												<?php echo $this->lang->line_arr('claim->details_view->budget_total_remaining_budget');?>
												</b></td>
											<td cell="cell" style="width:99%;"><b><?php echo number_format((float)$claim_total_budget - (float)$claim_remediation_payment,2, ".", ",");?></b></td>
										</tr>
									</table>
								</td>
							</tr>
					<?php
					} else {
					?>
					<tr>
						<td><?php echo $this->lang->line_arr('claim->details_view->no_project_text'); ?></td>
					</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>

			<h3>
				<span class="inner_accordion">
					<?php echo $this->lang->line_arr('claim->headers->claim_documents'); ?>
					<span id="claimDocsCountDisplay"></span>
				</span>
				<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);" 
					onclick="_claim_docs.addDocumentForm(event)" 
					title="<?php echo $this->lang->line_arr('claim->buttons_links->add_docs_title'); ?>">
				</a>
			</h3>
			<div class="project_section" id="attachment_content"></div>

			<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->claim_history'); ?></span></h3>
			<div class="form">
				-- To Be Implemented Later --
			</div>
		</div>
	</div>
	<div id="tabs_claim_subrogation">
	</div>
</div>