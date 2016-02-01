<!-- <div class="create-link">
	<a href="javascript:void(0);" onclick="_roles.createForm()">
		<?php echo $this->lang->line_arr('role->buttons_links->create'); ?>
	</a>
</div> -->
<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('role->headers->view_one'); ?></h2>
</div>
<div class='form'>
	<!-- List all the Roles from database -->
	<?php
		if(count($roles) > 0) {
			$i = 0;
			
			/*echo "<div class='label'>".$this->lang->line_arr('role->details_view->roleId')."</div>";
			echo "<div>".$roles[$i]->role_id."</div>";*/
			echo "<div class='label'>".$this->lang->line_arr('role->details_view->roleName')."</div>";
			echo "<div>". $roles[$i]->role_name ."</div>";
			echo "<div class='label'>".$this->lang->line_arr('role->details_view->roleDescr')."</div>";
			echo "<div>". $roles[$i]->role_desc ."</div>";
		}
	?>
</div>