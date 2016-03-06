<div id="contractor_accordion" class="accordion">
	<?php
	if(count($contractors) && is_array($contractors)) {
		for($i = 0; $i < count($contractors); $i++) {
	?>
	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->contractor_name'); ?>: <?php echo $contractors[$i]->company; ?></span></h3>
	<div>
		<table cellspacing="0" class="viewOne projectViewOne">
			<!-- <tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_contractor->name'); ?></td>
				<td class='cell' ><?php echo $contractors[$i]->name; ?></td>
			</tr> -->
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
				<td class='cell' ><?php echo $contractors[$i]->address1.",<br/>".$contractors[$i]->address2.",<br/>".$contractors[$i]->city.",<br/>".$contractors[$i]->state.",<br/>".$contractors[$i]->country.",<br/>".$contractors[$i]->zip_code; ?></td>
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