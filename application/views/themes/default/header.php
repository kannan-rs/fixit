<!-- TITLE -->
<div class="logo">
	<a href="#"><img src="<?php echo $baseUrl; ?>/images/logo.jpg" class="logo-img"></a>
	<br/>
	<span class="captionText">A marketplace for you and your resources</span>
</div>

<?php
if($this->session->userdata("is_logged_in")) {
?>
	<div class='logged_in'>
			<span class="logged-in-as">
				Logged in as <?php echo $this->session->userdata("email") ?> 
			</span>
			<span class="logged-in-as">
				Privilege as <b><i><?php echo $this->session->userdata("account_type") ?></i></b>
				<a href='<?php echo base_url(); ?>main/logout'>Logout</a>
			</span>
	</div>
<?php
} else {
	if ($page != "signup" && $page != "login" && $page != "forgotpass") {
?>
	<div class="login_form">
	<?php //echo $login_form; ?>
		<table>
			<tbody>
				<tr>
					<td>
						Existing User? <a class="signup" href="<?php echo $baseUrl; ?>main/login">Sign in </a>
					</td>
				</tr>
				<tr>
					<td>
						New to Fixit? <a class="signup" href="<?php echo $baseUrl; ?>main/signup">Signup</a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
<?php
	}
}
?>
<div class="clear"></div>
<!-- END TITLE -->