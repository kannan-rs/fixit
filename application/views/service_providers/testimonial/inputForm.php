<?php
	$edit = false;
	$prefix = "create";

	$id 				= "";
	$for_contractor_id 	= "";
	$for_customer_id 	= "";
	$for_customer_name 	= "";
	$anonynomus_name 	= "";
	$summary 			= "";
	$descr 				= "";
	$rating 			= "";
	$date 				= "";

	$testimonial  = isset($testimonialList) && is_array($testimonialList) ? $testimonialList[0] : null;

	if($testimonial) {
		$edit = true;
		$prefix = "update";

		$id 						= $testimonial->testimonial_id;
		$for_contractor_id 			= $testimonial->testimonial_contractor_id;
		$for_customer_id 			= $testimonial->testimonial_customer_id;
		$anonynomus_name 			= $testimonial->testimonial_anonynomus_name;
		$summary 					= $testimonial->testimonial_summary;
		$descr 						= $testimonial->testimonial_descr;
		$rating					= $testimonial->testimonial_rating;
		$date						= explode(" ", $testimonial->testimonial_date_input_box)[0];
	}
?>

<form id="<?php echo $prefix; ?>_testimonial_contractor_form" name="<?php echo $prefix; ?>_testimonial_contractor_form" class="inputForm">
	
	<input type="hidden" name="dbTestimonialId" id="dbTestimonialId" value="<?php echo isset($id) ? $id : ""; ?>">
	<input type="hidden" name="dbCustomerId" id="dbCustomerId" value="<?php echo isset($for_customer_id) ? $for_customer_id : ""; ?>" />
	<input type="hidden" name="dbRating" id="dbRating" value="<?php echo isset($rating) ? $rating : ""; ?>" />

	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->testimonial_summary'); ?>:</td>
				<td>
					<input type="text" name="testimonial_summary" id="testimonial_summary" value="<?php echo isset($summary) ? $summary : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->testimonial_summary_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label notMandatory"><?php echo $this->lang->line_arr('contractor->input_form->testimonial_descr'); ?></td>
				<td>
					<textarea type="text" name="testimonial_descr" id="testimonial_descr" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->testimonial_descr_ph'); ?>" ><?php echo isset($descr) ? $descr : "";?></textarea>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->testimonial_rating'); ?></td>
				<td>
					<input type="text" name="testimonial_rating" id="testimonial_rating" value="<?php echo isset($rating) ? $rating : "";?>" 
						placeholder="<?php echo $this->lang->line_arr('contractor->input_form->testimonial_rating_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->testimonial_customer_name'); ?>:</td>
				<td>
					<input type="text" name="testimonial_customer_name" id="testimonial_customer_name" value="<?php echo isset($anonynomus_name) ? $anonynomus_name : "";?>" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->testimonial_customer_name_ph'); ?>" trquired>
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('contractor->input_form->testimonial_date'); ?>:</td>
				<td>
					<input type="text" name="testimonial_date" id="testimonial_date" value="<?php echo isset($date) ? $date : "";?>" placeholder="<?php echo $this->lang->line_arr('contractor->input_form->testimonial_date_ph'); ?>" required>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="button" id="<?php echo $prefix; ?>_contractor_submit" onclick="_contractor_testimonial.<?php echo $prefix; ?>Validate()"><?php echo $this->lang->line_arr('contractor->buttons_links->'.$prefix."_testimonial"); ?></button>
						
						<button type="button" id="cancelButton" onclick="_projects.closeDialog()"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
						
						<button type="reset" id="resetButton" onclick=""><?php echo $this->lang->line_arr('buttons->reset'); ?></button>
						
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
		</tbody>
	</table>
</form>
