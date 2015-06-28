<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._functions.createForm()">Create Function</a></div>
<h2>View Function Details</h2>
<div class="form">
	<!-- List all the Functions from database -->
	<?php
		if(count($functions) > 0) {
			$i = 0;
			
			echo "<div class='cell label'>Function ID</div>"."<div class='cell'>".$functions[$i]->fn_id."</div>";
			echo "<div class='cell label'>Function Name</div>"."<div class='cell'>". $functions[$i]->fn_name ."</div>";
			echo "<div class='cell label'>Function Description</div>"."<div class='cell'>". $functions[$i]->fn_descr ."</div>";
			
		}
	?>
</div>
