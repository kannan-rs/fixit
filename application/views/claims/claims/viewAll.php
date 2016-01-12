<h2><?php echo $this->lang->line_arr('claim->headers->view_all'); ?></h2>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="claims-table-list">
	
	<?php
	if(count($claims) > 0) {
	?>
		<tr class='heading'>
			<td class='cell'><?php echo $this->lang->line_arr('claim->summary_table->claim_number'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('claim->summary_table->customer_name'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('claim->summary_table->customer_email'); ?></td>
		</tr>
	<?php

		for($i = 0; $i < count($claims); $i++) { 
			$claim = $claims[$i];
		?>
			<tr class='row viewAll <?php echo $cssStatus; ?>'>
				<!-- <td class='cell capitalize'>
					<a href="javascript:void(0);" onclick="_claims.viewOne('<?php echo $claims[$i]->id; ?>')">
						<?php echo $claim->name; ?>
					</a>
				</td> -->
				<td class="cell capitalize">
					<a href="javascript:void(0);" onclick="_claims.viewOne('<?php echo $claims[$i]->claim_id; ?>')">
						<?php echo $claim->	claim_number; ?></td>
					</a>
				<td class="cell capitalize"><?php echo isset($customer_names[$claim->claim_customer_id]) ? $customer_names[$claim->claim_customer_id] : "-NA-"; ?></td>
				<td class="cell capitalize"><?php echo $claim->customer_contact_no.", ".$claim->customer_email_id; ?></td>
			</tr>
		<?php
		}
	}  else {
	?>
	<tr>
		<td>
			-- No Claim data present --
		</td>
	</tr>
	<?php
	}
	?>
	</table>
</div>