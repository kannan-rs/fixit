<h2>Contractors List</h2>
<div class="contractors internal-tab-as-links" onclick="projectObj._contractors.showContractorsList(event)">
	<a href="javascript:void(0);" data-option="active" title="Click on this button to view active contractors">Active</a>
	<a href="javascript:void(0);" data-option="inactive" title="Click on this button to view in-active contractors">InActive</a>
	<?php if($account_type == "admin") { ?>
		<!-- <a href="javascript:void(0);" data-option="deleted">Deleted</a> -->
	<?php } ?>
	<a href="javascript:void(0);" data-option="all" title="Click on this button to view all contractors">All</a>
</div>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="contractors-table-list">
	
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

			$cssStatus = $contractor->status == "inactive" ? "inactive" : "active";
	?>
			<tr class='row viewAll <?php echo $cssStatus; ?>'>
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