<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._users.createForm()">Create User</a></div>
<h2>View All Users</h2>
<div>
	<!-- List all the users from database -->
	<table cellspacing="0">
	
	<?php
		if(count($users) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>Sno</td>";
			echo "<td class='cell'>User Name</td>";
			echo "<td class='cell'>Privilege</td>";
			echo "<td class='cell'>Actions</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($users); $i++) { 
			$deleteText = ($users[$i]->account_type =='admin') ?"":"Delete";
			$deleteFn = $deleteText ? "securityObj._users.deleteRecord(".$users[$i]->sno.")" : "";
			echo "<tr class='row'>";
			echo "<td class='cell number'>".($i+1)."</td>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"securityObj._users.viewOne('".$users[$i]->sno."')\">". $users[$i]->user_name ."</td>";
			echo "<td class='cell'>". $users[$i]->account_type ."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"securityObj._users.editUser('".$users[$i]->sno."')\">Edit</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$deleteText."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>