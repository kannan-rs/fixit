<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._roles.createForm()">Create Role</a></div>
<h2>View Role Details</h2>
<div>
	<!-- List all the Roles from database -->
	<table cellspacing="0">
	
	<?php
		if(count($roles) > 0) {
			$i = 0;
			echo "<tr>";
			echo "<td class='cell label'>Role ID</td>"."<td class='cell'>".$roles[$i]->role_id."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell label'>Role Name</td>"."<td class='cell'>". $roles[$i]->role_name ."</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td class='cell label'>Role Description</td>"."<td class='cell'>". $roles[$i]->role_desc ."</td>";
			echo "</tr>";
		}
	?>
	</table>
</div>