<?php
	$addressLine1 	= !empty($addressLine1) ? $addressLine1 : "";
	$addressLine2 	= !empty($addressLine2) ? $addressLine2 : "";
	$city 			= !empty($city) ? $city : "";
	$country 		= !empty($country) ? $country : "";
	$state 			= !empty($state) ? $state : "";
	$zipCode 		= !empty($zipCode) ? $zipCode : "";
	$requestFrom	= !empty($requestFrom) ? $requestFrom : "";
?>

<tr>
	<td><div class="label"><?php echo $this->lang->line_arr('address->input_form->addressLine1'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $addressLine1; 
				} else { ?>
				<input type="text" class="address" name="addressLine1" id="addressLine1" value="<?php echo $addressLine1; ?>" placeholder="<?php echo $this->lang->line_arr('address->input_form->addressLine1_ph'); ?>:" required>
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
	<td><div class="label"><?php echo $this->lang->line_arr('address->input_form->city'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $city; 
				} else { ?>
					<input type="hidden" name="cityDbVal" id="cityDbVal" value="<?php echo $city;?>" >
					<!--
						<input type="text" name="city" id="city" value="<?php echo $city; ?>" list="city_list" placeholder="City" onkeyup="utilObj.getAndSetMatchCity(this.value)" onblur="utilObj.setAddressByCity()" required>
					-->
					<select name="city" id="city" onkeyup="utilObj.getAndSetMatchCity(this.value)" onblur="utilObj.setAddressByCity()" required>
					</select>					
			<?php } ?>
		</div>
	</td>
</tr>
<tr>
	<td><DIV class="label"><?php echo $this->lang->line_arr('address->input_form->state'); ?>:</DIV></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $state; 
				} else { ?>
					<input type="hidden" name="stateDbVal" id="stateDbVal" value="<?php echo $state;?>" >
					<select name="state" id="state" required>
						<option value=""><?php echo $this->lang->line_arr('address->input_form->state_option_0'); ?></option>
					</select>
			<?php } ?>
		</div>
	</td>
</tr>
<tr>
	<td><div class="label"><?php echo $this->lang->line_arr('address->input_form->zipCode'); ?>:</div></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $zipCode; 
				} else { ?>
					<!-- <input type="text" name="zipCode" id="zipCode" list="zipcode_list" value="<?php echo $zipCode;?>" maxlength='5' placeholder="Zip Code" onblur="utilObj.setPostalCodeDetails()" required> -->
					<input type="text" name="zipCode" id="zipCode" list="zipcode_list" value="<?php echo $zipCode;?>" maxlength='5' placeholder="<?php echo $this->lang->line_arr('address->input_form->zipCode_ph'); ?>" required>
					<datalist id="zipcode_list"></datalist>
			<?php } ?>
		</div>
	</td>
</tr>

<tr>
	<td><DIV class="label"><?php echo $this->lang->line_arr('address->input_form->country'); ?>:</DIV></td>
	<td>
		<div>
			<?php 
				if($requestFrom == "view") {
					echo $country; 
				} else { ?>
					<input type="hidden" name="countryDbVal" id="countryDbVal" value="<?php echo $country;?>">
					<select name="country" id="country" onchange="utilObj.populateState(this.value, '<?php echo $forForm; ?>');" required>
						<option><?php echo $this->lang->line_arr('address->input_form->country_option_0'); ?></option>
					</select>
			<?php } ?>
		</div>
	</td>
</tr>
