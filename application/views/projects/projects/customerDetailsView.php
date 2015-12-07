<?php
	$firstName 			= $customer->first_name ? $customer->first_name : "--Not Provided--";
	$lastName 			= $customer->last_name ? $customer->last_name : "--Not Provided--";
	$address1 			= $customer->addr1 ? $customer->addr1 : "--Not Provided--";
	$address2			= $customer->addr2 ? $customer->addr2 : "--Not Provided--";
	$city 				= $customer->addr_city ? $customer->addr_city : "--Not Provided--";
	$state 				= $customer->addr_state ? $customer->addr_state : "--Not Provided--";
	$zipCode 			= $customer->addr_pin ? $customer->addr_pin : "--Not Provided--";
	$phone 				= $customer->contact_ph1 ? $customer->contact_ph1 : "--Not Provided--";
	$mobile 			= $customer->contact_mobile ? $customer->contact_mobile : "--Not Provided--";
	$altNo 				= $customer->contact_alt_mobile ? $customer->contact_alt_mobile : "--Not Provided--"; 
	$email 				= $customer->email ? $customer->email : "--Not Provided--";
	$contactPref 		= $customer->contact_pref ? $customer->contact_pref : "--Not Provided--";

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