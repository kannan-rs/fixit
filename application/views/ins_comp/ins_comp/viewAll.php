<div class="header-options">
	<h2 class=''><?php echo $this->lang->line_arr('ins_comp->headers->view_all'); ?></h2>
	<?php /*
	<div class="contractors internal-tab-as-links" onclick="_ins_comp.showContractorsList(event)">
		<a href="javascript:void(0);" data-option="active" title="<?php echo $this->lang->line_arr('ins_comp->buttons_links->active_hover_text'); ?>">
			<?php echo $this->lang->line_arr('ins_comp->buttons_links->active'); ?>
		</a>
		<a href="javascript:void(0);" data-option="inactive" title="<?php echo $this->lang->line_arr('ins_comp->buttons_links->in_active_hover_text'); ?>">
			<?php echo $this->lang->line_arr('ins_comp->buttons_links->in_active'); ?>
		</a>
		<?php if($role_disp_name == ROLE_ADMIN) { ?>
			<a href="javascript:void(0);" data-option="deleted">
				<?php echo $this->lang->line_arr('ins_comp->buttons_links->deleted'); ?>
			</a>
		<?php } ?>
		<a href="javascript:void(0);" data-option="all" title="<?php echo $this->lang->line_arr('ins_comp->buttons_links->all_hover_text'); ?>">
			<?php echo $this->lang->line_arr('ins_comp->buttons_links->all'); ?>
		</a>
	</div>
	*/ ?>
</div>

<div>
<?php
	if(count($ins_comps) > 0) {
?>
	<!-- List all the Functions from database -->
	<table cellspacing="0" class="contractors-table-list">
		<tr class='heading'>
			<!-- <td class='cell'><?php echo $this->lang->line_arr('ins_comp->summary_table->contractor_name'); ?></td> -->
			<td class='cell'><?php echo $this->lang->line_arr('ins_comp->summary_table->company'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('ins_comp->summary_table->admin_user'); ?></td>
			<td class='cell'><?php echo $this->lang->line_arr('ins_comp->summary_table->email_id'); ?></td>
		</tr>
	<?php
		for($i = 0; $i < count($ins_comps); $i++) { 
			$ins_comp = $ins_comps[$i];
			$deleteText = "Delete";
			$deleteFn = $deleteText ? "_ins_comp.deleteRecord(".$ins_comp->ins_comp_id.")" : "";
	?>
			<tr class='row viewAll <?php echo $cssStatus; ?>'>
				<td class="cell capitalize">
					<a href="javascript:void(0);" onclick="_ins_comp.viewOne('<?php echo $ins_comps[$i]->ins_comp_id; ?>')">
						<?php echo $ins_comp->ins_comp_name; ?></td>
					</a>
				</td>
				<td class="cell capitalize"><?php echo $ins_comp->default_contact_user_disp_str; ?></td>
				<td class="cell capitalize"><?php echo $ins_comp->email_id; ?></td>
			</tr>
	<?php } ?>
	</table>
<?php } else { ?>
	<DIV> No Insurance company assigned yet </DIV>
<?php } ?>
</div>