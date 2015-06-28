<?php
	//Edit Individual
	$i = 0;
?>
<h2>Edit Data Filter</h2>
<form id="update_dataFilter_form" name="update_dataFilter_form">
	<input type="hidden" id='dataFilter_sno' value="<?php echo $dataFilters[$i]->sno; ?>" />
	<div class='form'>
			<div class="label">Data Filter Id</div>
			<div><input type="text" name="dataFilterId" id="dataFilterId" value="<?php echo $dataFilters[$i]->data_filter_id; ?>" required></div>
			<div class="label">Data Filter Name</div>
			<div><input type="text" name="dataFilterName" id="dataFilterName" value="<?php echo $dataFilters[$i]->data_filter_name; ?>" required></div>
			<div class="label">Data Filter Description</div>
			<div><textarea rows="6" cols="30" name="dataFilterDescr" id="dataFilterDescr"><?php echo $dataFilters[$i]->data_filter_descr; ?></textarea></div>
		<p class="button-panel">
			<button type="button" id="update_dataFilter_submit" onclick="securityObj._dataFilters.updateValidate()">Update Data Filter</button>
		</p>
	</div>
</form>