<!-- SIDEBAR -->
<ul>	
	<!--
	<li>
		<p class="phone">+44 02392 299189</p>
	</li>
	-->
	<?php
	if($this->session->userdata("is_logged_in")) {
		
		$controller 	= $this->session->userdata("controller");
		$page 			= $this->session->userdata("page");
		$module			= $this->session->userdata("module");
		$account_type 	= $this->session->userdata("account_type");
		
		if($page == "personalDetails") {
			$menuOutput = "";
			for($menuIdx = 0; $menuIdx < count($menus['personalDetails']); $menuIdx++) {
				if(!isset($menus['personalDetails'][$menuIdx]['account_type']) || 
					(isset($menus['personalDetails'][$menuIdx]['account_type']) && $menus['personalDetails'][$menuIdx]['account_type'] == $account_type)) {
					$selected = ($this->session->userdata("module") == $menus['personalDetails'][$menuIdx]['key']) ? "selected" : "";
					$menuOutput .= "<li class=\"".$selected."\"><a href=\"".$menus['personalDetails'][$menuIdx]['link']."\">". $menus['personalDetails'][$menuIdx]['text'] ."</a></li>";
				}
			}
		?>
			<li>
				<h4>Personal Details</h4>
				<ul class="blocklist">
				  <?php echo $menuOutput; ?>
				</ul>
			</li>
		<?php
		} else if($page == "security") {
			$menuOutput = "";
			for($menuIdx = 0; $menuIdx < count($menus['security']); $menuIdx++) {
				if(!isset($menus['security'][$menuIdx]['account_type']) || 
					(isset($menus['security'][$menuIdx]['account_type']) && $menus['security'][$menuIdx]['account_type'] == $account_type)) {
					$selected = ($this->session->userdata("module") == $menus['security'][$menuIdx]['key']) ? "selected" : "";
					$menuOutput .= "<li class=\"".$selected."\"><a href=\"".$menus['security'][$menuIdx]['link']."\">". $menus['security'][$menuIdx]['text'] ."</a></li>";
				}
			}
		?>
			<li>
				<h4>Security Management</h4>
				<ul class="blocklist">
				  <?php echo $menuOutput; ?>
				</ul>
			</li>
		<?php
		} else if($page == "projects") {
			$menuOutput = "";
			for($menuIdx = 0; $menuIdx < count($menus['projects']); $menuIdx++) {
				if(!isset($menus['projects'][$menuIdx]['account_type']) || 
					(isset($menus['projects'][$menuIdx]['account_type']) && $menus['projects'][$menuIdx]['account_type'] == $account_type)) {
					$selected = ($this->session->userdata("module") == $menus['projects'][$menuIdx]['key']) ? "selected" : "";
					$menuOutput .= "<li class=\"".$selected."\"><a href=\"".$menus['projects'][$menuIdx]['link']."\">". $menus['projects'][$menuIdx]['text'] ."</a></li>";
				}
			}
		?>
			<li>
				<h4>projects Management</h4>
				<ul class="blocklist">
				  <?php echo $menuOutput; ?>
				</ul>
			</li>
		<?php
		}
	} else { // If Not logged in then following link
	?>
		<li>
			<h4>Blocklist</h4>
			<ul class="blocklist">
			  <li><a href="#">Lorem ipsum dolor sit amet.</a></li>
			  <li class="selected"><a href="#">Quisque consequat nunc a felis.</a></li>
			  <li><a href="#">Suspendisse consequat magna at.</a></li>
			  <li><a href="#">Etiam eget diam id ligula.</a></li>
			  <li><a href="#">Sed in mauris non nibh.</a></li>
			</ul>
		</li>
		<li>
		<h4>Testimonial</h4>
		<p class="testimonial">
			<span>&ldquo; </span> Nunc fringilla porttitor ipsum. Nulla rutrum sapien sed leo. Fusce sit amet nulla ac velit commodo mattis. Ut tristique neque nec lorem. <span> &rdquo;</span><br />
			<strong>Dolor sit</strong>
			<em>Curabitur</em>
		</p>
	</li>
	<?php
	}
	?>
</ul>
<!-- SIDEBAR -->