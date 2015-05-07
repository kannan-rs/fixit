<h2>View Role Details</h3>
<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._roles.createForm()">Create Role</a></div>
<div>
	<!-- List all the Roles from database -->
	<table>
	
	<?php
		if(count($roles) > 0) {
			$i = 0;
			echo "<tr>";
			echo "<td class='cell'>Role ID</td>"."<td class='cell'>".$roles[$i]->role_id."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell'>Role Name</td>"."<td class='cell'>". $roles[$i]->role_name ."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell'>Role Description</td>"."<td class='cell'>". $roles[$i]->role_desc ."</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>