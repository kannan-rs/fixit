<div class="create-link">
	<a href="javascript:void(0);" onclick="_functions.createForm()">
		<?php echo $this->lang->line_arr('function->buttons_links->create'); ?>
	</a>
</div>
<h2><?php echo $this->lang->line_arr('function->headers->view_one'); ?></h2>
<div class="form">
	<!-- List all the Functions from database -->
	<?php
		if(count($functions) > 0) {
			$i = 0;
			
			echo "<div class='cell label'>".$this->lang->line_arr('function->details_view->functionId')."</div>"."<div class='cell'>".$functions[$i]->fn_id."</div>";
			echo "<div class='cell label'>".$this->lang->line_arr('function->details_view->functionName')."</div>"."<div class='cell'>". $functions[$i]->fn_name ."</div>";
			echo "<div class='cell label'>".$this->lang->line_arr('function->details_view->functionDescr')."</div>"."<div class='cell'>". $functions[$i]->fn_descr ."</div>";
			
		}
	?>
</div>
