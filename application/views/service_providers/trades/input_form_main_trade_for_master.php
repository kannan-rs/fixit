<?php
	$prefix 		= "create";

	$db_is_deleted 	= 0;
	$show_form 		= true;

	$db_main_trade_id 		= "";
	$db_main_trade_name 	= "";
	$db_main_trade_descr 	= "";
	$db_is_deleted 			= "";

	if(isset($master_main_trade_list) && count($master_main_trade_list) == 1) {
		$db_main_trade_id 		= $master_main_trade_list[0]->main_trade_id;
		$db_main_trade_name		= $master_main_trade_list[0]->main_trade_name;
		$db_main_trade_descr	= $master_main_trade_list[0]->main_trade_description;
		$db_is_deleted			= $master_main_trade_list[0]->is_deleted;

		if($main_trade_id != $db_main_trade_id || $db_is_deleted ) {
			$show_form = false;
		}
	}

	if($show_form) {

	if( isset($db_main_trade_id) && !empty($db_main_trade_id) ) {
		$prefix = "update";
	}
?>
<form id="master_list_<?php echo $prefix; ?>_main_trade_form" name="contractor_<?php echo $prefix; ?>_trade_form" class="inputForm">
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->trade_name'); ?>:</td>
				<td>
					<input type="hidden" name="main_trade_id_db_value" id="main_trade_id_db_value" 
						value="<?php echo isset($main_trade_id) ? $main_trade_id : ""; ?>" />
					<input type="text" id="master_main_trade_name" name="master_main_trade_name" placeholder="Enter master main trade name" value="<?php echo $db_main_trade_name; ?>">
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->trade_description'); ?>:</td>
				<td>
					<textarea id="master_main_trade_descr" name="master_main_trade_descr" placeholder="Enter master main trade description"><?php echo $db_main_trade_descr; ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="contractor_<?php echo $prefix; ?>_trade_submit" 
							onclick="_contractor_trades.<?php echo $prefix; ?>_master_trade_validate(<?php echo isset($main_trade_id) ? $main_trade_id : ""; ?>)">
								<?php echo $this->lang->line_arr('contractor->buttons_links->'.$prefix."_trade"); ?>
						</button>
						<button type="button" id="cancelButton" 
							onclick="_projects.closeDialog({popupType: ''})">
								<?php echo $this->lang->line_arr('buttons->cancel'); ?>
						</button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>
<?php
	} else {
?>
	<div> Some thing went wrong please try later </div>
<?php
	}
?>