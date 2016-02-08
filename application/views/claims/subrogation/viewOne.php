<?php
	$suborgOne		= $subrogation[0];
?>
<div class="header-options">
	<h2 class=''>For Climant : <?php echo $suborgOne->climant_name; ?></h2>
	<span class="options-icon left-icon-list">
		<span class="ui-accordion-header-icon ui-icon ui-icon-plus expand-all" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->expand_all'); ?>" 
			onclick="_utils.viewOnlyExpandAll('subrogation_accordion')">
		</span>
		<span class="ui-accordion-header-icon ui-icon ui-icon-minus collapse-all" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->collapse_all'); ?>" 
			onclick="_utils.viewOnlyCollapseAll('subrogation_accordion')">
		</span>
	</span>
	<span class="options-icon">
		<?php 
		if(in_array(OPERATION_UPDATE, $claimPermission['operation'])) {
			$editFn 		= "_claim_subrogation.editForm({openAs : 'popup'})";
		?>
			<span>
				<a class="step fi-page-edit size-21" href="javascript:void(0);" 
					onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('claim_subrogation->details_view->edit_title'); ?>">
				</a>
			</span>
		<?php
		}
		if(in_array(OPERATION_DELETE, $claimPermission['operation'])) {
			$deleteFn 		= "_claim_subrogation.deleteRecord('".$claim_id."', '".$subrogation_id."')";
		?>
			<span>
				<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" 
					onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('claim_subrogation->details_view->delete_title'); ?>">
				</a>
			</span>
		<?php
		}
		?>	
	</span>
</div>
<div class="clear"></div>
<div id="subrogation_content">
	<div id="subrogation_accordion" class="accordion">
		<!-- Project Description -->
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_subrogation->headers->customer_details'); ?></span></h3>
		<div>
			<table cellspacing="0" class="viewOne">
				<tr>
					<td class='cell label'><?php echo $this->lang->line_arr('claim_subrogation->details_view->customer_name'); ?></td>
					<td class='cell'><?php echo $suborgOne->customer_name; ?></td>
				</tr>
				<?php echo $customer_communication_address_file; ?>
				<tr>
					<td class='cell label'><?php echo $this->lang->line_arr('claim_subrogation->details_view->status'); ?></td>
					<td class='cell'><?php echo $suborgOne->status; ?></td>
				</tr>
			</table>
		</div>
		
		<!-- Climant Address -->
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_subrogation->headers->climant_details'); ?></span></h3>
		<div>
			<table>
				<tr>
					<td class='cell label'><?php echo $this->lang->line_arr('claim_subrogation->details_view->climant_name'); ?></td>
					<td class='cell'><?php echo $suborgOne->climant_name; ?></td>
				</tr>
				<?php echo $claimant_address_file; ?>
			</table>
		</div>

		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_subrogation->headers->description'); ?></span></h3>
		<div>
			<table cellspacing="0" class="viewOne">
				<tr>
					<td class='cell'><?php echo $suborgOne->description; ?></td>
				</tr>
			</table>
		</div>

		<h3>
			<span class="inner_accordion">
				<?php echo $this->lang->line_arr('claim_subrogation->headers->subrogation_notes'); ?>
				<span id="subrogationNotesCountForClaimDisplay"></span>
			</span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);" 
				onclick="_claim_suorgation_notes.createForm(event, {'openAs': 'popup', 'popupType' : ''})" 
				title="<?php echo $this->lang->line_arr('projects->buttons_links->add_notes_title'); ?>">
			</a>
		</h3>
		<div class="project_section" id="subrogation_note_content"></div>

		<h3>
			<span class="inner_accordion">
				<?php echo $this->lang->line_arr('claim_subrogation->headers->subrogation_documents'); ?>
				<span id="subrogationClaimDocsCountDisplay"></span>
			</span>
			<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);" 
				onclick="_claim_subrogation_docs.addDocumentForm(event)" 
				title="<?php echo $this->lang->line_arr('claim_subrogation->buttons_links->add_docs_title'); ?>">
			</a>
		</h3>
		<div class="project_section" id="subrogation_attachment_content"></div>

	</div>
</div>