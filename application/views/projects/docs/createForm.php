<!-- Add Function Start -->
<!-- <h2>Attach Document</h3>-->
<form id="create_project_doc_form" name="create_project_doc_form" class="inputForm" enctype="multipart/form-data">
	<div class='form'>
		<input type="hidden" name="projectId" id="projectId" value="<?php echo $projectId; ?>" />
		<div class="label">Document Name:</div>
		<div>
			<input type="text" name="docName" id="docName" value="" required>
		</div>
		<div class="label">Choose Document:</div>
		<div>
			<input type="file" name="docAttachment" id="docAttachment" required value="" />
		</div>
		<p class="button-panel">
			<button type="submit" id="create_project_doc_submit">Upload Document</button>
			<button type="button" id="cancelButton" onclick="projectObj._projects.closeDialog()">Cancel</button>
		</p>
		<div id="notification"></div>
	</div>
</form>
<script>
$("#create_project_doc_form").on('submit',(function(e) {
	e.preventDefault();
	
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