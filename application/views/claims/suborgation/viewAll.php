<h2><?php echo $this->lang->line_arr('claim_suborgation->headers->view_all'); ?></h2>
<div class="header-options">
	<span class="options-icon">
		<?php 
		if(in_array('create', $claimPermission['operation'])) {
			$createFn 		= "_claim_suborgation.createForm({openAs : 'popup'})";
		?>
			<span>
				<a class="step fi-page-edit size-21" href="javascript:void(0);" 
					onclick="<?php echo $createFn; ?>" title="<?php echo $this->lang->line_arr('claim_suborgation->details_view->add_title'); ?>">
				</a>
			</span>
		<?php
		}
		?>	
	</span>
</div>

<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="suborgation-table-list">
	
	<?php
	if(count($suborgation) > 0) {
	?>
		<tr class='heading'>
			<td class='cell'><?php echo $this->lang->line_arr('claim_suborgation->summary_table->customer_name'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('claim_suborgation->summary_table->climant_name'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('claim_suborgation->summary_table->status'); ?></td>
		</tr>
	<?php

		for($i = 0; $i < count($suborgation); $i++) { 
			$suborgOne = $suborgation[$i];
		?>
			<tr class='row viewAll <?php echo $cssStatus; ?>'>
				<td class="cell capitalize">
					<a href="javascript:void(0);" onclick="_claim_suborgation.viewOne('<?php echo $suborgOne->suborgation_id; ?>');">
						<?php echo isset($customer_names[$suborgOne->customer_id]) ? $customer_names[$suborgOne->customer_id] : ""; ?></td>
					</a>
				</td>
				<td class="cell capitalize">
					<a href="javascript:void(0);" onclick="_claim_suborgation.viewOne('<?php echo $suborgOne->suborgation_id; ?>');">
						<?php echo $suborgOne->climant_name; ?></td>
					</a>
				</td>
				<td class="cell capitalize"><?php echo $suborgOne->status; ?></td>
			</tr>
		<?php
		}
	}  else {
	?>
	<tr>
		<td>
			-- No suborgation data present --
		</td>
	</tr>
	<?php
	}
	?>
	</table>
</div>