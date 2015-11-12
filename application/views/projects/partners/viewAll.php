<h2><?php echo $this->lang->line_arr('partner->headers->view_all'); ?></h2>
<div class="partners internal-tab-as-links" onclick="projectObj._partners.showPartnersList(event)">
	<a href="javascript:void(0);" data-option="active" title="<?php echo $this->lang->line_arr('partner->buttons_links->active_title'); ?>"><?php echo $this->lang->line_arr('partner->buttons_links->active'); ?></a>
	<a href="javascript:void(0);" data-option="inactive" title="<?php echo $this->lang->line_arr('partner->buttons_links->in_active_title'); ?>"><?php echo $this->lang->line_arr('partner->buttons_links->in_active'); ?></a>
	<?php if($account_type == "admin") { ?>
		<!-- <a href="javascript:void(0);" data-option="deleted"><?php echo $this->lang->line_arr('partner->buttons_links->delete'); ?></a> -->
	<?php } ?>
	<a href="javascript:void(0);" data-option="all" title="<?php echo $this->lang->line_arr('partner->buttons_links->all_title'); ?>"><?php echo $this->lang->line_arr('partner->buttons_links->all'); ?></a>
</div>
<div>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="partners-table-list">
	
	<?php
		if(count($partners) > 0) {
	?>
			<tr class='heading'>
			<td class='cell'><?php echo $this->lang->line_arr('partner->summary_table->partner_name'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('partner->summary_table->company'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('partner->summary_table->type'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('partner->summary_table->status'); ?></td>
			</tr>
	<?php
		}

		for($i = 0; $i < count($partners); $i++) { 
			$partner = $partners[$i];
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "projectObj._partners.deleteRecord(".$partner->id.")" : "";

			$cssStatus = $partner->status == "inactive" ? "inactive" : "active";
	?>
			<tr class='row viewAll <?php echo $cssStatus; ?>'>
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