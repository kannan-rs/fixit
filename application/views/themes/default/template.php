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
		<?php
		if( !$is_logged_in && $currentPage == "" || $currentPage == "index") {
		?>
		<ul class="banner-ul" id="slider1">
		  	<li><img src="/img/pic1.jpg" class="banner-image" />
		  		<span class="banner-text">Manage projects, large or small</span>
		  	</li>
		  	<li><img src="/img/pic2.jpg" class="banner-image" />
		  		<span class="banner-text">Improve communication with your service provider</span>
		  	</li>
		  	<li><img src="/img/pic3.jpg" class="banner-image" />
		  		<span class="banner-text">Over communicate with your customers</span>
		  	</li>
		  	<li><img src="/img/pic4.jpg" class="banner-image" />
		  		<span class="banner-text">Always know what is next in your project</span>
		 	</li>
		</ul>
		<?php
    	}
    	?>
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
			<?php
			if(!is_logged_in() && $currentPage == "" || $currentPage == "index") {
			?>
			<div style="clear:both"></div>
			<div class="slider5">
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount1">
			  	<h3>Discount 1</h3>
			  	<div>
			  		<b> Header</b> <br/>
			  		Some text to show discount<br/>
			  		<span style="font-size:20px">Discount $20</span>
			  	</div>
			  </div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount2">
			  	<h3>Discount 2</h3>
			  	<div>
			  		<b> Header</b> <br/>
			  		Some text to show discount<br/>
			  		<span style="font-size:20px">Discount 20%</span>
			  	</div>
			  </div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount3"></div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount4"></div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount5"></div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount6"></div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount7"></div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount8"></div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount9"></div>
			  <div class="slide"><img src="http://placehold.it/300x150&text=ImageForDiscount10"></div>
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