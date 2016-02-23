<?php
if(isset($discountList) && count($discountList)) {
?>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="discount-table-list">
		<tr class='heading'>
			<td>Details</td>
			<td>Value</td>
			<td>From &nbsp; To date</td>
			<td>Available on Zip Code</td>
			<td>Actions</td>
		</tr>
<?php
	for($i=0; $i < count($discountList); $i++) {
		$discount = $discountList[$i];

		$mainTradeText 	= 	isset($discount->discount_for_trade_id) && 
							$discount->discount_for_trade_id != "0" &&
							isset($main_trades) &&
							isset($main_trades[$discount->discount_for_trade_id]) ? $main_trades[$discount->discount_for_trade_id]->main_trade_name : "All";

		$subTradeText	= 	$mainTradeText != "all" && 
							isset($discount->discount_for_sub_trade_id) && 
							isset($sub_trades) &&
							isset($sub_trades[$discount->discount_for_sub_trade_id]) &&
							$discount->discount_for_sub_trade_id != "0" ? $sub_trades[$discount->discount_for_sub_trade_id]->sub_trade_name : "All";
							
		$mainTradeId 	= $discount->discount_for_trade_id;
		$subTradeId 	= $discount->discount_for_sub_trade_id;
		$name			= $discount->discount_name;
		$descr			= $discount->discount_descr;
		$zips			= $discount->discount_for_zip;
		$type			= $discount->discount_type;
		$value			= $discount->discount_value;
		$from_date		= $discount->discount_from_date_for_view;
		$to_date		= $discount->discount_to_date_for_view;
		$img 			= $discount->discount_image;

		//$maintradeCSS 	= $mainTradeText != "all" ? "mainTrade_".$discount->discount_for_trade_id : "";
	?>
		<tr class="oneDiscount" data-main-trade-id="<?php echo $mainTradeId; ?>" data-sub-trade-id="<?php echo $subTradeId; ?>">
			<td><?php echo $name." - ".$descr; ?></td>
			<td>
			<?php echo $value.($type == "%" ? "%" : "$"); ?>
			</td>
			<td>
			<?php
				if(isset($from_date) && $from_date != "" && trim($from_date) != "00-00-00") {
					echo $from_date;
				}
				if(isset($to_date) && $to_date != "" && trim($to_date) != "00-00-00") {
					if(isset($from_date) && $from_date != "") {
						echo " - ";
					} else {
						echo "Till - ";
					}
					echo $to_date;
				} else if(isset($from_date) && $from_date != "" && trim($from_date) != "00-00-00") {
					echo " - ** ";
				} else {
					echo "All Day";
				}
			?></td>
			<td> 
				<?php
				if(isset($zips) && trim($zips) != "") {
					echo trim($zips);
				} else {
					echo "All";
				}
				?>
			</td>
			<td>
				<div class="header-options">
					<span class="options-icon">
						<span>
							<a class="step fi-page-edit size-21" href="javascript:void(0);" 
								onclick="_contractor_discounts.editForm(event, <?php echo $discount->discount_id?>)" 
								title="<?php echo $this->lang->line_arr('contractor->buttons_links->edit_discount'); ?>">
							</a>
							<a class="step fi-deleteRow size-21 accordion-icon icon-right red delete" 
								href="javascript:void(0);" 
								onclick="_contractor_discounts.delete(event, <?php echo $discount->discount_id?>);" 
								title="<?php echo $this->lang->line_arr('contractor->buttons_links->delete_discount'); ?>">
							</a>
						</span>
					</span>
				</div>
			</td>
		</tr>
	<?php
	}
?>
</table>
<?php
} else {
?>
	<div>No Discount added</div>
<?php
}
?>