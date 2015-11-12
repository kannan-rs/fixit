<?php
	//Edit Individual
	$i = 0;
$edit = false;
$prefix = "create";

if(isset($dataFilters) && count($dataFilters)) {
	$i = 0;
	$edit = true;
	$prefix = "update";
	$individualDataFilter = $dataFilters[0];
}

$sno 				= isset($individualDataFilter) ? $individualDataFilter->sno : "";
$data_filter_id 	= isset($individualDataFilter) ? $individualDataFilter->data_filter_id : "";
$data_filter_name 	= isset($individualDataFilter) ? $individualDataFilter->data_filter_name : "";
$data_filter_descr 	= isset($individualDataFilter) ? $individualDataFilter->data_filter_descr : "";

?>
<h2><?php echo $this->lang->line_arr('data_filter->headers->'.$prefix); ?></h2>
<form id="<?php echo $prefix; ?>_dataFilter_form" name="<?php echo $prefix; ?>_dataFilter_form" class="inputForm">
	<input type="hidden" id='dataFilter_sno' value="<?php echo $sno; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('data_filter->input_form->dataFilterId'); ?></td>
				<td><input type="text" name="dataFilterId" id="dataFilterId" value="<?php echo $data_filter_id; ?>" placeholder="<?php echo $this->lang->line_arr('data_filter->input_form->dataFilterId_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('data_filter->input_form->dataFilterName'); ?></td>
				<td><input type="text" name="dataFilterName" id="dataFilterName" value="<?php echo $data_filter_name; ?>" placeholder="<?php echo $this->lang->line_arr('data_filter->input_form->dataFilterName_ph'); ?>" required></td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('data_filter->input_form->dataFilterDescr'); ?></td>
				<td><textarea rows="6" cols="30" name="dataFilterDescr" id="dataFilterDescr" placeholder="<?php echo $this->lang->line_arr('data_filter->input_form->dataFilterDescr_ph'); ?>"><?php echo $data_filter_descr; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_dataFilter_submit" onclick="securityObj._dataFilters.<?php echo $prefix; ?>Validate()"><?php echo $this->lang->line_arr('data_filter->buttons_links->'.$prefix); ?></button>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</form>