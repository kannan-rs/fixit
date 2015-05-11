<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._functions.createForm()">Create Function</a></div>
<h2>View Function Details</h2>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0">
	
	<?php
		if(count($functions) > 0) {
			$i = 0;
			echo "<tr>";
			echo "<td class='cell label'>Function ID</td>"."<td class='cell'>".$functions[$i]->fn_id."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell label'>Function Name</td>"."<td class='cell'>". $functions[$i]->fn_name ."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell label'>Function Description</td>"."<td class='cell'>". $functions[$i]->fn_descr ."</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>
