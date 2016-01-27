<?php
	$addressLine1 	= !empty($addressLine1) ? $addressLine1 : "";
	$addressLine2 	= !empty($addressLine2) ? $addressLine2 : "";
	$city 			= !empty($city) ? $city : "";
	$country 		= !empty($country) ? $country : "";
	$state 			= !empty($state) ? $state : "";
	$zipCode 		= !empty($zipCode) ? $zipCode : "";
	$requestFrom	= !empty($requestFrom) ? $requestFrom : "";
	$id_prefix		= !empty($id_prefix) ? $id_prefix : "";
	$hidden			= !empty($hidden) ? $hidden : "";

	$notMandatory 	= isset($role_disp_name) && $role_disp_name == 'admin' ? "notMandatory" : "";
	$required		= isset($role_disp_name) && $role_disp_name == 'admin' ? "" : "required";

	if($requestFrom == "view") {
		$notMandatory = "notMandatory";
	}
?>

<tr>
	<td><div class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('address->input_form->addressLine1'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view" || $requestFrom == "both") {
					echo $addressLine1; 
				}
				if($requestFrom == "input" || $requestFrom == "both" ) { ?>
					<input type="text" class="address" name="<?php echo $id_prefix; ?>addressLine1" id="<?php echo $id_prefix; ?>addressLine1" value="<?php echo $addressLine1; ?>" 
						placeholder="<?php echo $this->lang->line_arr('address->input_form->addressLine1_ph'); ?>:" <?php  echo $required; ?> <?php echo $hidden; ?>>
				<?php } ?>
		</div>
	</td>
</tr>
<tr>
	<td><div class="label notMandatory"><?php echo $this->lang->line_arr('address->input_form->addressLine2'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view" || $requestFrom == "both") {
					echo $addressLine2; 
				}
				if($requestFrom == "input" || $requestFrom == "both" ) { ?>
					<input type="text" class="address" name="<?php echo $id_prefix; ?>addressLine2" id="<?php echo $id_prefix; ?>addressLine2" 
						value="<?php echo $addressLine2; ?>" placeholder="<?php echo $this->lang->line_arr('address->input_form->addressLine2_ph'); ?>" <?php echo $hidden; ?>>
			<?php } ?>
		</div>
	</td>
</tr>
<tr>
	<td><div class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('address->input_form->city'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view" || $requestFrom == "both") {
					echo $city; 
				}
				if($requestFrom == "input" || $requestFrom == "both") { ?>
					<input type="hidden" name="<?php echo $id_prefix; ?>cityDbVal" id="<?php echo $id_prefix; ?>cityDbVal" value="<?php echo $city;?>" >
					<select name="<?php echo $id_prefix; ?>city" id="<?php echo $id_prefix; ?>city" 
						onkeyup="_utils.getAndSetMatchCity(this.value,'','')" onblur="_utils.setAddressByCity()" <?php  echo $required; ?> <?php echo $hidden; ?>>
						<option value=""><?php echo $this->lang->line_arr('address->input_form->city_option_0'); ?></option>
						<?php if(!empty($city)) { ?>
							<option value="<?php echo $city ?>"><?php echo $city ?></option>
						<?php } ?>
					</select>
			<?php } ?>
		</div>
	</td>
</tr>
<tr>
	<td><DIV class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('address->input_form->state'); ?>:</DIV></td>
	<td width="90%">
		<div>
			<?php 
				if($requestFrom == "view" || $requestFrom == "both") {
					echo $state; 
				} 
				if($requestFrom == "input" || $requestFrom == "both") { 
			?>
					<input type="hidden" name="<?php echo $id_prefix; ?>stateDbVal" id="<?php echo $id_prefix; ?>stateDbVal" value="<?php echo $state;?>" >
					<select name="<?php echo $id_prefix; ?>state" id="<?php echo $id_prefix; ?>state" <?php  echo $required; ?> <?php echo $hidden; ?>>
						<option value=""><?php echo $this->lang->line_arr('address->input_form->state_option_0'); ?></option>
					</select>
			<?php } ?>
		</div>
	</td>
</tr>
<tr>
	<td><div class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('address->input_form->zipCode'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view" || $requestFrom == "both") {
					echo $zipCode; 
				} 
				if($requestFrom == "input" || $requestFrom == "both") { ?>
					<input type="hidden" name="<?php echo $id_prefix; ?>zipcodeDbVal" id="<?php echo $id_prefix; ?>zipcodeDbVal" value="<?php echo $zipCode;?>">
					<select name="<?php echo $id_prefix; ?>zipCode" id="<?php echo $id_prefix; ?>zipCode" <?php  echo $required; ?> <?php echo $hidden; ?>>
						<option><?php echo $this->lang->line_arr('address->input_form->zipcode_option_0'); ?></option>
					</select>
			<?php } ?>
		</div>
	</td>
</tr>

<tr>
	<td><DIV class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('address->input_form->country'); ?>:</DIV></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view" || $requestFrom == "both") {
					echo $country; 
				} 
				if($requestFrom == "input" || $requestFrom == "both" ) { ?>
					<input type="hidden" name="<?php echo $id_prefix; ?>countryDbVal" id="<?php echo $id_prefix; ?>countryDbVal" value="<?php echo $country;?>">
					<select name="<?php echo $id_prefix; ?>country" id="<?php echo $id_prefix; ?>country" <?php  echo $required; ?> <?php echo $hidden; ?>>
						<option><?php echo $this->lang->line_arr('address->input_form->country_option_0'); ?></option>
					</select>
			<?php } ?>
		</div>
	</td>
</tr>
