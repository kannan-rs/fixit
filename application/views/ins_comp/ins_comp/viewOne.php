<?php
$ins_comp		= $ins_comps[0];
?>
<div id="ins_comp_tabs" class="page-tabs">
	
	<!-- Tab Menu content #1 -->
	<div class="header-options">
		<h2 class=''><?php echo $this->lang->line_arr('ins_comp->headers->view_one'); ?></h2>
		<span class="options-icon left-icon-list">
			<span class="ui-accordion-header-icon ui-icon ui-icon-plus expand-all" 
				title="<?php echo $this->lang->line_arr('projects->buttons_links->expand_all'); ?>" 
				onclick="_utils.viewOnlyExpandAll('ins_comp_accordion')">
			</span>
			<span class="ui-accordion-header-icon ui-icon ui-icon-minus collapse-all" 
				title="<?php echo $this->lang->line_arr('projects->buttons_links->collapse_all'); ?>" 
				onclick="_utils.viewOnlyCollapseAll('ins_comp_accordion')">
			</span>
		</span>
		<span class="options-icon">
			<?php
			if(in_array(OPERATION_UPDATE, $ins_comp_permission['operation'])) {
				$editFn 		= "_ins_comp.editForm({'openAs':'popup', 'popupType' : 2})";
			?>
				<span>
					<a class="step fi-page-edit size-21" href="javascript:void(0);" 
						onclick="<?php echo $editFn; ?>" 
						title="<?php echo $this->lang->line_arr('ins_comp->buttons_links->edit_hover_text'); ?>">
					</a>
				</span>
			<?php
			}
			if(in_array(OPERATION_DELETE, $ins_comp_permission['operation'])) {
				$deleteFn 		= "_ins_comp.deleteRecord('".$ins_comp_id."')";
			?>
				<span>
					<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" 
						onclick="<?php echo $deleteFn; ?>" 
						title="<?php echo $this->lang->line_arr('ins_comp->buttons_links->delete_hover_text'); ?>">
					</a>
				</span>	
			<?php
			}
			?>
		</span>
	</div>
	<div class="clear"></div>
	<div id="ins_comp_accordion" class="accordion">
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('ins_comp->headers->ins_comp_details'); ?></span></h3>
		<div>
			<table cellspacing="0" class="viewOne">
				<tbody>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('ins_comp->details_view->company'); ?></td>
						<td class="capitalize"><?php echo $ins_comp->ins_comp_name; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('ins_comp->details_view->default_contract_user'); ?></td>
						<td class="capitalize"><?php echo isset($ins_comp->default_contact_user_disp_str) ? $ins_comp->default_contact_user_disp_str : ""; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('ins_comp->details_view->office_email_id'); ?></td>
						<td class="capitalize"><?php echo isset($ins_comp->email_id) ? $ins_comp->email_id : ""; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('ins_comp->details_view->office_number'); ?></td>
						<td class="capitalize"><?php echo isset($ins_comp->contact_no) ? $ins_comp->contact_no : ""; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('ins_comp->headers->ins_comp_address'); ?></span></h3>
		<div>
			<table cellspacing="0" class="viewOne">
				<tbody>
					<?php
					echo $addressFile;
					?>
				</tbody>
			</table>
		</div>
		<h3>
			<span class="inner_accordion">
				<?php echo $this->lang->line_arr('ins_comp->headers->ins_comp_others') ?>
			</span>
		</h3>
		<div>
			<table cellspacing="0" class="viewOne">
				<tbody>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('common_text->created_by'); ?></td>
						<td><?php echo $ins_comp->created_by; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('common_text->created_on'); ?></td>
						<td><?php echo $ins_comp->created_on; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('common_text->updated_by'); ?></td>
						<td><?php echo $ins_comp->updated_by; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('common_text->updated_on'); ?></td>
						<td><?php echo $ins_comp->updated_on; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>