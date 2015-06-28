<div class="create-link"><a href="javascript:void(0);" onclick="securityObj._operations.createForm()">Create Operation</a></div>
<h2>View Operation Details</h2>
<div class="form">
	<!-- List all the operations from database -->
	<?php
		if(count($operations) > 0) {
			$i = 0;
			echo "<div class='cell label'>Operation ID</div>"."<div class='cell'>".$operations[$i]->ope_id."</div>";
			echo "<div class='cell label'>Operation Name</div>"."<div class='cell'>". $operations[$i]->ope_name ."</div>";
			echo "<div class='cell label'>Operation Description</div>"."<div class='cell'>". $operations[$i]->ope_desc ."</div>";
		}
	?>
</div>