<div class="create-link">
	<a href="javascript:void(0);" onclick="_roles.createForm()">
		<?php echo $this->lang->line_arr('role->buttons_links->create'); ?>
	</a>
</div>
<h2><?php echo $this->lang->line_arr('role->headers->view_all'); ?></h2>
<div>
	<!-- List all the Roles from database -->
	<table cellspacing="0">
	
	<?php
		if(count($roles) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>".$this->lang->line_arr('role->summary_table->sno')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('role->summary_table->roleId')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('role->summary_table->roleName')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('role->summary_table->roleDescr')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('role->summary_table->action')."</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($roles); $i++) { 
			$deleteFn = "_roles.deleteRecord(".$roles[$i]->sno.")";
			echo "<tr class='row'>";
			echo "<td class='cell number'>".($i+1)."</td>";
			echo "<td class='cell'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"_roles.viewOne('".$roles[$i]->sno."')\">". $roles[$i]->role_id;
			echo "</td>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"_roles.viewOne('".$roles[$i]->sno."')\">". $roles[$i]->role_name ."</td>";
			echo "<td>".$roles[$i]->role_desc."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"_roles.editRole('".$roles[$i]->sno."')\">".$this->lang->line_arr('role->buttons_links->edit')."</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$this->lang->line_arr('role->buttons_links->delete')."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>