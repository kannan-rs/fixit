/**
	Notes functions
*/
function note() {

};


note.prototype.projectDetails = function( projectId ) {
	if($(".projectDetails").length) {
		return;
	}

	$.ajax({
		method: "POST",
		url: "/projects/notes/projectDetails",
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

note.prototype.viewAll = function(projectId, taskId, noteId) {
	projectObj.resetCounter("notes");
	projectObj.clearRest(["note_content", "new_note_content"]);
	this.createForm(projectId, taskId);

	this.noteListStartRecord 	= this.noteListStartRecord ? this.noteListStartRecord: 0;
	this.noteListCount 			= this.noteListCount ? this.noteListCount: "All";

	taskId = taskId ? taskId : 0;

	if(!this.noteRequestSent || this.noteRequestSent < this.noteListStartRecord) {
		$.ajax({
			method: "POST",
			url: "/projects/notes/viewAll",
			data: {
				projectId: 		projectId,
				taskId: 		taskId,
				noteId: 		noteId,
				startRecord: 	projectObj._notes.noteListStartRecord,
				count: 			projectObj._notes.noteListCount
			},
			success: function( response ) {
				if(response.length) {
					$("#notesCount").remove();
					if(projectObj._notes.noteListStartRecord == 0) {
						$("#note_content").html(response);
					} else {
						$("#note_list_table").append(response);
					}

					projectObj._notes.noteRequestSent = projectObj._notes.noteListStartRecord;
					projectObj._notes.noteListStartRecord += 5;
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

note.prototype.createForm = function(projectId, taskId) {
	if($("#create_project_note_form").length) {
		$("input[type='text']").val("");
		$("textarea").val("");
		return;
	}
	$.ajax({
		method: "POST",
		url: "/projects/notes/createForm",
		data: {
			projectId: projectId,
			taskId: taskId
		},
		success: function( response ) {
			$("#new_note_content").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

note.prototype.createValidate = function( viewFor ) {
	var validator = $( "#create_project_note_form" ).validate();

	if(validator.form()) {
		if(!viewFor) {
			projectObj._notes.createSubmit();
		} else {
			projectObj._projects.noteCreateSubmit();
		}
		
	}	
};

note.prototype.createSubmit = function() {
	var projectId 			= $("#projectId").val();
	var taskId 				= $("#taskId").val();
	var noteName 			= $("#noteName").val();
	var noteContent 		= $("#noteContent").val();

	$.ajax({
		method: "POST",
		url: "/projects/notes/add",
		data: {
			projectId: 			projectId,
			taskId: 			taskId,
			noteName: 			noteName,
			noteContent: 		noteContent
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._notes.viewAll(response.projectId, response.taskId, response.insertedId);
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
};