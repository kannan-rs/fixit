<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('change_password->headers->title'); ?></h2>
</div>
<form id="update_password_form" name="update_password_form" class="inputForm">
	<input type="hidden" id='email' value="<?php echo $user_details[0]->user_name; ?>" />
	<input type="hidden" name="user_details_sno" id="user_details_sno" value="<?php echo $user_details[0]->sno; ?>">
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('change_password->input_form->password'); ?></td>
				<td><input type="password" name="password" id="password" value="" placeholder="<?php echo $this->lang->line_arr('change_password->input_form->password_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('change_password->input_form->confirmPassword'); ?></td>
				<td><input type="password" name="confirmPassword" id="confirmPassword" value="" placeholder="<?php echo $this->lang->line_arr('change_password->input_form->confirmPassword_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('change_password->input_form->passwordHint'); ?></td>
				<td><input type="text" name="passwordHint" id="passwordHint" value="" placeholder="<?php echo $this->lang->line_arr('change_password->input_form->passwordHint_ph'); ?>"></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="signup_user_submit" onclick="_userInfo.updatePasswordValidate()">
							<?php echo $this->lang->line_arr('change_password->buttons_links->update'); ?>
						</button>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</form>