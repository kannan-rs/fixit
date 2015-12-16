<?php
	$prefix 		= "create";
	$sub_trade_name_from_db 	= "";
	$sub_trade_id_from_db 		= "";

	if(isset($tradesList) && count($tradesList)) {
		$trade 					= $tradesList[0];
		$prefix 				= "update";
		$sub_trade_name_from_db 	= $trade->trade_name;
		$sub_trade_id_from_db 		= $trade->trade_id;
	}
?>
<form id="contractor_<?php echo $prefix; ?>_sub_trade_form" name="contractor_<?php echo $prefix; ?>_sub_trade_form" class="inputForm">
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->sub_trade_name'); ?>:</td>
				<td>
					<input type="hidden" name="main_trade_id_db_value" id="main_trade_id_db_value" value="<?php echo isset($main_trade_id) ? $main_trade_id : ""; ?>" />
					<input type="hidden" name="sub_trade_id_db_value" id="sub_trade_id_db_value" value="<?php echo isset($sub_trade_id_from_db) ? $sub_trade_id_from_db : ""; ?>" />
					<input type="text" name="sub_trade_name" id="sub_trade_name" value="<?php echo isset($sub_trade_name_from_db) ? $sub_trade_name_from_db : "";?>" required 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->sub_trade_name_ph'); ?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="contractor_<?php echo $prefix; ?>_sub_trade_submit" 
							onclick="_contractors.<?php echo $prefix; ?>SubTradeValidate()">
								<?php echo $this->lang->line_arr('contractor->buttons_links->'.$prefix."_sub_trade"); ?>
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
