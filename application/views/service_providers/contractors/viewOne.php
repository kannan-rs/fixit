<?php
	$contractor		= $contractors[0];

	if(!$openAs || $openAs != "popup") {
?>
	<div id="contractor_tabs" class="page-tabs">
		<ul>
			<li><a href="#tabs_contractor_company_details">Company details</a></li>
			<li><a href="#tabs_contractor_trade_sub_trades" onclick="_contractors.showTradeList()">Trade & sub Trades</a></li>
			<li><a href="#tabs_contractor_discounts" onclick="_contractors.showDiscountInitialPage()">Discounts</a></li>
			<li><a href="#tabs_contractor_testimonial" onclick="_contractors.showTestimonial()">Customer Testimonial</a></li>
		</ul>
		<div id="tabs_contractor_company_details">
		<!-- Tab Menu content #1 -->
		
			<div class="header-options">
				<h2><?php echo $this->lang->line_arr('contractor->headers->view_one'); ?></h2>
				<span class="options-icon">
					<?php 
					if(in_array('update', $contractorPermission['operation'])) {
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
					if(in_array('delete', $contractorPermission['operation'])) {
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
			<table cellspacing="0" class="viewOne">
				<tbody>
				<!-- List all the Functions from database -->
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
						<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->dtatus'); ?></td>
						<td class="capitalize"><?php echo $contractor->status; ?></td>
					</tr>
					<?php
					echo $addressFile;
					?>
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
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->created_by'); ?></td>
						<td><?php echo $contractor->created_by; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->created_on'); ?></td>
						<td><?php echo $contractor->created_on; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->updated_by'); ?></td>
						<td><?php echo $contractor->updated_by; ?></td>
					</tr>
					<tr>
						<td class='label'><?php echo $this->lang->line_arr('contractor->details_view->updated_on'); ?></td>
						<td><?php echo $contractor->updated_on; ?></td>
					</tr>
				</tbody>
			</table>
		<?php
		if(!$openAs || $openAs != "popup") {
		?>
		</div>
		<!-- Trades and Sub Trade list -->
		<div id="tabs_contractor_trade_sub_trades">
			<div class="header-options">
				<h2 class="ui-accordion-header"><?php echo $this->lang->line_arr('contractor->headers->trades_list'); ?></h2>
				<span class="options-icon">
					<span>
						<a class="step fi-page-add size-21" href="javascript:void(0);" 
							onclick="_contractors.addNewMainTrendsForm()" title="<?php echo $this->lang->line_arr('contractor->buttons_links->add_main_trend'); ?>">
						</a>
					</span>
				</span>
			</div>
			<div style="clear:both"></div>
			<div id="tradesList">
			</div>
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
							<select id="discount_for_main_trade" onchange="_contractors.populateSubTradeInDiscount( this.value );">
								<option value="0">-- Select Main Trade --</option>
							</select>
						</td>
						<td>Sub Trade</td>
						<td>
							<select id="discount_for_sub_trade">
								<option value="0">-- Select Sub Trade --</option>
							</select>
						</td>
						<td>
							<div class="header-options">
							<span class="options-icon">
								<span>
									<a class="step fi-page-add size-21" href="javascript:void(0);" 
										onclick="_contractors.addDiscountForm()" title="<?php echo $this->lang->line_arr('contractor->buttons_links->add_discount'); ?>">
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
								onclick="_contractors.addTestomonialForm( event )" 
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