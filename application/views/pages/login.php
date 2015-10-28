<div id="index_content">
	<form class="login-form" id="login_form" method="post" action="/validation/login">
		<table>
			<tbody>
				<tr>
					<td colspan="2">
						<h2>Login Form</h2>
					</td>
				</tr>
				<tr>
					<td class="label">
						user Name :
					</td>
					<td>
						<input type=email id="login_email" name="login_email" placeholder="Email Id" required />
					</td>
				</tr>
				<tr>
					<td class="label">
						Password :
					</td>
					<td>
						<input type=password id="login_password" name="login_password" placeholder="Password" required />
						<!-- <img src="/images/blue_go_button.gif" class="login-image" onclick="homeObj.loginValidate()"> -->
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<button type="reset" id="resetButton" onclick="">Reset</button>
						<button class="formbutton" type="button" onclick="homeObj.loginValidate()">Login</button>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p class='note error' id="login_error"></p>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="extralinks">
						New to thefixitnetwork <a class="signup" href="<?php echo $baseUrl; ?>main/signup">Click here</a> to register
					</td>
				</tr>
				<tr>
					<td colspan="2" class="extralinks">
						Forgot Password <a class="signup" href="<?php echo $baseUrl; ?>main/forgotpass">Click here</a> to retrive
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>