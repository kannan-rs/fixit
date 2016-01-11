<?php
	$claim		= $claims[0];
?>
<div class="header-options">
	<h2><?php echo $this->lang->line_arr('claim->headers->view_one'); ?></h2>
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
		<?php echo $addressFile; ?>
	</div>

	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->claim_notes'); ?></span></h3>
	<div class="form">
		-- Yet to Develop --
	</div>

	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->claim_dairy_update'); ?></span></h3>
	<div class="form">
		-- Yet to Develop --
	</div>

	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('claim->headers->claim_payment'); ?></span></h3>
	<div class="form">
		-- Yet to Develop --
	</div>
</div>