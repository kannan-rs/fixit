<h2>Partners List</h2>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0">
	
	<?php
		if(count($partners) > 0) {
	?>
			<tr class='heading'>
			<td class='cell'>partner Name</td>
			<td class='cell'>Company</td>
			<td class='cell'>Type</td>
			<td class='cell'>Status</td>
			</tr>
	<?php
		}

		for($i = 0; $i < count($partners); $i++) { 
			$partner = $partners[$i];
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "projectObj._partners.deleteRecord(".$partner->id.")" : "";
	?>
			<tr class='row viewAll'>
				<td class='cell capitalize'>
					<a href="javascript:void(0);" onclick="projectObj._partners.viewOne('<?php echo $partners[$i]->id; ?>')">
						<?php echo $partner->name; ?>
					</a>
				</td>
				<td class="cell capitalize"><?php echo $partner->company_name; ?></td>
				<td class="cell capitalize"><?php echo $partner->type; ?></td>
				<td class="cell capitalize"><?php echo $partner->status; ?></td>
			</tr>
	<?php
		}
	?>
	</table>
</div>