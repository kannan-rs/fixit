<!-- Need to be removed -->
<!-- Login form -->
<!-- <ul>
	<li>
		<h4>User Login</h4>
		<form class="login-form" id="login_form" method="post" action="/validation/login">
			<p>
				<input type=email id="login_email" name="login_email" placeholder="Email Id" required />
			</p>
			<p>
				<input type=password id="login_password" name="login_password" placeholder="Password" required />
			</p>
			<p class="button-panel">
				<a class="signup" href="<?php echo $baseUrl; ?>main/signup">Sign up</a>
				<button class="formbutton" type="button" onclick="homeObj.loginValidate()">Login</button>
			</p>
			<p class='note error' id="login_error"></p>
		</form>
	</li>
</ul> -->
<?php if ($page != "signup") { ?>
<form class="login-form" id="login_form" method="post" action="/validation/login">
	<table>
		<tbody>
			<tr>
				<td>
					<input type=email id="login_email" name="login_email" placeholder="Email Id" required />
				</td>
			</tr>
			<tr>
				<td>
					<input type=password id="login_password" name="login_password" placeholder="Password" required />
					<img src="/images/blue_go_button.gif" class="login-image" onclick="homeObj.loginValidate()">
				</td>
			</tr>
			<tr>
				<td>
					<p class='note error' id="login_error"></p>
				</td>
			</tr>
		</tbody>
	</table>
</form>
<?php } ?>