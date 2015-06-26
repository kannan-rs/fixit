<?php
	$addressLine1 	= !empty($addressLine1) ? $addressLine1 : "";
	$addressLine2 	= !empty($addressLine2) ? $addressLine2 : "";
	$city 			= !empty($city) ? $city : "";
	$country 		= !empty($country) ? $country : "";
	$state 			= !empty($state) ? $state : "";
	$pinCode 		= !empty($pinCode) ? $pinCode : "";
	$requestFrom	= !empty($requestFrom) ? $requestFrom : "";
?>
<div class="label">Address Line1:</div>
<div>
	<?php 
		if($requestFrom == "view") { echo $addressLine1; } else { ?>
		<input type="text" class="address" name="addressLine1" id="addressLine1" value="<?php echo $addressLine1; ?>" placeholder="Address Line 1:" required>
	<?php } ?>
</div>
<div class="label">Address Line2:</div>
<div>
	<?php 
		if($requestFrom == "view") { echo $addressLine2; } else { ?>
	<input type="text" class="address" name="addressLine2" id="addressLine2" value="<?php echo $addressLine2; ?>" placeholder="Address Line 2" required>
	<?php } ?>
</div>
<div class="label">City:</div>
<div>
	<?php 
		if($requestFrom == "view") { echo $city; } else { ?>
	<input type="text" name="city" id="city" value="<?php echo $city; ?>" placeholder="City" required>
	<?php } ?>
</div>
<DIV class="label">Country:</DIV>
<div>
	<?php 
		if($requestFrom == "view") { echo $country; } else { ?>
	<input type="hidden" name="countryDbVal" id="countryDbVal" value="<?php echo $country;?>" >
	<select name="country" id="country" required onchange="formUtilObj.populateState(this.value, 'state');">
		<option value="">--Select Country--</option>
	</select>
	<?php } ?>
</div>
<DIV class="label">State:</DIV>
<div>
	<?php 
		if($requestFrom == "view") { echo $state; } else { ?>
	<input type="hidden" name="stateDbVal" id="stateDbVal" value="<?php echo $state;?>" >
	<select name="state" id="state" required>
		<option value="">--Select State--</option>
	</select>
	<?php } ?>
</div>
<div class="label">Zip Code:</div>
<div>
	<?php 
		if($requestFrom == "view") { echo $pinCode; } else { ?>
	<input type="number" name="pinCode" id="pinCode" value="<?php echo $pinCode;?>" maxlength='5' placeholder="Pin Code" required>
	<?php } ?>
</div>