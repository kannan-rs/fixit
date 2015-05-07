<!-- TITLE -->
<h1><a href="#">Fixit</a></h1>
<h2>We will fix all your home and insurence needs</h2>
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
}
?>
<div class="clear"></div>
<!-- END TITLE -->