<?php
if(isset($discountList) && count($discountList)) {
	$discount = $discountList[0];
	if(!empty($discount->discount_image)) {
?>
	<div><img src="data:image/jpeg;base64,<?php echo base64_encode(stripslashes($discount->discount_image) ); ?>"></div>
<?php
	} else {
?>
	- No Discount Image Uploaded -
<?php
	}
}
?>