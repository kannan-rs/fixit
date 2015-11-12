<!-- Add Function Start -->
<form id="create_project_doc_form" name="create_project_doc_form" class="inputForm" enctype="multipart/form-data">
	<input type="hidden" name="projectId" id="projectId" value="<?php echo $projectId; ?>" />
	<table class='form'>
		<tbody>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('docs->input_form->docName'); ?>:</td>
				<td>
					<input type="text" name="docName" id="docName" placeholder="<?php echo $this->lang->line_arr('docs->input_form->docName_ph'); ?>" value="">
				</td>
			</tr>
			<tr>
				<td class="label"><?php echo $this->lang->line_arr('docs->input_form->docAttachment'); ?>:</td>
				<td>
					<input type="file" name="docAttachment" id="docAttachment" placeholder="<?php echo $this->lang->line_arr('docs->input_form->docName_ph'); ?>" value="" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="button-panel">
						<button type="submit" id="create_project_doc_submit"><?php echo $this->lang->line_arr('docs->buttons_links->upload'); ?></button>
						<button type="button" id="cancelButton" onclick="projectObj._projects.closeDialog()"><?php echo $this->lang->line_arr('buttons->cancel'); ?></button>
					</p>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo $this->lang->line('mandatory_field_text'); ?></td>
			</tr>
			<!-- <div id="notification"></div> -->
		</tbody>
	</table>
</form>
<script>
$("#create_project_doc_form").on('submit',(function(e) {
	e.preventDefault();

	if(!projectObj._docs.createValidate())
		return false;
	
	$.ajax({
    	url: "/projects/docs/add",
		type: "POST",
		data:  new FormData(this),
		contentType: false, 		
	    cache: false,
		processData:false,
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				$(".ui-button").trigger("click");
				projectObj._projects.getProjectDocumentList();
			}
			alert(response.message);
			
	    },
		error: function( error ) {
			error = error;
		}	        
   })
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}));
</script>