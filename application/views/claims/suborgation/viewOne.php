<?php
	$suborgOne		= $suborgation[0];
?>
<div class="header-options">
	<span class="options-icon">
		<?php 
		if(in_array('update', $claimPermission['operation'])) {
			$editFn 		= "_claim_suborgation.editForm({openAs : 'popup'})";
		?>
			<span>
				<a class="step fi-page-edit size-21" href="javascript:void(0);" 
					onclick="<?php echo $editFn; ?>" title="<?php echo $this->lang->line_arr('claim_suborgation->details_view->edit_title'); ?>">
				</a>
			</span>
		<?php
		}
		if(in_array('delete', $claimPermission['operation'])) {
			$deleteFn 		= "_claim_suborgation.deleteRecord('".$claim_id."', '".$suborgation_id."')";
		?>
			<span>
				<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" 
					onclick="<?php echo $deleteFn; ?>" title="<?php echo $this->lang->line_arr('claim_suborgation->details_view->delete_title'); ?>">
				</a>
			</span>
		<?php
		}
		?>	
	</span>
</div>
<div class="clear"></div>
<div id="suborgation_accordion" class="accordion">
	<!-- Project Description -->
	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_suborgation->headers->customer_details'); ?></span></h3>
	<div>
		<table cellspacing="0" class="viewOne">
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('claim_suborgation->details_view->customer_name'); ?></td>
				<td class='cell'><?php echo $suborgOne->customer_name; ?></td>
			</tr>
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('claim_suborgation->details_view->status'); ?></td>
				<td class='cell'><?php echo $suborgOne->status; ?></td>
			</tr>
		</table>
	</div>
	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_suborgation->headers->climant_details'); ?></span></h3>
	<div>
		<table>
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('claim_suborgation->details_view->climant_name'); ?></td>
				<td class='cell'><?php echo $suborgOne->climant_name; ?></td>
			</tr>
			<?php echo $addressFile; ?>
		</table>
	</div>

	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim_suborgation->headers->description'); ?></span></h3>
	<div>
		<table cellspacing="0" class="viewOne">
			<tr>
				<td class='cell'><?php echo $suborgOne->description; ?></td>
			</tr>
		</table>
	</div>

	<h3>
		<span class="inner_accordion">
			<?php echo $this->lang->line_arr('claim_suborgation->headers->suborgation_notes'); ?>
			<span id="suborgationNotesCountForClaimDisplay"></span>
		</span>
		<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);" 
			onclick="_claim_suorgation_notes.createForm(event, {'openAs': 'popup', 'popupType' : ''})" 
			title="<?php echo $this->lang->line_arr('projects->buttons_links->add_notes_title'); ?>">
		</a>
	</h3>
	<div class="project_section" id="suborgation_note_content"></div>

	<h3>
		<span class="inner_accordion">
			<?php echo $this->lang->line_arr('claim_suborgation->headers->suborgation_documents'); ?>
			<span id="suborgationClaimDocsCountDisplay"></span>
		</span>
		<a class="step fi-page-add size-21 accordion-icon icon-right" href="javascript:void(0);" 
			onclick="_claim_suborgation_docs.addDocumentForm(event)" 
			title="<?php echo $this->lang->line_arr('claim_suborgation->buttons_links->add_docs_title'); ?>">
		</a>
	</h3>
	<div class="project_section" id="suborgation_attachment_content"></div>

</div>