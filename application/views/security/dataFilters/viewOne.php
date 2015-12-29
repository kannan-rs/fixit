<!-- <div class="create-link">
	<a href="javascript:void(0);" onclick="_dataFilters.createForm()">
		<?php echo $this->lang->line_arr('data_filter->buttons_links->create'); ?>
	</a>
</div> -->
<h2><?php echo $this->lang->line_arr('data_filter->headers->view_one'); ?></h2>
<div class="form">
	<!-- List all the Data Filters from database -->
	<?php
		if(count($dataFilters) > 0) {
			$i = 0;
			echo "<div class='cell label'>".$this->lang->line_arr('data_filter->details_view->dataFilterId')."</div>"."<div class='cell'>".$dataFilters[$i]->data_filter_id."</div>";
			echo "<div class='cell label'>".$this->lang->line_arr('data_filter->details_view->dataFilterName')."</div>"."<div class='cell'>". $dataFilters[$i]->data_filter_name ."</div>";
			echo "<div class='cell label'>".$this->lang->line_arr('data_filter->details_view->dataFilterDescr')."</div>"."<div class='cell'>". $dataFilters[$i]->data_filter_descr ."</div>";
		}
	?>
</div>