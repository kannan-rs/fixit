<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('contractor->headers->view_all'); ?></h2>
	<div class="contractors internal-tab-as-links" onclick="_contractors.showContractorsList(event)">
		<a href="javascript:void(0);" data-option="active" title="<?php echo $this->lang->line_arr('contractor->buttons_links->active_hover_text'); ?>">
			<?php echo $this->lang->line_arr('contractor->buttons_links->active'); ?>
		</a>
		<a href="javascript:void(0);" data-option="inactive" title="<?php echo $this->lang->line_arr('contractor->buttons_links->in_active_hover_text'); ?>">
			<?php echo $this->lang->line_arr('contractor->buttons_links->in_active'); ?>
		</a>
		<?php if($role_disp_name == "admin") { ?>
			<!-- <a href="javascript:void(0);" data-option="deleted">
				<?php echo $this->lang->line_arr('contractor->buttons_links->deleted'); ?>
			</a> -->
		<?php } ?>
		<a href="javascript:void(0);" data-option="all" title="<?php echo $this->lang->line_arr('contractor->buttons_links->all_hover_text'); ?>">
			<?php echo $this->lang->line_arr('contractor->buttons_links->all'); ?>
		</a>
	</div>
</div>

<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="contractors-table-list">
	
	<?php
		if(count($contractors) > 0) {
	?>
			<tr class='heading'>
			<!-- <td class='cell'><?php echo $this->lang->line_arr('contractor->summary_table->contractor_name'); ?></td> -->
			<td class='cell'><?php echo $this->lang->line_arr('contractor->summary_table->company'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('contractor->summary_table->service_area'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('contractor->summary_table->type'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('contractor->summary_table->status'); ?></td>
			</tr>
	<?php
		}

		for($i = 0; $i < count($contractors); $i++) { 
			$contractor = $contractors[$i];
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "_contractors.deleteRecord(".$contractor->id.")" : "";

			$cssStatus = $contractor->status == "inactive" ? "inactive" : "active";
	?>
			<tr class='row viewAll <?php echo $cssStatus; ?>'>
				<!-- <td class='cell capitalize'>
					<a href="javascript:void(0);" onclick="_contractors.viewOne('<?php echo $contractors[$i]->id; ?>')">
						<?php echo $contractor->name; ?>
					</a>
				</td> -->
				<td class="cell capitalize">
					<a href="javascript:void(0);" onclick="_contractors.viewOne('<?php echo $contractors[$i]->id; ?>')">
						<?php echo $contractor->company; ?></td>
					</a>
				</td>
				<td class="cell capitalize"><?php echo !empty($contractor->service_area) ? $contractor->service_area : "-NA-"; ?></td>
				<td class="cell capitalize"><?php echo !empty($contractor->type) ? $contractor->type : "-NA-"; ?></td>
				<td class="cell capitalize"><?php echo $contractor->status; ?></td>
			</tr>
	<?php
		}
	?>
	</table>
</div>