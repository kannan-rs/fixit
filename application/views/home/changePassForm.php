<h2>Change Password</h2>
<form id="update_password_form" name="update_password_form">
	<div class='form'>
		<input type="hidden" id='email' value="<?php echo $user_details[0]->user_name; ?>" />
		<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details[0]->sno; ?>">
		<div class="label">Password</div>
		<div><input type="password" name="password" id="password" value="" placeholder="Password" required></div>
		<div class="label">Confirm Password:</div>
		<div><input type="password" name="confirmPassword" id="confirmPassword" value="" placeholder="Confirm Password" required></div>
		<div class="label">Password Hint:</div>
		<div><input type="text" name="passwordHint" id="passwordHint" value="" placeholder="Password Hint"></div>
		<p class="button-panel">
			<button type="button" id="signup_user_submit" onclick="homeObj._userInfo.updatePasswordValidate()">Update</button>
		</p>
	</div>
</form>