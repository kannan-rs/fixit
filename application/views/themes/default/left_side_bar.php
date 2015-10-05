<!-- SIDEBAR -->
<ul>	
	<?php
	if($this->session->userdata("is_logged_in")) {
		
		$controller 	= $this->session->userdata("controller");
		$page 			= $this->session->userdata("page");
		$module			= $this->session->userdata("module");
		$account_type 	= $this->session->userdata("account_type");
		
		$menuOutput = "";

		$menuCount = isset($menus) && isset($menus[$page]) && is_array($menus[$page]) ? count($menus[$page]) : 0;
		
		for($menuIdx = 0; $menuIdx < $menuCount; $menuIdx++) {
			if(!isset($menus[$page][$menuIdx]['account_type']) || 
				(isset($menus[$page][$menuIdx]['account_type']) && $menus[$page][$menuIdx]['account_type'] == $account_type)) {
				$selected = ($module == $menus[$page][$menuIdx]['key']) ? "selected" : "";
				$selected = ($selected == "" && $module == "" && $menus_default[$page] == $menus[$page][$menuIdx]['key']) ? "selected" : $selected; 
				$menuOutput .= "<li class=\"".$selected."\"><a href=\"".$menus[$page][$menuIdx]['link']."\">". $menus[$page][$menuIdx]['text'] ."</a></li>";
			}
		}

		$menuTitle = isset($menu_title) && isset($menu_title[$page]) ? $menu_title[$page] : "";
		?>
		<li>
			<h4><?php echo $menuTitle; ?></h4>
			<ul class="blocklist">
			  <?php echo $menuOutput; ?>
			</ul>
		</li>
		<?php
	} else { // If Not logged in then following link
	/*
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
	*/
	}
	?>
</ul>
<!-- SIDEBAR -->