<div id="index_content">
	<form class="login-form" id="login_form" method="post" action="/validation/login">
		<table>
			<tbody>
				<tr>
					<td colspan="2">
						<h2><?php echo $this->lang->line_arr('login->headers->title'); ?></h2>
					</td>
				</tr>
				<tr>
					<td class="label">
						<?php echo $this->lang->line_arr('login->input_form->login_email'); ?> :
					</td>
					<td>
						<input type=email id="login_email" name="login_email" placeholder="<?php echo $this->lang->line_arr('login->input_form->login_email_ph'); ?>" required />
					</td>
				</tr>
				<tr>
					<td class="label">
						<?php echo $this->lang->line_arr('login->input_form->login_password'); ?> :
					</td>
					<td>
						<input type=password id="login_password" name="login_password" placeholder="<?php echo $this->lang->line_arr('login->input_form->login_password_ph'); ?>" required />
						<!-- <img src="/images/blue_go_button.gif" class="login-image" onclick="homeObj.loginValidate()"> -->
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<button type="reset" id="resetButton" onclick=""><?php echo $this->lang->line_arr('buttons->reset'); ?></button>
						<button class="formbutton" type="button" onclick="homeObj.loginValidate()"><?php echo $this->lang->line_arr('login->buttons_links->login'); ?></button>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<p class='note error' id="login_error"></p>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="extralinks">
					<?php
						$start = "<a class=\"signup\" href=\"".$baseUrl."main/signup\">";
						$end = "</a>";
						echo str_replace(["##replace1##", "##replace2##"], [$start, $end], $this->lang->line_arr('login->buttons_links->new_customer'));
					?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="extralinks">
					<?php
						$start = "<a class=\"signup\" href=\"".$baseUrl."main/forgotpass\">";
						$end = "</a>";
						echo str_replace(["##replace1##", "##replace2##"], [$start, $end], $this->lang->line_arr('login->buttons_links->forgot_pass'));
					?>
					</td>
				</tr>
			</tbody>
		</table>
	</form>
</div>