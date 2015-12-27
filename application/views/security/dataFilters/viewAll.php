<!-- <div class="create-link">
	<a href="javascript:void(0);" onclick="_dataFilters.createForm()">
		<?php echo $this->lang->line_arr('data_filter->buttons_links->create'); ?>
	</a>
</div> -->
<h2><?php echo $this->lang->line_arr('data_filter->headers->view_all'); ?></h2>
<div>
	<!-- List all the Data Filters from database -->
	<table cellspacing="0">
	
	<?php
		if(count($dataFilters) > 0) {
			echo "<tr class='heading'>";
			echo "<td class='cell'>".$this->lang->line_arr('data_filter->summary_table->sno')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('data_filter->summary_table->dataFilterId')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('data_filter->summary_table->dataFilterName')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('data_filter->summary_table->dataFilterDescr')."</td>";
			echo "<td class='cell'>".$this->lang->line_arr('data_filter->summary_table->action')."</td>";
			echo "</tr>";
		}

		for($i = 0; $i < count($dataFilters); $i++) { 
			$deleteFn = "_dataFilters.deleteRecord(".$dataFilters[$i]->sno.")";
			echo "<tr class='row'>";
			echo "<td class='cell number'>".($i+1)."</td>";
			echo "<td class='cell number'>";
			echo "<a href=\"javascript:void(0);\" onclick=\"_dataFilters.viewOne('".$dataFilters[$i]->sno."')\">". $dataFilters[$i]->data_filter_id;
			echo "</td>";
			echo "<td class='cell'><a href=\"javascript:void(0);\" onclick=\"_dataFilters.viewOne('".$dataFilters[$i]->sno."')\">". $dataFilters[$i]->data_filter_name ."</td>";
			echo "<td>".$dataFilters[$i]->data_filter_descr."</td>";
			echo "<td class='cell table-action'>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"_dataFilters.editDataFilter('".$dataFilters[$i]->sno."')\">".$this->lang->line_arr('data_filter->buttons_links->edit')."</a></span>";
			echo "<span><a href=\"javascript:void(0);\" onclick=\"".$deleteFn."\">".$this->lang->line_arr('data_filter->buttons_links->delete')."</a></span></td>";
			echo "</tr>";
		}
	?>
	</table>
</div>