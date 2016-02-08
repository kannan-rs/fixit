<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('claim_subrogation->headers->view_all'); ?></h2>
	<span class="options-icon">
		<?php 
		if(in_array(OPERATION_CREATE, $claimPermission['operation'])) {
			$createFn 		= "_claim_subrogation.createForm({openAs : 'popup'})";
		?>
			<span>
				<a class="step fi-page-edit size-21" href="javascript:void(0);" 
					onclick="<?php echo $createFn; ?>" title="<?php echo $this->lang->line_arr('claim_subrogation->details_view->add_title'); ?>">
				</a>
			</span>
		<?php
		}
		?>	
	</span>
</div>

<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="subrogation-table-list">
	
	<?php
	if(count($subrogation) > 0) {
	?>
		<tr class='heading'>
			<td class='cell'><?php echo $this->lang->line_arr('claim_subrogation->summary_table->climant_name'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('claim_subrogation->summary_table->description'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('claim_subrogation->summary_table->status'); ?></td>
		</tr>
	<?php

		for($i = 0; $i < count($subrogation); $i++) { 
			$suborgOne = $subrogation[$i];
		?>
			<tr class='row viewAll'>
				<td class="cell capitalize">
					<a href="javascript:void(0);" onclick="_claim_subrogation.viewOne('<?php echo $suborgOne->subrogation_id; ?>');">
						<?php echo $suborgOne->climant_name; ?></td>
					</a>
				</td>
				<td class="cell capitalize">
					<?php echo $suborgOne->description; ?>
				</td>
				<td class="cell capitalize"><?php echo $suborgOne->status; ?></td>
			</tr>
		<?php
		}
	}  else {
	?>
	<tr>
		<td>
			-- No subrogation data present --
		</td>
	</tr>
	<?php
	}
	?>
	</table>
</div>