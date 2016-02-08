<?php
	$prefix 		= "create";

	if(isset($displayString) && !empty($displayString) && isset($main_trade_id) && !empty($main_trade_id)) {
		$prefix 				= "update";
	}
?>
<form id="contractor_<?php echo $prefix; ?>_trade_form" name="contractor_<?php echo $prefix; ?>_trade_form" class="inputForm">
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->trade_name'); ?>:</td>
				<td>
					<input type="hidden" name="main_trade_id_db_value" id="main_trade_id_db_value" 
						value="<?php echo isset($main_trade_id) ? $main_trade_id : ""; ?>" />
					<?php
					if($prefix == "create") {
					?>
					<select name="trade_name" id="trade_name" onchange="_contractor_trades.addSubTradesForm(event, this.value);">
						<option value=""><?php echo $this->lang->line_arr('contractor->input_form->main_trade_option_0'); ?></option>
						<?php
						for( $i = 0, $count = count($main_trades_list); $i < $count; $i++ ) {
							echo '<option value = "'.$main_trades_list[$i]->main_trade_id.'">'.$main_trades_list[$i]->main_trade_name.'</option>';
						}
						?>
					</select>
					<?php
					} else if($prefix == "update") {
						echo $displayString;
					}
					?>
				</td>
			</tr>
			<tr class="sub-trade-tr">
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->sub_trade_name'); ?>:</td>
				<td id="sub-trade-container">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="contractor_<?php echo $prefix; ?>_trade_submit" 
							onclick="_contractor_trades.<?php echo $prefix; ?>TradeValidate(<?php echo isset($main_trade_id) ? $main_trade_id : ""; ?>)">
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
