<?php
	$firstName 			= $customer->first_name ? $customer->first_name : "--";
	$lastName 			= $customer->last_name ? $customer->last_name : "--";
	$address1 			= $customer->address1 ? $customer->address1 : "--";
	$address2			= $customer->address2 ? $customer->address2 : "--";
	$city 				= $customer->city ? $customer->city : "--";
	$state 				= $customer->state ? $customer->state : "--";
	$zipCode 			= $customer->zip_code ? $customer->zip_code : "--";
	$phone 				= $customer->contact_ph1 ? $customer->contact_ph1 : "--";
	$mobile 			= $customer->contact_mobile ? $customer->contact_mobile : "--";
	$altNo 				= $customer->contact_alt_mobile ? $customer->contact_alt_mobile : "--"; 
	$email 				= $customer->email ? $customer->email : "--";
	$contactPref 		= $customer->contact_pref ? $customer->contact_pref : "--";

?>

<table cellspacing="0" class="viewOne projectViewOne">
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_customer->first_name'); ?></td>
		<td class='cell' ><?php echo $firstName; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_customer->last_name'); ?></td>
		<td class='cell' ><?php echo $lastName; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_customer->address'); ?></td>
		<td class='cell' >
			<div><?php echo $address1; ?></div>
			<div><?php echo $address2; ?></div>
			<div><?php echo $city.", ".$state." - ".$zipCode; ?></div>
		</td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_customer->phone'); ?></td>
		<td class='cell' ><?php echo $phone; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_customer->mobile'); ?></td>
		<td class='cell' >
			<div><?php echo $mobile; ?></div>
			<div><?php echo $this->lang->line_arr('projects->details_view_customer->alternate_no'); ?> : <?php echo $altNo; ?></div>
		</td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_customer->email'); ?></td>
		<td class='cell' ><?php echo $email; ?></td>
	</tr>
	<tr>
		<td class='cell label'><?php echo $this->lang->line_arr('projects->details_view_customer->contact_pref'); ?></td>
		<td class='cell' ><?php echo $contactPref; ?></td>
	</tr>
</table>