<!-- <div class="create-link">
	<a href="javascript:void(0);" onclick="_functions.createForm()">
		<?php echo $this->lang->line_arr('function->buttons_links->create'); ?>
	</a>
</div> -->
<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('function->headers->view_all'); ?></h2>
</div>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0">
	
	<?php
		if(count($functions) > 0) {
			echo "<tr class='heading'>";
			//echo "<td class='cell'>".$this->lang->line_arr('function->summary_table->sno')."</td>";
			/*echo "<td class='cell'>".$this->lang->line_arr('function->summary_table->functionId')."</td>";*/
			echo "<td class='cell'>".$this->lang->line_arr('function->summary_table->functionName')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('function->summary_table->functionDescr')."</td>";
			echo "<td class='cell table-action'>".$this->lang->line_arr('function->summary_table->action')."</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($functions); $i++) { 
			$deleteFn = "_functions.deleteRecord(".$functions[$i]->sno.")";
			echo "<tr class='row'>";
			//echo "<td class='cell number'>".($i+1)."</td>";
			/*echo "<td class='cell number'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"_functions.viewOne('".$functions[$i]->sno."')\">". $functions[$i]->fn_id;
			echo "</td>";*/
			//echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"_functions.viewOne('".$functions[$i]->sno."')\">". $functions[$i]->fn_name ."</td>";
			echo "<td class='cell'><a href=\"/main/security/functions/viewone/".$functions[$i]->sno."\">". $functions[$i]->fn_name ."</td>";
			echo "<td class='cell description'>".$functions[$i]->fn_descr."</td>";
			echo "<td class='cell table-action'>";
			//echo "<span><a href=\"javascript:void(0);\" onclick=\"_functions.editFunction('".$functions[$i]->sno."')\">".$this->lang->line_arr('function->buttons_links->edit')."</a></span>";
			echo "<span><a href=\"/main/security/functions/edit/".$functions[$i]->sno."\" >".$this->lang->line_arr('function->buttons_links->edit')."</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$this->lang->line_arr('function->buttons_links->delete')."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>