<?php
	$contractor		= $contractors[0];

	if(!$openAs || $openAs != "popup") {
?>
	<div id="contractor_tabs" class="page-tabs">
		<ul>
			<li><a href="#tabs_contractor_company_details">Company details</a></li>
			<li><a href="#tabs_contractor_trade_sub_trades" onclick="_contractor_trades.showTradeList()">Trade & sub Trades</a></li>
			<li><a href="#tabs_contractor_discounts" onclick="_contractor_discounts.initialPage()">Discounts</a></li>
			<li><a href="#tabs_contractor_testimonial" onclick="_contractor_testimonial.viewAll()">Customer Testimonial</a></li>
		</ul>
		<div id="tabs_contractor_company_details">
		<!-- Tab Menu content #1 -->
			<div class="header-options">
				<h2 class=''><?php echo $this->lang->line_arr('contractor->headers->view_one'); ?></h2>
				<span class="options-icon left-icon-list">
					<span class="ui-accordion-header-icon ui-icon ui-icon-plus expand-all" 
						title="<?php echo $this->lang->line_arr('projects->buttons_links->expand_all'); ?>" 
						onclick="_utils.viewOnlyExpandAll('service_provider_accordion')">
					</span>
					<span class="ui-accordion-header-icon ui-icon ui-icon-minus collapse-all" 
						title="<?php echo $this->lang->line_arr('projects->buttons_links->collapse_all'); ?>" 
						onclick="_utils.viewOnlyCollapseAll('service_provider_accordion')">
					</span>
				</span>
				<span class="options-icon">
					<?php 
					if(in_array(OPERATION_UPDATE, $contractorPermission['operation'])) {
						$editFn 		= "_contractors.editForm({'openAs':'popup', 'popupType' : 2})";
					?>
						<span>
							<a class="step fi-page-edit size-21" href="javascript:void(0);" 
								onclick="<?php echo $editFn; ?>" 
								title="<?php echo $this->lang->line_arr('contractor->buttons_links->edit_hover_text'); ?>">
							</a>
						</span>
					<?php
					}
					if(in_array(OPERATION_DELETE, $contractorPermission['operation'])) {
						$deleteFn 		= "_contractors.deleteRecord('".$contractorId."')";
					?>
						<span>
							<a class="step fi-deleteRow size-21 red delete" href="javascript:void(0);" 
								onclick="<?php echo $deleteFn; ?>" 
								title="<?php echo $this->lang->line_arr('contractor->buttons_links->delete_hover_text'); ?>">
							</a>
						</span>	
					<?php
					}
					?>
				</span>
			</div>
			<div class="clear"></div>
		<?php
		}
		?>
			<div id="service_provider_accordion" class="accordion">
				<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('contractor->headers->contractor_details'); ?></span></h3>
				<div>
					<table cellspacing="0" class="viewOne">
						<tbody>
							<!--
							<tr> 
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->name'); ?>:</td>
								<td class="capitalize"><?php echo $contractor->name; ?></td> 
							</tr>
							-->
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->company'); ?></td>
								<td class="capitalize"><?php echo $contractor->company; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->default_contract_user'); ?></td>
								<td class="capitalize"><?php echo isset($contractor->default_contact_user_disp_str) ? $contractor->default_contact_user_disp_str : ""; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->type'); ?></td>
								<td class="capitalize"><?php echo $contractor->type; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->license'); ?></td>
								<td class="capitalize"><?php echo $contractor->license; ?></td>
							</tr>
							<!-- <tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->bbb'); ?></td>
								<td class="capitalize"><?php echo $contractor->bbb; ?></td>
							</tr> -->
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->status'); ?></td>
								<td class="capitalize"><?php echo $contractor->status; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<h3><span class="inner_accordion"><?php echo $this->lang->line_arr('contractor->headers->contractor_address'); ?></span></h3>
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
						<?php echo $this->lang->line_arr('contractor->headers->contractor_contact')." & ".$this->lang->line_arr('contractor->headers->contractor_service_area'); ?>
					</span>
				</h3>
				<div>
					<table cellspacing="0" class="viewOne">
						<tbody>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->office_email_id'); ?></td>
								<td><?php echo $contractor->office_email; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->office_number'); ?></td>
								<td><?php echo $contractor->office_ph; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->mobile_number'); ?></td>
								<td><?php echo $contractor->mobile_ph; ?></td>
							</tr>
								<!-- 
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->prefered_mode'); ?></td>
								<td>
									<input type="hidden" name="prefContactDb" id="prefContactDb" value="<?php echo $contractor->prefer; ?>" />
									<table class="innerOption">
										
											<td><input type="checkbox" name="prefContact" id="prefContactEmailId" value="emailId" disabled></td>
											<td><?php echo $this->lang->line_arr('contractor->details_view->email'); ?></td>
											<td><input type="checkbox" name="prefContact" id="prefContactofficeNumber" value="officeNumber" disabled></td>
											<td><?php echo $this->lang->line_arr('contractor->details_view->office_phone'); ?></td>
											<td><input type="checkbox" name="prefContact" id="prefContactMobileNumber" value="mobileNumber" disabled></td>
											<td><?php echo $this->lang->line_arr('contractor->details_view->mobile_number'); ?></td>
										
									</table>
								</td> 
								</tr>
								-->
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->webSite_url'); ?></td>
								<td><?php echo $contractor->website_url; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->serive_provided'); ?></td>
								<td><?php echo $contractor->service_area; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<h3>
					<span class="inner_accordion">
						<?php echo $this->lang->line_arr('contractor->headers->contractor_others') ?>
					</span>
				</h3>
					<div>
						<table cellspacing="0" class="viewOne">
							<tbody>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('common_text->created_by'); ?></td>
								<td><?php echo $contractor->created_by; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('common_text->created_on'); ?></td>
								<td><?php echo $contractor->created_on; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('common_text->updated_by'); ?></td>
								<td><?php echo $contractor->updated_by; ?></td>
							</tr>
							<tr>
								<td class='label'><?php echo $this->lang->line_arr('common_text->updated_on'); ?></td>
								<td><?php echo $contractor->updated_on; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		<?php
		if(!$openAs || $openAs != "popup") {
		?>
		</div>
		<!-- Trades and Sub Trade list -->
		<div id="tabs_contractor_trade_sub_trades">
			<div class="header-options">
				<h2 class="ui-accordion-header show-in-contractor-trade"><?php echo $this->lang->line_arr('contractor->headers->trades_list'); ?></h2>
				<?php
				if($role_disp_name == ROLE_ADMIN) {
				?>
					<h2 class="ui-accordion-header show-in-master-trade"><?php echo $this->lang->line_arr('contractor->headers->master_trades_list'); ?></h2>
				<?php } ?>
				<span class="options-icon left-icon-list">
					<?php
					if($role_disp_name == ROLE_ADMIN) {
					?>
					<span class="show-in-contractor-trade">
						<a class="step fi-results size-21" href="javascript:void(0);" 
							onclick="_contractor_trades.list_all_Trades_and_manage(true)" title="<?php echo $this->lang->line_arr('contractor->buttons_links->list_all_trades_and_manage'); ?>">
						</a>
					</span>
					<?php
					}
					?>
				</span>
				<span class="options-icon">
					<span class="show-in-contractor-trade">
						<a class="step fi-page-add size-21" href="javascript:void(0);" 
							onclick="_contractor_trades.addNewMainTrendsForm()" title="<?php echo $this->lang->line_arr('contractor->buttons_links->add_main_trade'); ?>">
						</a>
					</span>
					<?php
					if($role_disp_name == ROLE_ADMIN) {
					?>
					<span class="show-in-master-trade">
						<a class="step fi-page-add size-21" href="javascript:void(0);" 
							onclick="_contractor_trades.add_main_trend_for_master_form()" title="<?php echo $this->lang->line_arr('contractor->buttons_links->add_master_main_trade'); ?>">
						</a>
					</span>
					<span class="show-in-master-trade">
						<a class="step fi-arrow-left size-21" href="javascript:void(0);" 
							onclick="_contractor_trades.showTradeList(true)" title="<?php echo $this->lang->line_arr('contractor->buttons_links->back_to_contractor_trades'); ?>">
						</a>
					</span>
					<?php
					}
					?>
				</span>
			</div>
			<div style="clear:both"></div>
			<div id="tradesList"></div>
		</div>
		<!-- Discount List -->
		<div id="tabs_contractor_discounts">
			<div class="header-options">
				<h2 class="ui-accordion-header"><?php echo $this->lang->line_arr('contractor->headers->discount_list'); ?></h2>
			</div>
			<div id="discountFilters">
				<table>
					<tr>
						<td>Main Trade</td>
						<td>
							<select id="discount_for_main_trade" onchange="_contractor_discounts.populateSubTrade( this.value ); _contractor_discounts.show_discount_by_filter();">
								<option value="0">-- Select Main Trade --</option>
							</select>
						</td>
						<td>Sub Trade</td>
						<td>
							<select id="discount_for_sub_trade" onchange="_contractor_discounts.show_discount_by_filter();">
								<option value="0">-- Select Sub Trade --</option>
							</select>
						</td>
						<td>
							<div class="header-options">
							<span class="options-icon">
								<span>
									<a class="step fi-page-add size-21" href="javascript:void(0);" 
										onclick="_contractor_discounts.createForm()" title="<?php echo $this->lang->line_arr('contractor->buttons_links->add_discount'); ?>">
									</a>
								</span>
							</span>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							Select main trade and sub trade to filter the discount list or to add new discount
						</td>
					</tr>
				</table>
			</div>
			<div id="discountList"></div>
		</div>
		<!-- Testimonial List -->
		<div id="tabs_contractor_testimonial">
			<div class="header-options">
				<h2 class="ui-accordion-header"><?php echo $this->lang->line_arr('contractor->headers->testomonial_list'); ?></h2>
				<div class="header-options">
					<span class="options-icon">
						<span>
							<a class="step fi-page-add size-21" href="javascript:void(0);" 
								onclick="_contractor_testimonial.createForm( event )" 
								title="<?php echo $this->lang->line_arr('contractor->buttons_links->add_testimonial'); ?>">
							</a>
						</span>
					</span>
				</div>
			</div>
			<div style="clear:both"></div>
			<div id="testimonialList"></div>
		</div>
	</div>
<?php
}
?>