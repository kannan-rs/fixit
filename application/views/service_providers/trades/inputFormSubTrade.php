<?php
if(count($sub_trades_list)) {
?>
<table class='form'>
	<tbody>
		<?php
		$existingList = "";
		for($i = 0, $count = count($sub_trades_list); $i < $count; $i++) {
			$checked = isset($selected_sub_trade_list[$sub_trades_list[$i]->sub_trade_id]) ? "checked" : "";
			if($checked) {
				$existingList .= !empty($existingList) ? "," : "";
				$existingList .= $sub_trades_list[$i]->sub_trade_id;
			}
		?>
		<tr>
			<td>
				<input type="checkbox" name="sub_trades" <?php echo $checked;?>
					id="<?php echo $sub_trades_list[$i]->sub_trade_id; ?>" 
					value="<?php echo $sub_trades_list[$i]->sub_trade_name; ?>" >
					<?php echo $sub_trades_list[$i]->sub_trade_name; ?>
			</td>
		</tr>
		<?php
		}
		?>
		<input type="hidden" id="existingSubList" value="<?php echo $existingList; ?>">
	</tbody>
</table>
<?php
}
?>