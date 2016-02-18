<?php
	$prefix 		= "create";

	$db_is_deleted 	= 0;
	$show_form 		= true;

	$db_sub_trade_id 		= "";
	$db_main_trade_id 		= "";
	$db_sub_trade_name 		= "";
	$db_sub_trade_descr 	= "";
	$db_is_deleted 			= "";

	if(isset($master_sub_trade_list) && count($master_sub_trade_list) == 1) {
		$db_sub_trade_id 		= $master_sub_trade_list[0]->sub_trade_id;
		$db_main_trade_id		= $master_sub_trade_list[0]->parent_trade_id;
		$db_sub_trade_name		= $master_sub_trade_list[0]->sub_trade_name;
		$db_sub_trade_descr		= $master_sub_trade_list[0]->sub_trade_description;
		$db_is_deleted			= $master_sub_trade_list[0]->is_deleted;

		if( ($sub_trade_id != $db_sub_trade_id) || ($main_trade_id != $db_main_trade_id) || $db_is_deleted ) {
			$show_form = false;
		}
	}

	if($show_form) {

	if( isset($db_sub_trade_id) && !empty($db_sub_trade_id) ) {
		$prefix = "update";
	}
?>
<form id="master_list_<?php echo $prefix; ?>_sub_trade_form" name="contractor_<?php echo $prefix; ?>_trade_form" class="inputForm">
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->trade_name'); ?>:</td>
				<td>
					<input type="hidden" name="main_trade_id_db_value" id="main_trade_id_db_value" 
						value="<?php echo isset($main_trade_id) ? $main_trade_id : ""; ?>" />
					<input type="hidden" name="sub_trade_id_db_value" id="sub_trade_id_db_value" 
						value="<?php echo isset($sub_trade_id) ? $sub_trade_id : ""; ?>" />
					<input type="text" id="master_sub_trade_name" name="master_sub_trade_name" placeholder="Enter master main trade name" value="<?php echo $db_sub_trade_name; ?>">
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->trade_description'); ?>:</td>
				<td>
					<textarea id="master_sub_trade_descr" name="master_sub_trade_descr" placeholder="Enter master main trade description"><?php echo $db_sub_trade_descr; ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="contractor_<?php echo $prefix; ?>_trade_submit" 
							onclick="_contractor_trades.<?php echo $prefix; ?>_master_sub_trade_validate(<?php echo isset($sub_trade_id) ? $sub_trade_id : ""; ?>)">
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