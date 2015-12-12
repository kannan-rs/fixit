<?php
	$menus = array(
		0 => array(
			'text' 			=> "Home",
			'link' 			=> "/main/index",
			'is_logged_in' 	=> "all"
		),
		1 => array(
			'text' 			=> "Overview",
			'link' 			=> "/main/overview",
			'is_logged_in' 	=> 0
		),
		2 => array(
			'text' 			=> "FAQ",
			'link' 			=> "/main/faq",
			'is_logged_in' 	=> 0
		),
		3 => array(
			'text' 			=> "About Us",
			'link' 			=> "/main/about_us",
			'is_logged_in' 	=> 0
		),
		4 => array(
			'text' 			=> "Contact Us",
			'link' 			=> "/main/contact_us",
			'is_logged_in' 	=> 0
		),
		5 => array(
			'text' 			=> "Security",
			'link' 			=> "/main/security",
			'is_logged_in' 	=> 1,
			'dependency'	=> array(
				'roles_by_name'	=> array('admin')
			)
		),
		6 => array(
			'text' 			=> "Projects",
			'link' 			=> "/main/projects",
			'is_logged_in' 	=> 1
		),
	);
$is_logged_in = (isset($is_logged_in) && $is_logged_in === 1) ? 1 : 0;

?>
<ul>
	<!-- MENU -->
	<?php

	for($menuIdx = 0; $menuIdx < count($menus); $menuIdx++) {
		$selected = "";

		if(isset($menus[$menuIdx]['dependency'])) {
			$dependency = $menus[$menuIdx]['dependency'];
			if(isset($dependency['roles_by_name']) && isset($role_disp_name) && !in_array($role_disp_name, $dependency['roles_by_name'])) {
				continue;
			}
		}
		if( strpos(preg_replace('/\s+/', '', strtolower($menus[$menuIdx]["link"])), $page) ) {
			$selected = 'current';
		}
		if( ($page == "home" || $page == "signup")  && strpos(preg_replace('/\s+/', '', strtolower($menus[$menuIdx]["link"])), "index")) {
			$selected = 'current';	
		}
		
		if($menus[$menuIdx]['is_logged_in'] === 'all' || $menus[$menuIdx]['is_logged_in'] === $is_logged_in)
			echo '<li class="'.$selected.'"><a href="'. $menus[$menuIdx]["link"] .'">'. $menus[$menuIdx]["text"] .'</a></li>';
	}
	?>
	<!-- END MENU -->
</ul>