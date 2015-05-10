<h2>View All Functions</h3>
<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._functions.createForm()">Create Function</a></div>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0">
	
	<?php
		if(count($functions) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>Sno</td>";
			echo "<td class='cell'>Function ID</td>";
			echo "<td class='cell'>Function Name</td>";
			echo "<td class='cell'>Function Descr</td>";
			echo "<td class='cell'>Action</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($functions); $i++) { 
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "securityObj._functions.delete(".$functions[$i]->sno.")" : "";
			echo "<tr class='row'>";
			echo "<td class='cell number'>".($i+1)."</td>";
			echo "<td class='cell number'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"securityObj._functions.viewOne('".$functions[$i]->sno."')\">". $functions[$i]->fn_id;
			echo "</td>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"securityObj._functions.viewOne('".$functions[$i]->sno."')\">". $functions[$i]->fn_name ."</td>";
			echo "<td>".$functions[$i]->fn_descr."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"securityObj._functions.editFunction('".$functions[$i]->sno."')\">Edit</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$deleteText."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>