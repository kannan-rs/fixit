<?php
/*SIDEBAR*/ 
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

		$menuCount = isset($left_menus) && isset($left_menus[$page]) && is_array($left_menus[$page]) ? count($left_menus[$page]) : 0;

		$leftMenus = isset($left_menus[$page]) ? $left_menus[$page] : null;
		
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
			$selected = ($selected == "" && $module == "" && $left_menus_default[$page] == $leftLoopMenu['key']) ? "selected" : $selected; 
			$menuOutput .= "<li class=\"".$selected."\"><a href=\"".$leftLoopMenu['link']."\">". $leftLoopMenu['text'] ."</a></li>";
		}

		$menuTitle = isset($menu_title) && isset($menu_title[$page]) ? $menu_title[$page] : "";

		if(isset($menuOutput) && $menuOutput != "") {
		?>
		<ul>
			<li>
				<!-- <h4><?php echo $menuTitle; ?></h4> -->
				<ul class="blocklist">
				  <?php echo $menuOutput; ?>
				</ul>
			</li>
		</ul>
		<?php
		}
	}
	?>