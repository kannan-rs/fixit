<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._operations.createForm()">Create Operation</a></div>
<h2>View All Operations</h2>
<div>
	<!-- List all the operations from database -->
	<table cellspacing="0">
	
	<?php
		if(count($operations) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>Sno</td>";
			echo "<td class='cell'>Operation ID</td>";
			echo "<td class='cell'>Operation Name</td>";
			echo "<td class='cell'>Operation Descr</td>";
			echo "<td class='cell'>Action</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($operations); $i++) { 
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "securityObj._operations.deleteRecord(".$operations[$i]->sno.")" : "";
			echo "<tr class='row'>";
			echo "<td class='cell number'>".($i+1)."</td>";
			echo "<td class='cell number'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"securityObj._operations.viewOne('".$operations[$i]->sno."')\">". $operations[$i]->ope_id;
			echo "</td>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"securityObj._operations.viewOne('".$operations[$i]->sno."')\">". $operations[$i]->ope_name ."</td>";
			echo "<td>".$operations[$i]->ope_desc."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"securityObj._operations.editOperation('".$operations[$i]->sno."')\">Edit</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$deleteText."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>