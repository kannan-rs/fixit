<?php
	$addressLine1 	= !empty($addressLine1) ? $addressLine1 : "";
	$addressLine2 	= !empty($addressLine2) ? $addressLine2 : "";
	$city 			= !empty($city) ? $city : "";
	$country 		= !empty($country) ? $country : "";
	$state 			= !empty($state) ? $state : "";
	$zipCode 		= !empty($zipCode) ? $zipCode : "";
	$requestFrom	= !empty($requestFrom) ? $requestFrom : "";
?>
<div class="label">Address Line1:</div>
<div>
	<?php 
		if($requestFrom == "view") { echo $addressLine1; } else { ?>
		<input type="text" class="address" name="addressLine1" id="addressLine1" value="<?php echo $addressLine1; ?>" placeholder="Address Line 1:" required>
	<?php } ?>
</div>
<div class="label notMandatory">Address Line2:</div>
<div>
	<?php 
		if($requestFrom == "view") { echo $addressLine2; } else { ?>
	<input type="text" class="address" name="addressLine2" id="addressLine2" value="<?php echo $addressLine2; ?>" placeholder="Address Line 2" >
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
	<input type="hidden" name="countryDbVal" id="countryDbVal" value="<?php echo $country;?>">
	<select name="country" id="country" onchange="utilObj.populateState(this.value, '<?php echo $forForm; ?>');" required>
		<option>--Select Country--</option>
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
		if($requestFrom == "view") { echo $zipCode; } else { ?>
	<input type="text" name="zipCode" id="zipCode" value="<?php echo $zipCode;?>" maxlength='5' placeholder="Zip Code" required>
	<?php } ?>
</div>