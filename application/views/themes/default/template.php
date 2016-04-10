<!DOCTYPE html>
<html ng-app="fixit_app">
<head>
	<!-- Metadatas -->
	<title>The Fixit Network: A marketplace for you and your resources</title>
	<!-- CSS -->
	<!-- Javascripts -->
	<script>
	var session 				= <?php print_r(json_encode($initVar)); ?>;
	var projectPermission		= <?php print_r( isset($projectPermission) ? json_encode($projectPermission) : "\"\""); ?>;
	var contractorPermission	= <?php print_r( isset($contractorPermission) ? json_encode($contractorPermission) : "\"\""); ?>;
	var adjusterPermission		= <?php print_r( isset($adjusterPermission) ? json_encode($adjusterPermission) : "\"\""); ?>;
	var claimPermission			= <?php print_r( isset($claimPermission) ? json_encode($claimPermission) : "\"\""); ?>;
	</script>

	<?php 
		echo $includes; 
		//$main_content_css = !is_logged_in() ? "column-480" : "column-700";
		$main_content_css = $this->session->userdata("page") == "signup" || $this->session->userdata("page") == "login" || $this->session->userdata("page") == "forgotpass"? "column-940" : "column-700";
		//$main_content_css = $main_content_css == "column-480" && $this->session->userdata("page") != "signup" ? "column-480" : "column-700";
	?>
</head>
<body>
	<div class="wrapper">
		<!-- Header -->
		<header class="header" ng-controller="header">
			<div ng-include="header_view"></div>
		</header>
		<!-- Top Navigation Menu -->
		<div class="nav_top" ng-controller="top_menu">
			<div ng-include="top_menu_view"></div>
		</div>
		<!-- Main Content Section for all application actions -->
		<!-- <section ng-controller="<?php echo $this->session->userdata("page"); ?>"> -->
		<section>
			<?php
			if(is_logged_in()) {
				if(isset($left_side_bar) && $left_side_bar != "") {
			?>
				<div class="sidebar column-220 column-left">	
					<!-- Left Side Bar and left navigation -->
					<?php echo $left_side_bar; ?>
				</div>
			<?php
				} else {
					$main_content_css = "column-940";
				}
			}
			?>
			<div class="content <?php echo $main_content_css; ?> column-left inner-column" ng-controller="fixitcontent">
				<!-- Main Content -->
				<?php echo $main_content; ?>
			</div>
			<!-- Right Side menus -->
			<?php
			if(!is_logged_in() && $this->session->userdata("page") != "login" && $this->session->userdata("page") != "signup" && $this->session->userdata("page") != "forgotpass") {
			?>
				<div class="sidebar column-220 column-right">	
					<?php echo $right_side_bar; ?>
				</div>
			<?php
			}
			?>
		</section>
		
		<!-- Footer -->
		<footer ng-controller="footer">
			<div ng-include="footer_view"></div>
		</footer>
	</div>
</body>
</html>