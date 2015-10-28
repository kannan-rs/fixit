<div id="index_content">
	<form class="forgotpass-form" id="forgotpass_form" method="post" action="/validation/forgotpass">
		<table>
			<tbody>
				<tr>
					<td colspan="2">
						<h2>Password Retrive Form</h2>
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
					<td colspan="2">
						<button type="reset" id="resetButton" onclick="">Reset</button>
						<button class="formbutton" type="button" onclick="homeObj.forgotPassValidate()">Retrive Password</button>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p class='note error' id="forgotpass_error"></p>
						<p class='note success' id="forgotpass_success"></p>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="extralinks">
						New to thefixitnetwork <a class="signup" href="<?php echo $baseUrl; ?>main/signup">Click here</a> to register
					</td>
				</tr>
				<tr>
					<td colspan="2" class="extralinks">
						Existing User <a class="signup" href="<?php echo $baseUrl; ?>main/login">Click here</a> to login
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>