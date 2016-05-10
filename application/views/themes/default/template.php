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
		<!--
			Slider
		-->
		<div id="jssor_1" style="position:relative; margin-top:10px; margin-bottom:10px;/*margin:0 auto;*/top:0px;left:0px;width:960px;height:300px;overflow:hidden;/*visibility:hidden;*/background:url('/img/main_bg.jpg') 50% 50% no-repeat;">
			<div data-p="172.50" style="">
                <div style="position: absolute; top: 10px; left: 10px; width: 480px; height: 300px; font-family: Arial, Verdana; font-size: 12px; text-align: left;">
                    <span style="display: block; line-height: 1em; text-transform: uppercase; font-size: 52px;
                                color: #FFFFFF;">A Market place for your resource</span><br /><br />
                    <span style="display: block; line-height: 1.1em; font-size: 2.5em; color: #FFFFFF;">
                        Inusrance, Project management,  service providers, etc..,
                        
                    </span>
                </div>
                <img src="/img/s3.png" style="position: absolute; top: 23px; left: 480px; width: 500px; height: 300px;" />
                <img data-u="thumb" src="/img/s3t.jpg" />
            </div>
	        <!-- Loading Screen -->
	        <!-- <div data-u="loading" style="position: absolute; top: 0px; left: 0px;">
	            <div style="filter: alpha(opacity=70); opacity: 0.7; position: absolute; display: block; top: 0px; left: 0px; width: 100%; height: 100%;"></div>
	            <div style="position:absolute;display:block;background:url('img/loading.gif') no-repeat center center;top:0px;left:0px;width:100%;height:100%;"></div>
	        </div>
	        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 980px; height: 400px; overflow: hidden;">
	            <div data-p="172.50" style="display: none;">
	                <div style="position: absolute; top: 10px; left: 10px; width: 480px; height: 300px; font-family: Arial, Verdana; font-size: 12px; text-align: left;"><br />
	                    <span style="display: block; line-height: 1em; text-transform: uppercase; font-size: 52px; color: #FFFFFF;">results driven</span><br /><br /><br />
	                    <span style="display: block; line-height: 1.1em; font-size: 2.5em; color: #FFFFFF;">
	                                                        iT Solutions & Services
	                                                    
	                    </span><br />
	                    <span style="display: block; line-height: 1.1em; font-size: 1.5em; color: #FFFFFF;">
	                                                        Our professional services help you address the ever evolving business and technological
	                                                        challenges.
	                                                    
	                    </span><br /><br />
	                    <a>
	                        <img src="img/find-out-more-bt.png" border="0" alt="auction slider" width="215" height="50" />
	                    </a>
	                </div>
	                <img src="img/s2.png" style="position: absolute; top: 23px; left: 480px; width: 500px; height: 300px;" />
	                <img data-u="thumb" src="img/s2t.jpg" />
	            </div>
	            <div data-p="172.50" style="display: none;">
	                <div style="position: absolute; top: 10px; left: 10px; width: 480px; height: 300px; font-family: Arial, Verdana; font-size: 12px; text-align: left;">
	                    <span style="display: block; line-height: 1em; text-transform: uppercase; font-size: 52px;
	                                color: #FFFFFF;">web design & development</span><br /><br />
	                    <span style="display: block; line-height: 1.1em; font-size: 2.5em; color: #FFFFFF;">
	                        Visually Compelling & Functional
	                        
	                    </span><br /><br />
	                    <a>
	                        <img src="img/find-out-more-bt.png" border="0" alt="ebay slideshow" width="215" height="50" />
	                    </a>
	                </div>
	                <img src="img/s3.png" style="position: absolute; top: 23px; left: 480px; width: 500px; height: 300px;" />
	                <img data-u="thumb" src="img/s3t.jpg" />
	            </div>
	            <div data-p="172.50" style="display: none;">
	                <div style="position: absolute; top: 10px; left: 10px; width: 480px; height: 300px; font-family: Arial, Verdana; font-size: 12px; text-align: left;">
	                    <span style="display: block; line-height: 1em; text-transform: uppercase; font-size: 52px;
	                    color: #FFFFFF;">Online marketing</span><br />
	                    <span style="display: block; line-height: 1.1em; font-size: 2.5em; color: #FFFFFF;">
	                            We enhance your brand, your website traffic and your bottom line online.
	                        
	                    </span><br /><br />
	                    <a>
	                        <img src="img/find-out-more-bt.png" border="0" alt="listing slider" width="215" height="50" />
	                    </a>
	                </div>
	                <img src="img/s4.png" style="position: absolute; top: 23px; left: 480px; width: 500px; height: 300px;" />
	                <img data-u="thumb" src="img/s4t.jpg" />
	            </div>
	        </div> -->
	        <!-- Thumbnail Navigator -->
	        <div data-u="thumbnavigator" class="jssort04" style="position:absolute;left:0px;bottom:0px;width:980px;height:60px;" data-autocenter="1">
	            <!-- Thumbnail Item Skin Begin -->
	            <div data-u="slides" style="cursor: default;">
	                <div data-u="prototype" class="p">
	                    <div class="w">
	                        <div data-u="thumbnailtemplate" class="t"></div>
	                    </div>
	                    <div class="c"></div>
	                </div>
	            </div>
	            <!-- Thumbnail Item Skin End -->
	        </div>
	        <!-- Arrow Navigator -->
	        <span data-u="arrowleft" class="jssora07l" style="top:0px;left:8px;width:50px;height:50px;" data-autocenter="2"></span>
	        <span data-u="arrowright" class="jssora07r" style="top:0px;right:8px;width:50px;height:50px;" data-autocenter="2"></span>
	    </div>
		<!--
		slider
		-->
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