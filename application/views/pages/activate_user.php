<?php
$userName = "Fixit Member";

if($params && isset($params["response"]["activated_user"])) {
	//$userName = $params["response"]["activated_user"][0]->first_name." ".$params["response"]["activated_user"][0]->last_name;
}
?>

<?php
if( !empty($userName)) {
?>
	<h3>Account Activation Successful</h3>
	<p>Dear <?php echo $userName; ?>, </p>
	<p>
		Your account is been activated successfully. Please login to see the further information.
	</p>
<?php
} else {
?>
	<h3>Account Activation failed</h3>
	<p>Dear User, </p>
	<p>Your account activation with fixit networks failed. Following might be the reasons for failure.</p>
	<p>1. Activation key expired</p>
	<p>2. Account is disabled by fixit admin</p>
	<p>Please contact fixit network admin for further details </p>
	<br/>
	<br/>
<?php
}
?>