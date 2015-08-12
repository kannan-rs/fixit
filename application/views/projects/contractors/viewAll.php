<h2>Contractors List</h2>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0">
	
	<?php
		if(count($contractors) > 0) {
	?>
			<tr class='heading'>
			<td class='cell'>contractor Name</td>
			<td class='cell'>Company</td>
			<td class='cell'>Type</td>
			<td class='cell'>Status</td>
			</tr>
	<?php
		}

		for($i = 0; $i < count($contractors); $i++) { 
			$contractor = $contractors[$i];
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "projectObj._contractors.deleteRecord(".$contractor->id.")" : "";
	?>
			<tr class='row viewAll'>
				<td class='cell capitalize'>
					<a href="javascript:void(0);" onclick="projectObj._contractors.viewOne('<?php echo $contractors[$i]->id; ?>')">
						<?php echo $contractor->name; ?>
					</a>
				</td>
				<td class="cell capitalize"><?php echo $contractor->name; ?></td>
				<td class="cell capitalize"><?php echo $contractor->company; ?></td>
				<td class="cell capitalize"><?php echo $contractor->type; ?></td>
				<td class="cell capitalize"><?php echo $contractor->status; ?></td>
			</tr>
	<?php
		}
	?>
	</table>
</div>