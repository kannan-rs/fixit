<?php 
	if(!is_logged_in() && $page != "signup" && $page != "login") {
?>
	<!-- SIDEBAR -->
	<aside>
		<?php /* echo $login_form;*/ ?>
		<!-- <ul>
			<li>
				<a class="signup" href="<?php echo $baseUrl; ?>main/signup">Click here</a> for registering youself to "thefixitnetwork".
			</li>
		</ul> -->
		<ul>	
			<li>
				<h4>News</h4>
				<ul>
					<li class="newsitem">
						<span class="headline"><a href="#">Sed tortor neque</a></span>
						<span class="date">August 09 2009</span>
						<span class="extract">Sed tortor neque, interdum nec aliquam id, egestas quis augue. Phasellus bibendum pellentesque massa eu pretium.</span>	
						<span class="morelink"><a href="#">Continue reading &raquo;</a></span>
					</li>

				</ul>
			</li>
			
			<li>
				<div class="greybox">
					<p>A simple grey box. Sed tortor neque, interdum nec aliquam id, egestas quis augue. Phasellus bibendum pellentesque massa eu pretium.</p>
				</div>
				<div class="bluebox">
					<p>A simple blue box. Sed tortor neque, interdum nec aliquam id, egestas quis augue. Phasellus bibendum pellentesque massa eu pretium.</p>
				</div>
			</li>
		</ul>
	</aside>
<?php
	}
?>

<!-- SIDEBAR -->
