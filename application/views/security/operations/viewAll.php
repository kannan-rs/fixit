<!-- <div class="create-link">
	<a href="javascript:void(0);" onclick="_operations.createForm()">
		<?php echo $this->lang->line_arr('operation->buttons_links->create'); ?>
	</a>
</div> -->
<h2><?php echo $this->lang->line_arr('operation->headers->view_all'); ?></h2>
<div>
	<!-- List all the operations from database -->
	<table cellspacing="0">
	
	<?php
		if(count($operations) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>".$this->lang->line_arr('operation->summary_table->sno')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('operation->summary_table->operationId')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('operation->summary_table->operationName')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('operation->summary_table->operationDescr')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('operation->summary_table->action')."</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($operations); $i++) { 
			$deleteFn = "_operations.deleteRecord(".$operations[$i]->sno.")";
			echo "<tr class='row'>";
			echo "<td class='cell number'>".($i+1)."</td>";
			echo "<td class='cell number'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"_operations.viewOne('".$operations[$i]->sno."')\">". $operations[$i]->ope_id;
			echo "</td>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"_operations.viewOne('".$operations[$i]->sno."')\">". $operations[$i]->ope_name ."</td>";
			echo "<td>".$operations[$i]->ope_desc."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"_operations.editOperation('".$operations[$i]->sno."')\">".$this->lang->line_arr('operation->buttons_links->edit')."</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$this->lang->line_arr('operation->buttons_links->delete')."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>