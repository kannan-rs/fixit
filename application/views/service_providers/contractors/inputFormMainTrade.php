<?php
	$prefix 		= "create";
	$trade_name_from_db 	= "";
	$trade_id_from_db 		= "";

	if(isset($tradesList) && count($tradesList)) {
		$trade 					= $tradesList[0];
		$prefix 				= "update";
		$trade_name_from_db 	= $trade->trade_name;
		$trade_id_from_db 		= $trade->trade_id;
	}
?>
<form id="contractor_<?php echo $prefix; ?>_trade_form" name="contractor_<?php echo $prefix; ?>_trade_form" class="inputForm">
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->trade_name'); ?>:</td>
				<td>
					<input type="hidden" name="trade_id_db_value" id="trade_id_db_value" value="<?php echo isset($trade_id_from_db) ? $trade_id_from_db : ""; ?>" />
					<input type="text" name="trade_name" id="trade_name" value="<?php echo isset($trade_name_from_db) ? $trade_name_from_db : "";?>" required 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->trade_name_ph'); ?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="contractor_<?php echo $prefix; ?>_trade_submit" 
							onclick="_contractors.<?php echo $prefix; ?>TradeValidate()">
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
