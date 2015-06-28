<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._roles.createForm()">Create Role</a></div>
<h2>View Role Details</h2>
<div class='form'>
	<!-- List all the Roles from database -->
	<?php
		if(count($roles) > 0) {
			$i = 0;
			
			echo "<div class='label'>Role ID</div>";
			echo "<div>".$roles[$i]->role_id."</div>";
			echo "<div class='label'>Role Name</div>";
			echo "<div>". $roles[$i]->role_name ."</div>";
			echo "<div class='label'>Role Description</div>";
			echo "<div>". $roles[$i]->role_desc ."</div>";
		}
	?>
</div>