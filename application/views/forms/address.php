<?php
	$addressLine1 	= !empty($addressLine1) ? $addressLine1 : "";
	$addressLine2 	= !empty($addressLine2) ? $addressLine2 : "";
	$city 			= !empty($city) ? $city : "";
	$country 		= !empty($country) ? $country : "";
	$state 			= !empty($state) ? $state : "";
	$zipCode 		= !empty($zipCode) ? $zipCode : "";
	$requestFrom	= !empty($requestFrom) ? $requestFrom : "";

	$notMandatory 	= isset($role_disp_name) && $role_disp_name == 'admin' ? "notMandatory" : "";
	$required		= isset($role_disp_name) && $role_disp_name == 'admin' ? "" : "required";
?>

<tr>
	<td><div class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('address->input_form->addressLine1'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $addressLine1; 
				} else { ?>
					<input type="text" class="address" name="addressLine1" id="addressLine1" value="<?php echo $addressLine1; ?>" 
						placeholder="<?php echo $this->lang->line_arr('address->input_form->addressLine1_ph'); ?>:" <?php  echo $required; ?> >
				<?php } ?>
		</div>
	</td>
</tr>
<tr>
	<td><div class="label notMandatory"><?php echo $this->lang->line_arr('address->input_form->addressLine2'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $addressLine2; 
				} else { ?>
					<input type="text" class="address" name="addressLine2" id="addressLine2" value="<?php echo $addressLine2; ?>" placeholder="<?php echo $this->lang->line_arr('address->input_form->addressLine2_ph'); ?>" >
			<?php } ?>
		</div>
	</td>
</tr>
<tr>
	<td><div class="label <?php echo $notMandatory;?>"><?php echo $this->lang->line_arr('address->input_form->city'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $city; 
				} else { ?>
					<input type="hidden" name="cityDbVal" id="cityDbVal" value="<?php echo $city;?>" >
					<!--
						<input type="text" name="city" id="city" value="<?php echo $city; ?>" list="city_list" placeholder="City" onkeyup="_utils.getAndSetMatchCity(this.value)" onblur="_utils.setAddressByCity()" <?php  echo $required; ?> >
					-->
					<select name="city" id="city" onkeyup="_utils.getAndSetMatchCity(this.value)" onblur="_utils.setAddressByCity()" <?php  echo $required; ?> >
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
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $state; 
				} else { ?>
					<input type="hidden" name="stateDbVal" id="stateDbVal" value="<?php echo $state;?>" >
					<select name="state" id="state" <?php  echo $required; ?> >
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
				if($requestFrom == "view") {
					echo $zipCode; 
				} else { ?>
					<!-- <input type="text" name="zipCode" id="zipCode" list="zipcode_list" value="<?php echo $zipCode;?>" maxlength='5' placeholder="Zip Code" onblur="_utils.setPostalCodeDetails()" <?php  echo $required; ?> > -->
					<!-- <input type="text" name="zipCode" id="zipCode" list="zipcode_list" value="<?php echo $zipCode;?>" maxlength='5' placeholder="<?php echo $this->lang->line_arr('address->input_form->zipCode_ph'); ?>" <?php  echo $required; ?> > -->
					<!--<datalist id="zipcode_list"></datalist>-->
					<input type="hidden" name="zipcodeDbVal" id="zipcodeDbVal" value="<?php echo $zipCode;?>">
					<select name="zipCode" id="zipCode" <?php  echo $required; ?> >
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
				if($requestFrom == "view") {
					echo $country; 
				} else { ?>
					<input type="hidden" name="countryDbVal" id="countryDbVal" value="<?php echo $country;?>">
					<!-- <select name="country" id="country" onchange="_utils.populateStateOption({'country': this.value, 'moduleId' : '<?php echo $forForm; ?>'});" <?php  echo $required; ?> > -->
					<select name="country" id="country" <?php  echo $required; ?> >
						<option><?php echo $this->lang->line_arr('address->input_form->country_option_0'); ?></option>
					</select>
			<?php } ?>
		</div>
	</td>
</tr>
