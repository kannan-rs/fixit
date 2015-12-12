<!-- SIDEBAR -->
<ul>	
	<?php
	/*print_r($projectPermission);
echo "<br/>";
print_r($contractorPermission);
echo "<br/>";
print_r($adjusterPermission);*/
	if($this->session->userdata("is_logged_in")) {
		
		$controller 	= $this->session->userdata("controller");
		$page 			= $this->session->userdata("page");
		$module			= $this->session->userdata("module");
		$role_id 		= $this->session->userdata("role_id");
		
		$menuOutput = "";

		$menuCount = isset($menus) && isset($menus[$page]) && is_array($menus[$page]) ? count($menus[$page]) : 0;

		$leftMenus = $menus[$page];
		
		for($menuIdx = 0; $menuIdx < $menuCount; $menuIdx++) {
			$leftLoopMenu = $leftMenus[$menuIdx];
			if(isset($leftLoopMenu['role_id'])) {
				echo "role ID->".$leftLoopMenu['role_id']."<br/>";
			}

			if(isset($leftLoopMenu['dependency'])) {
				$dependency = $leftLoopMenu['dependency'];
				if(isset($dependency['roles_by_name']) && isset($role_disp_name) && !in_array($role_disp_name, $dependency['roles_by_name'])) {
					continue;
				}

				if(isset($dependency['permissions']) && isset($dependency['operation'])) {
					$permissionKey 		= $dependency['permissions'];
					$allowedPermission 	= $$permissionKey;
					$operation 			= $dependency['operation'];
					$showMenu 			= false;

					for($opIx = 0; $opIx < count($operation); $opIx++) {
						if(in_array($operation[$opIx], $allowedPermission['operation'])) {
							$showMenu = true;
							break;
						}
					}
					if(!$showMenu) {
						continue;
					}
				}
			}

			$selected = ($module == $leftLoopMenu['key']) ? "selected" : "";
			$selected = ($selected == "" && $module == "" && $menus_default[$page] == $leftLoopMenu['key']) ? "selected" : $selected; 
			$menuOutput .= "<li class=\"".$selected."\"><a href=\"".$leftLoopMenu['link']."\">". $leftLoopMenu['text'] ."</a></li>";
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