<?php
	$menus = array(
		0 => array('text' => "Home", 'link' => "/main/index"),
		1 => array('text' => "Security", 'link' => "/main/security"),
		2 => array('text' => "Projects", 'link' => "/main/projects")
	);
?>
<ul>
	<!-- MENU -->
	<?php
	for($menuIdx = 0; $menuIdx < count($menus); $menuIdx++) {
		$selected = "";

		if( preg_replace('/\s+/', '', strtolower($menus[$menuIdx]["text"])) == $page) {
			$selected = 'class = "current"';
		}
		echo '<li '.$selected.'><a href="'. $menus[$menuIdx]["link"] .'">'. $menus[$menuIdx]["text"] .'</a></li>';
	}
	?>
	<!-- END MENU -->
</ul>