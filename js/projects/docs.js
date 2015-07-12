/**
	Notes functions
*/
function docs() {

};


docs.prototype.projectDetails = function( projectId ) {
	if($(".projectDetails").length) {
		return;
	}

	$.ajax({
		method: "POST",
		url: "/projects/docs/projectDetails",
		data: {
			projectId: projectId
		},
		success: function( response ) {
			$("#project_content").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

docs.prototype.viewAll = function( projectId ) {
	projectObj.resetCounter("docs");
	//projectObj.clearRest(["attachment_list", "new_attachment"]);
	this.createForm(projectId);

	this.docsListStartRecord = this.docsListStartRecord ? this.docsListStartRecord: 0;

	if(!this.docsRequestSent || this.docsRequestSent < this.docsListStartRecord) {
		$.ajax({
			method: "POST",
			url: "/projects/docs/viewAll",
			data: {
				projectId: 		projectId,
				startRecord: 	projectObj._docs.docsListStartRecord
			},
			success: function( response ) {
				if(response.length) {
					if(projectObj._docs.docsListStartRecord == 0) {
						$("#attachment_list").html(response);
					} else {
						$("#attachment_list").append(response);
					}
					projectObj._docs.docsRequestSent = projectObj._docs.docsListStartRecord;
					projectObj._docs.docsListStartRecord += 10;
				}
			},
			error: function( error ) {
				error = error;
			}
		})
		.fail(function ( failedObj ) {
			fail_error = failedObj;
		});
	}
};

docs.prototype.createForm = function(projectId) {
	if($("#create_project_doc_form").length) {
		$("input[type='text']").val("");
		$("textarea").val("");
		return;
	}
	$.ajax({
		method: "POST",
		url: "/projects/docs/createForm",
		data: {
			projectId: projectId
		},
		success: function( response ) {
			$("#new_attachment").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

docs.prototype.createValidate = function() {
	var validator = $( "#create_project_doc_form" ).validate();

	if(validator.form()) {
		return true;
	} else {
		return false;
	}	
};

docs.prototype.getAttachment = function(doc_id) {

}

docs.prototype.deleteRecord = function ( doc_id ) {
		$.ajax({
		method: "POST",
		url: "/projects/docs/deleteRecord",
		data: {
			docId: doc_id
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._docs.removeDoc( response.docId);
			} else if(response.status.toLowerCase() == "error") {
				alert(response.message);
			}
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

docs.prototype.removeDoc = function( docId) {
	$("#docId_"+docId).hide();
}