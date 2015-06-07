/**
	Projects functions
*/
function project() {

};

/**
	Create Project Validation
*/
project.prototype.createValidate =  function () {
	var validator = $( "#create_project_form" ).validate();

	if(validator.form()) {
		projectObj._projects.createSubmit();
	}
};

project.prototype.viewAll = function() {
	projectObj.resetCounter("docs");
	projectObj.resetCounter("notes");
	projectObj.clearRest();
	projectObj.toggleAccordiance("project", "viewAll");

	$.ajax({
		method: "POST",
		url: "/projects/projects/viewAll",
		data: {},
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

project.prototype.createForm = function() {
	projectObj.clearRest();
	projectObj.toggleAccordiance("project", "create_project");
	$.ajax({
		method: "POST",
		url: "/projects/projects/createForm",
		data: {},
		success: function( response ) {
			$("#project_content").html(response);
			projectObj._projects.setMandatoryFields();
			projectObj._projects.hideContractorDetails();
			projectObj._projects.getContractorDetails();

		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

project.prototype.createSubmit = function() {
	var projectTitle 			= $("#projectTitle").val();
	var description 			= $("#description").val();
	//var assign_user 			= $("#assign_user").val();
	var associated_claim_num 	= $("#associated_claim_num").val();
	var project_type			= $("#project_type").val();
	var project_status			= $("#project_status").val();
	var project_budget			= $("#project_budget").val();
	var property_owner_id		= $("#property_owner_id").val();
	var contractor_id			= $("#contractorId").val();
	var adjuster_id				= $("#adjuster_id").val();
	var customer_id				= $("#customer_id").val();
	var paid_from_budget		= $("#paid_from_budget").val();
	var remaining_budget		= $("#remaining_budget").val();
	var referral_fee			= $("#referral_fee").length ? $("#referral_fee").val() : "";
	var project_lender			= $("#project_lender").val();
	var lend_amount				= $("#lend_amount").val();

	$.ajax({
		method: "POST",
		url: "/projects/projects/add",
		data: {
			projectTitle: 			projectTitle,
			description: 			description,
			//assign_user: 			assign_user,
			associated_claim_num: 	associated_claim_num,
			project_type: 			project_type,
			project_status: 		project_status,
			project_budget: 		project_budget,
			property_owner_id: 		property_owner_id,
			contractor_id: 			contractor_id,
			adjuster_id: 			adjuster_id,
			customer_id: 			customer_id,
			paid_from_budget: 		paid_from_budget,
			remaining_budget: 		remaining_budget,
			referral_fee: 			referral_fee,
			project_lender: 		project_lender,
			lend_amount: 			lend_amount
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._projects.viewOne(response.insertedId);
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

project.prototype.editProject = function() {
	$.ajax({
		method: "POST",
		url: "/projects/projects/editForm",
		data: {
			'projectId' : projectObj._projects.projectId
			
		},
		success: function( response ) {
			//$("#project_content").html(response);
			$("#popupForAll").html(response);
			projectObj._projects.openDialog({"title" : "Edit Project"});
			projectObj._projects.setDropdownValue();
			projectObj._projects.setMandatoryFields();
			projectObj._projects.hideContractorDetails();
			projectObj._projects.getContractorDetails();
			projectObj._projects.setContractorDetails();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

project.prototype.updateValidate = function() {
	var validator = $( "#update_project_form" ).validate();

	if(validator.form()) {
		projectObj._projects.updateSubmit();
	}
};

project.prototype.updateSubmit = function() {
	var project_sno 			= $("#project_sno").val();
	var projectTitle 			= $("#projectTitle").val();
	var description 			= $("#description").val();
	//var assign_user 			= $("#assign_user").val();
	var associated_claim_num 	= $("#associated_claim_num").val();
	var project_type			= $("#project_type").val();
	var project_status			= $("#project_status").val();
	var project_budget			= $("#project_budget").val();
	var property_owner_id		= $("#property_owner_id").val();
	var contractor_id			= $("#contractorId").val();
	var adjuster_id				= $("#adjuster_id").val();
	var customer_id				= $("#customer_id").val();
	var paid_from_budget		= $("#paid_from_budget").val();
	var remaining_budget		= $("#remaining_budget").val();
	var referral_fee			= $("#referral_fee").length ? $("#referral_fee").val() : "";
	var project_lender			= $("#project_lender").val();
	var lend_amount				= $("#lend_amount").val();

	$.ajax({
		method: "POST",
		url: "/projects/projects/update",
		data: {
			project_sno: 			project_sno,
			projectTitle: 			projectTitle,
			description: 			description,
			associated_claim_num: 	associated_claim_num,
			project_type: 			project_type,
			project_status: 		project_status,
			project_budget: 		project_budget,
			property_owner_id: 		property_owner_id,
			contractor_id: 			contractor_id,
			adjuster_id: 			adjuster_id,
			customer_id: 			customer_id,
			paid_from_budget: 		paid_from_budget,
			remaining_budget: 		remaining_budget,
			referral_fee: 			referral_fee,
			project_lender: 		project_lender,
			lend_amount: 			lend_amount

		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				$(".ui-button").trigger("click");
				projectObj._projects.viewOne(response.updatedId);
				alert(response.message);
			} else if(response.status.toLowerCase() == "error") {
				alert(response.message);
			}
		},
		error: function( error ) {
			alert(error);
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
		alert(fail_error);
	});
};

project.prototype.delete = function() {
	$.ajax({
		method: "POST",
		url: "/projects/projects/delete",
		data: {
			projectId: projectObj._projects.projectId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._projects.viewAll();
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

project.prototype.viewOne = function(projectId) {
	this.projectId = projectId;
	projectObj.clearRest();
	projectObj.toggleAccordiance("project", "viewOne");
	
	$( "#project_section_accordion" ).accordion(
		{
			collapsible : true,  
			icons 		: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" },
			active 		: false
		}
	);
	// Project Details View
	setTimeout(function() {
		projectObj._projects.getProjectDetails();
	}, 0);

	// Project Task List
	setTimeout(function() {
		projectObj._projects.getProjectTasksList();
	}, 0);

	// Project Notes
	setTimeout(function() {
		projectObj._projects.getProjectNotesList();
	}, 0);

	// Project Notes
	setTimeout(function() {
		projectObj._projects.getProjectDocumentList();
	}, 0);
};

project.prototype.getProjectDetails = function() {
	$.ajax({
		method: "POST",
		url: "/projects/projects/viewOne",
		data: {
			projectId: projectObj._projects.projectId
		},
		success: function( response ) {
			$("#project_content").html(response);
			$( "#accordion" ).accordion(
				{
					collapsible : true,  
					icons 		: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" },
					active 		: false
				}
			);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

project.prototype.getProjectTasksList = function() {
	$.ajax({
		method: "POST",
		url: "/projects/tasks/viewAll",
		data: {
			projectId 	: projectObj._projects.projectId,
			viewFor 	: 'projectViewOne'
		},
		success: function( response ) {
			$("#task_content").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

project.prototype.getProjectNotesList = function() {
	taskId = "", noteId = "";
	$.ajax({
		method: "POST",
		url: "/projects/notes/viewAll",
		data: {
			projectId 		: projectObj._projects.projectId,
			taskId 			: taskId,
			noteId 			: noteId,
			startRecord 	: projectObj._notes.noteListStartRecord,
			count 			: projectObj._notes.noteListCount,
			viewFor 		: 'projectViewOne'
		},
		success: function( response ) {
			if(response.length) {
				$("#notesCount").remove();
				//if(projectObj._notes.noteListStartRecord == 0) {
					$("#note_content").html(response);
				//} else {
				//	$("#note_list_table").append(response);
				//}

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

project.prototype.getTaskNotesList = function( taskId, startingRecord) {
	noteId = "";
	$.ajax({
		method: "POST",
		url: "/projects/notes/viewAll",
		data: {
			projectId 		: projectObj._projects.projectId,
			taskId 			: taskId,
			noteId 			: noteId,
			startRecord 	: 0,
			count 			: projectObj._notes.noteListCount,
			viewFor 		: 'projectViewOne'
		},
		success: function( response ) {
			if(response.length) {	
				$("#popupForAll").html(response);
				projectObj._projects.openDialog({"title": "Nots List for Task"});
				projectObj._projects.addTaskNote(taskId);
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

project.prototype.addTaskNote = function( taskId ) {
	$.ajax({
		method: "POST",
		url: "/projects/notes/createForm",
		data: {
			projectId 	: projectObj._projects.projectId,
			taskId 		: taskId,
			viewFor 	: 'projectViewOne'
		},
		success: function( response ) {
			$("#popupForAll").append(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

project.prototype.getProjectDocumentList = function() {
	$.ajax({
		method: "POST",
		url: "/projects/docs/viewAll",
		data: {
			projectId: 		projectObj._projects.projectId,
			startRecord: 	projectObj._docs.docsListStartRecord
		},
		success: function( response ) {
			if(response.length) {
				//if(projectObj._docs.docsListStartRecord == 0) {
					$("#attachment_content").html(response);
				//} else {
				//	$("#attachment_content").append(response);
				//}
				//projectObj._docs.docsRequestSent = projectObj._docs.docsListStartRecord;
				//projectObj._docs.docsListStartRecord += 10;
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

project.prototype.addDocumentForm = function() {
	event.stopPropagation();
	$.ajax({
		method: "POST",
		url: "/projects/docs/createForm",
		data: {
			projectId: projectObj._projects.projectId,
		},
		success: function( response ) {
			$("#popupForAll").html(response);
			projectObj._projects.openDialog({"title": "Add Document"});
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

project.prototype.documentDelete = function ( doc_id ) {
		$.ajax({
		method: "POST",
		url: "/projects/docs/delete",
		data: {
			docId: doc_id
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				$("#docId_"+response.docId).remove();
				alert(response.message);
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

project.prototype.taskDelete = function(task_id, project_id) {
	$.ajax({
		method: "POST",
		url: "/projects/tasks/delete",
		data: {
			task_id 	: 	task_id,
			project_id 	: 	project_id
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				//projectObj._projects.getProjectTasksList();
				$("#task_"+task_id).remove();
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

project.prototype.notesDelete = function(noteId, taskId) {
	$.ajax({
		method: "POST",
		url: "/projects/notes/delete",
		data: {
			noteId 	: 	noteId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
					$("#notes_"+noteId).hide();
					$("#notes_"+noteId).remove();
					if(taskId && taskId > 0) {
						$(".ui-button").trigger("click");
						projectObj._projects.getTaskNotesList(taskId, 0);
					}
				setTimeout(function(){alert(response.message);},100);
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

project.prototype.taskViewOne = function(taskId) {
	$.ajax({
		method: "POST",
		url: "/projects/tasks/viewOne",
		data: {
			taskId 		: taskId,
			viewFor 	: 'projectViewOne'
		},
		success: function( response ) {
			$("#popupForAll").html(response);
			projectObj._projects.openDialog({"title": "Task Details"});

		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

project.prototype.addTask = function( ) {
	event.stopPropagation();
	$.ajax({
		method: "POST",
		url: "/projects/tasks/createForm",
		data: {
			projectId: projectObj._projects.projectId,
			viewFor 	: 'projectViewOne'
		},
		success: function( response ) {
			$("#popupForAll").html(response);
			projectObj._projects.openDialog({"title": "Add Task"});
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

project.prototype.addProjectNote = function( ) {
	event.stopPropagation();

	$.ajax({
		method: "POST",
		url: "/projects/notes/createForm",
		data: {
			projectId 	: projectObj._projects.projectId,
			taskId 		: "",
			viewFor 	: 'projectViewOne'
		},
		success: function( response ) {
			$("#popupForAll").html(response);
			projectObj._projects.openDialog({"title" : "Add Notes"});
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

project.prototype.editTask = function(taskId) {
	$.ajax({
		method: "POST",
		url: "/projects/tasks/editForm",
		data: {
			'taskId' 	: taskId,
			viewFor 	: 'projectViewOne'
		},
		success: function( response ) {
			$("#popupForAll").html(response);
			projectObj._projects.openDialog({"title": "Edit Task Details"});
			projectObj._tasks.setDropdownValue();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

project.prototype.taskCreateSubmit = function() {
	parentId 					= $("#parentId").val();
	task_name 					= $("#task_name").val();
	task_desc 					= $("#task_desc").val();
	task_start_date 			= $("#task_start_date").val();
	task_end_date 				= $("#task_end_date").val();
	task_status					= $("#task_status").val();
	task_owner_id				= $("#task_owner_id").val();
	task_percent_complete		= $("#task_percent_complete").val();
	task_dependency				= $("#task_dependency").val();
	task_trade_type				= $("#task_trade_type").val();

	$.ajax({
		method: "POST",
		url: "/projects/tasks/add",
		data: {
			parentId: 				projectObj._projects.projectId,
			task_name: 				task_name,
			task_desc: 				task_desc,
			task_start_date: 		task_start_date,
			task_end_date: 			task_end_date,
			task_status: 			task_status,
			task_owner_id: 			task_owner_id,
			task_percent_complete: 	task_percent_complete,
			task_dependency: 		task_dependency,
			task_trade_type: 		task_trade_type
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				//projectObj._tasks.viewOne(response.insertedId);
				projectObj._projects.getProjectTasksList(projectObj._projects.projectId);
				projectObj._projects.taskViewOne(response.insertedId);
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

project.prototype.noteCreateSubmit = function() {
	var taskId 				= $("#taskId").val();
	var noteName 			= $("#noteName").val();
	var noteContent 		= $("#noteContent").val();

	$.ajax({
		method: "POST",
		url: "/projects/notes/add",
		data: {
			projectId: 			projectObj._projects.projectId,
			taskId: 			taskId,
			noteName: 			noteName,
			noteContent: 		noteContent
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				
				$(".ui-button").trigger("click");
				if(taskId && taskId != "" && taskId > 0) {
					projectObj._projects.getTaskNotesList(taskId);
				} else {
					projectObj._projects.getProjectNotesList();
				}
				
				alert(response.message);
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

project.prototype.taskUpdateSubmit = function() {
	task_id 				= $("#task_sno").val();
	task_name 				= $("#task_name").val();
	task_desc 				= $("#task_desc").val();
	task_start_date 		= $("#task_start_date").val();
	task_end_date 			= $("#task_end_date").val();
	task_status 			= $("#task_status").val();
	task_owner_id 			= $("#task_owner_id").val();
	task_percent_complete	= $("#task_percent_complete").val();
	task_dependency			= $("#task_dependency").val();
	task_trade_type			= $("#task_trade_type").val();


	$.ajax({
		method: "POST",
		url: "/projects/tasks/update",
		data: {
			task_id: 				task_id,
			task_name: 				task_name,
			task_desc: 				task_desc,
			task_start_date: 		task_start_date,
			task_end_date: 			task_end_date,
			task_status: 			task_status,
			task_owner_id: 			task_owner_id,
			task_percent_complete: 	task_percent_complete,
			task_dependency: 		task_dependency,
			task_trade_type: 		task_trade_type
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._projects.getProjectTasksList();
				projectObj._projects.taskViewOne(task_id);
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

project.prototype.setDropdownValue = function() {
	var db_project_type = $("#db_project_type").val();
	var db_project_status = $("#db_project_status").val();

	$("#project_type").val(db_project_type);
	$("#project_status").val(db_project_status);

}

project.prototype.setMandatoryFields = function(){
	
}

project.prototype.openDialog = function( options ) {
	if(typeof(this.popupDialog) == "undefined") {
		this.popupDialog = $( "#popupForAll" ).dialog({
	      	autoOpen: false,
	      	maxHeight : 700,
	      	width: 700,
	      	modal: true
	  	});
	}
  	this.popupDialog.dialog("open");
  	if( typeof(options) != "undefined" ) {
  		this.popupDialog.dialog("option", "title", options.title); 
  	}
}

project.prototype.hideContractorDetails = function() {
	$(".contractorDetails").hide();
	$(".contractorCompany").hide();
	$(".contractorCompanyInfo > span").hide();
}

project.prototype.getContractorDetails = function() {
	$.ajax({
		method: "POST",
		url: "/projects/contractors/getList",
		data: {},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				contractors = response["contractors"];
				for(var i =0 ; i < contractors.length; i++) {
					$('#contractorId').append($('<option>', {
					    value: contractors[i].id,
					    text: contractors[i].name
					}));
				}
				hideContractorDetails();
			} else {
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

project.prototype.setContractorDetails = function() {
	setTimeout(function() {
		$("#contractorId").val($("#contractorIdDb").val());
	}, 100);
}