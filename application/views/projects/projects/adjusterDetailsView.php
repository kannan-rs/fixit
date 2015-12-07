<div id="partner_accordion" class="accordion">
	<?php
	if(count($partners) && is_array($partners)) {
		for($i = 0; $i < count($partners); $i++) {
	?>
	<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('projects->headers->partner_name'); ?>: <?php echo $partners[$i]->company_name; ?></span></h3>
	<div>
		<table cellspacing="0" class="viewOne projectViewOne">
			<!-- <tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->name'); ?></td>
				<td class='cell' ><?php echo $partners[$i]->name; ?></td>
			</tr> -->
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->company'); ?></td>
				<td class='cell' ><?php echo $partners[$i]->company_name; ?></td>
			</tr>
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->prefered_contact_mode'); ?></td>
				<td class='cell' ><?php echo $partners[$i]->contact_pref; ?></td>
			</tr>
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->contact_office_email'); ?></td>
				<td class='cell' ><?php echo $partners[$i]->work_email_id; ?></td>
			</tr>
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->contact_office_number'); ?></td>
				<td class='cell' ><?php echo $partners[$i]->work_phone; ?></td>
			</tr>
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->contact_personal_email'); ?></td>
				<td class='cell' ><?php echo $partners[$i]->personal_email_id; ?></td>
			</tr>
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->contact_mobile_number'); ?></td>
				<td class='cell' ><?php echo $partners[$i]->mobile_no; ?></td>
			</tr>
			<tr>
				<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_partner->address'); ?></td>
				<td class='cell' ><?php echo $partners[$i]->address1.",<br/>".$partners[$i]->address2.",<br/>".$partners[$i]->city.",<br/>".$partners[$i]->state.",<br/>".$partners[$i]->country.",<br/>".$partners[$i]->zip_code; ?></td>
			</tr>
		</table>
	</div>
	<?php
		}
	} else {
	?>
		<span><?php echo $this->lang->line_arr('projects->details_view_partner->yet_to_assign'); ?></span>
	<?php
	}
	?>
</div>