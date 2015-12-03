<div class="create-link">
	<a href="javascript:void(0);" onclick="_operations.createForm()">
		<?php echo $this->lang->line_arr('operation->buttons_links->create'); ?>
	</a>
</div>
<h2><?php echo $this->lang->line_arr('operation->headers->view_one')?></h2>
<div class="form">
	<!-- List all the operations from database -->
	<?php
		if(count($operations) > 0) {
			$i = 0;
			echo "<div class='cell label'>".$this->lang->line_arr('operation->details_view->operationId')."</div>"."<div class='cell'>".$operations[$i]->ope_id."</div>";
			echo "<div class='cell label'>".$this->lang->line_arr('operation->details_view->operationName')."</div>"."<div class='cell'>". $operations[$i]->ope_name ."</div>";
			echo "<div class='cell label'>".$this->lang->line_arr('operation->details_view->operationDescr')."</div>"."<div class='cell'>". $operations[$i]->ope_desc ."</div>";
		}
	?>
</div>