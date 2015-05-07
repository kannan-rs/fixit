<h2>View Function Details</h3>
<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._functions.createForm()">Create Function</a></div>
<div>
	<!-- List all the Functions from database -->
	<table>
	
	<?php
		if(count($functions) > 0) {
			$i = 0;
			echo "<tr>";
			echo "<td class='cell'>Function ID</td>"."<td class='cell'>".$functions[$i]->fn_id."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell'>Function Name</td>"."<td class='cell'>". $functions[$i]->fn_name ."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell'>Function Description</td>"."<td class='cell'>". $functions[$i]->fn_descr ."</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>
