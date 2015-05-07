<h2>View All Roles</h3>
<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._roles.createForm()">Create Role</a></div>
<div>
	<!-- List all the Roles from database -->
	<table>
	
	<?php
		if(count($roles) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>Sno</td>";
			echo "<td class='cell'>Role ID</td>";
			echo "<td class='cell'>Role Name</td>";
			echo "<td class='cell'>Role Descr</td>";
			echo "<td class='cell'>Action</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($roles); $i++) { 
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "securityObj._roles.delete(".$roles[$i]->sno.")" : "";
			echo "<tr class='row'>";
			echo "<td class='cell'>".($i+1)."</td>";
			echo "<td class='cell'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"securityObj._roles.viewOne('".$roles[$i]->sno."')\">". $roles[$i]->role_id;
			echo "</td>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"securityObj._roles.viewOne('".$roles[$i]->sno."')\">". $roles[$i]->role_name ."</td>";
			echo "<td>".$roles[$i]->role_desc."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"securityObj._roles.editRole('".$roles[$i]->sno."')\">Edit</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$deleteText."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>