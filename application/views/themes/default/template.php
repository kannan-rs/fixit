<!DOCTYPE html>
<html>
<head>
<!-- Metadatas -->
<title>simplecorp premium - by spyka Webmaster</title>
<!-- CSS -->
<!-- Javascripts -->
<script>
var session = "";
session = <?php print_r(json_encode($initVar)); ?>;
</script>

<?php 
	echo $includes; 
	$main_content_css = !$this->session->userdata("is_logged_in") ? "column-480" : "column-700";
	$main_content_css = $main_content_css == "column-480" && $this->session->userdata("page") != "signup" ? "column-480" : "column-700";
?>
</head>
<body>
<div class="wrapper">

	<!-- Header -->
	<header class="header">
		<?php echo $header; ?>
	</header>

	<!-- Top Navigation Menu -->
	<nav>
		<?php echo $top_menu; ?>
	</nav>

	<!-- Main Content Section for all application actions -->
	<section>
		<div class="sidebar column-220 column-left">	
			<!-- Left Side Bar and left navigation -->
			<?php echo $left_side_bar; ?>
		</div>
		<div class="content <?php echo $main_content_css; ?> column-left inner-column">	
			<!-- Main Content -->
			<?php echo $main_content; ?>
		</div>
		<!-- Right Side menus -->
		<div class="sidebar column-220 column-right">	
			<?php echo $right_side_bar; ?>
		</div>
	</section>
	
	<!-- Footer -->
	<footer>
		<p class="footer">
			<?php echo $footer; ?>
		</p>
	</footer>
</div>
</body>
</html>
