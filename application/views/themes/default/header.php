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
				Role as <b><i><?php echo $role_disp_name ?></i></b>
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
					<?php
						$start = "<a class=\"signup\" href=\"".$baseUrl."main/login\">";
						$end = "</a>";
						echo str_replace(["##replace1##", "##replace2##"], [$start, $end], $this->lang->line_arr('headers->existing_user'));
					?>
					</td>
				</tr>
				<tr>
					<td>
					<?php
						$start = "<a class=\"signup\" href=\"".$baseUrl."main/signup\">";
						$end = "</a>";
						echo str_replace(["##replace1##", "##replace2##"], [$start, $end], $this->lang->line_arr('headers->new_user'));
					?>
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