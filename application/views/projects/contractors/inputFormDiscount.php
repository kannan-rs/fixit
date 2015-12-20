<?php
	$edit = false;
	$prefix = "create";

	$discount  = isset($discountList) && is_array($discountList) ? $discountList[0] : null;

	if($discount) {
		$edit = true;
		$prefix = "update";

		$id 						= $discount->discount_id;
		$name 						= $discount->discount_name;
		$descr 						= $discount->discount_descr;
		$for_contractor_id 			= $discount->discount_for_contractor_id;
		$main_trade_id 				= $discount->discount_for_trade_id;
		$sub_trade_id 				= $discount->discount_for_sub_trade_id;
		$type						= $discount->discount_type;
		$value						= $discount->discount_value;
		$for_zip 					= $discount->discount_for_zip;
		$from_date 					= $discount->discount_from_date_input_box;
		$to_date 					= $discount->discount_to_date_input_box;
		$image 						= $discount->discount_image;
		$is_deleted 				= $discount->is_deleted;
	}
?>

<form id="<?php echo $prefix; ?>_discount_contractor_form" name="<?php echo $prefix; ?>_discount_contractor_form" class="inputForm">
	
	<input type="hidden" id='contractorId' value="<?php echo isset($contractor_id) ? $contractor_id : ""; ?>" />
	<input type="hidden" name="selectedMainTradeId" id="selectedMainTradeId" value="<?php echo isset($main_trade_id) ? $main_trade_id : ""; ?>">
	<input type="hidden" name="selectedSubTradeId" id="selectedSubTradeId" value="<?php echo isset($sub_trade_id) ? $sub_trade_id : ""; ?>" />
	<input type="hidden" name="selected_discount_type" id="selected_discount_type" value="<?php echo isset($type) ? $type : ""; ?>" />
	<input type="hidden" name = "dbDiscountId" id= "dbDiscountId" value="<?php echo isset($id) ? $id : ""; ?>" />

	<table class='form'>
		<tr>
			<td>Main Trade</td>
			<td>
				<select id="input_discount_for_main_trade" onchange="_contractors.populateSubTradeInDiscount(this.value, 'input_discount_for_sub_trade')">
					<option><?php echo $this->lang->line_arr('contractor->input_form->main_trade_option_0'); ?></option>
				</select>
			</td>
			<td>Sub Trade</td>
			<td>
				<select id="input_discount_for_sub_trade">
					<option><?php echo $this->lang->line_arr('contractor->input_form->sub_trade_option_0'); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				Select main trade and sub trade to add new discount
			</td>
		</tr>
	</table>
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->discount_name'); ?>:</td>
				<td>
					<input type="text" name="discount_name" id="discount_name" value="<?php echo isset($name) ? $name : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->discount_name_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->discount_value'); ?>:</td>
				<td>
					<input type="radio" name="discount_type" value="%" <?php echo !isset($type) || $type == "%" || $type == "" ? "checked" : ""; ?>>Percentage (%)
					<input type="radio" name="discount_type" value="$" <?php echo isset($type) && $type == "$" ? "checked" : ""; ?>>Dollers ($)<br/>
					<input type="text" name="discount_value" id="discount_value" value="<?php echo isset($value) ? $value : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->discount_value_ph'); ?>" required>
				</td>
			</tr>
			type
			value
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('contractor->input_form->discount_descr'); ?></td>
				<td>
					<textarea type="text" name="discount_descr" id="discount_descr" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->discount_descr_ph'); ?>" ><?php echo isset($descr) ? $descr : "";?></textarea>
				</td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('contractor->input_form->discount_for_zip'); ?></td>
				<td>
					<textarea type="text" name="discount_for_zip" id="discount_for_zip" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->discount_for_zip_ph'); ?>" ><?php echo isset($for_zip) ? $for_zip : "";?></textarea>
				</td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('contractor->input_form->discount_from_date'); ?>:</td>
				<td>
					<input type="text" name="discount_from_date" id="discount_from_date" value="<?php echo isset($from_date) ? $from_date : "";?>" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->discount_from_date_ph'); ?>" >
				</td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('contractor->input_form->discount_to_date'); ?>:</td>
				<td>
					<input type="text" name="discount_to_date" id="discount_to_date" value="<?php echo isset($to_date) ? $to_date : "";?>" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->discount_to_date_ph'); ?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_contractor_submit" onclick="_contractors.<?php echo $prefix; ?>DiscountValidate()"><?php echo $this->lang->line_arr('contractor->buttons_links->'.$prefix."_discount"); ?></button>
						
						<button type="button" id="cancelButton" onclick="_projects.closeDialog()"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
						
						<button type="reset" id="resetButton" onclick=""><?php echo $this->lang->line_arr('buttons->reset'); ?></button>
						
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>
