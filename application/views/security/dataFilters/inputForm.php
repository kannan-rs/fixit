<?php
	//Edit Individual
	$i = 0;
$edit = false;
$prefix = "create";
$headerText = "Create Data Filter";

if(isset($dataFilters) && count($dataFilters)) {
	$i = 0;
	$edit = true;
	$prefix = "update";
	$headerText = "Edit Data Filter";
	$individualDataFilter = $dataFilters[0];
}

$sno 				= isset($individualDataFilter) ? $individualDataFilter->sno : "";
$data_filter_id 	= isset($individualDataFilter) ? $individualDataFilter->data_filter_id : "";
$data_filter_name 	= isset($individualDataFilter) ? $individualDataFilter->data_filter_name : "";
$data_filter_descr 	= isset($individualDataFilter) ? $individualDataFilter->data_filter_descr : "";

?>
<h2><?php echo $headerText; ?></h2>
<form id="<?php echo $prefix; ?>_dataFilter_form" name="<?php echo $prefix; ?>_dataFilter_form" class="inputForm">
	<input type="hidden" id='dataFilter_sno' value="<?php echo $sno; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label">Data Filter Id</td>
				<td><input type="text" name="dataFilterId" id="dataFilterId" value="<?php echo $data_filter_id; ?>" required></td>
			</tr>
			<tr>
				<td class="label">Data Filter Name</td>
				<td><input type="text" name="dataFilterName" id="dataFilterName" value="<?php echo $data_filter_name; ?>" required></td>
			</tr>
			<tr>
				<td class="label">Data Filter Description</td>
				<td><textarea rows="6" cols="30" name="dataFilterDescr" id="dataFilterDescr"><?php echo $data_filter_descr; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_dataFilter_submit" onclick="securityObj._dataFilters.<?php echo $prefix; ?>Validate()"><?php echo $prefix; ?> Data Filter</button>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</form>




<!-- Add Data Filter Start -->
<!-- <h2>Create Data Filter</h2>
	<form id="create_dataFilter_form" name="create_dataFilter_form" class="inputForm">
	<div class='form'>
			<div class="label">Data Filter ID:</div>
			<div><input type="text" name="dataFilterId" id="dataFilterId" value="" required></div>
			<div class="label">Data Filter Name:</div>
			<div><input type="text" name="dataFilterName" id="dataFilterName" required></div>
			<div class="label">Data Filter Description:</div>
			<div><textarea rows="6" cols="30" name="dataFilterDescr" id="dataFilterDescr"></textarea></div>
		<p class="button-panel">
			<button type="button" id="create_dataFilter_submit" onclick="securityObj._dataFilters.createValidate()">Create Data Filter</button>
		</p>
	</div>
</form> -->
<!-- Add Data Filter Ends -->