<!-- <div class="create-link">
	<a href="javascript:void(0);" onclick="_users.createForm()">
		<?php echo $this->lang->line_arr('user->buttons_links->create'); ?>
	</a>
</div> -->
<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('user->headers->view_all'); ?></h2>
</div>

<?php echo $noticeFile; ?>

<div>
	<!-- List all the users from database -->
	<table cellspacing="0">
	
	<?php
		if(count($users) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>".$this->lang->line_arr('user->summary_table->user_name')."</td>";
			/*echo "<td class='cell'>".$this->lang->line_arr('user->summary_table->belongs_to')."</td>";*/
			echo "<td class='cell'>".$this->lang->line_arr('user->summary_table->role')."</td>";
			echo "<td class='cell table-action'>".$this->lang->line_arr('user->summary_table->actions')."</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($users); $i++) { 
			$deleteText = ( $users[$i]->role_id == $admin_role_id ) ? "" : $this->lang->line_arr('user->buttons_links->delete');
			$deleteFn = $deleteText ? "_users.deleteRecord(".$users[$i]->sno.", '".$users[$i]->user_name."')" : "";
			echo "<tr class='row'>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"_users.viewOne({ userId: ".$users[$i]->sno."})\">". $users[$i]->user_name ."</td>";
			/*echo "<td class='cell capitalize'>". (!empty($users[$i]->belongs_to) ? $users[$i]->belongs_to : "-NA-") ."</td>";*/
			echo "<td class='cell capitalize'>". $users[$i]->role_disp_name ."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"_users.editUser('".$users[$i]->sno."')\">".$this->lang->line_arr('user->buttons_links->edit')."</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$deleteText."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>