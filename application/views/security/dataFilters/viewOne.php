<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._dataFilters.createForm()">Create Data Filter</a></div>
<h2>View Data Filter Details</h2>
<div class="form">
	<!-- List all the Data Filters from database -->
	<?php
		if(count($dataFilters) > 0) {
			$i = 0;
			echo "<div class='cell label'>Data Filter ID</div>"."<div class='cell'>".$dataFilters[$i]->data_filter_id."</div>";
			echo "<div class='cell label'>Data Filter Name</div>"."<div class='cell'>". $dataFilters[$i]->data_filter_name ."</div>";
			echo "<div class='cell label'>Data Filter Description</div>"."<div class='cell'>". $dataFilters[$i]->data_filter_descr ."</div>";
		}
	?>
</div>