<!-- Add Data Filter Start -->
<h2>Create Data Filter</h2>
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
</form>
<!-- Add Data Filter Ends -->