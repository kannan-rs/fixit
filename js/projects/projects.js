/**
	Projects functions
*/
function project() {

};

project.prototype.selectedContractor = [];
project.prototype.selectedAdjuster = [];
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
			projectObj._projects.hideContractorDetails('all');
			projectObj._projects.hideAdjusterDetails('all');
			projectObj._projects.hideDropDowns();
			utilObj.setCustomerDataList();
			utilObj.setAdjusterDataList();
			utilObj.getAndSetCountryStatus("create_project_form");
			utilObj.setAsDateRangeFields({fromDateField: "start_date", toDateField: "end_date"});
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
	var idPrefix 				= "#create_project_form ";
	var projectTitle 			= $(idPrefix+"#projectTitle").val();
	var description 			= $(idPrefix+"#description").val();
	var associated_claim_num 	= $(idPrefix+"#associated_claim_num").val();
	var project_type			= $(idPrefix+"#project_type").val();
	var start_date 				= utilObj.toMySqlDateFormat($(idPrefix+"#start_date").val());
	var end_date 				= utilObj.toMySqlDateFormat($(idPrefix+"#end_date").val());
	var project_status			= $(idPrefix+"#project_status").val();
	var project_budget			= $(idPrefix+"#project_budget").val();
	var property_owner_id		= $(idPrefix+"#property_owner_id").val();
	var contractor_id			= [];
	var adjuster_id				= [];//$(idPrefix+"#adjuster_id").val();
	var customer_id				= $(idPrefix+"#customer_id").val();
	var remaining_budget		= $(idPrefix+"#remaining_budget").val();
	var deductible				= $(idPrefix+"#deductible").length ? $(idPrefix+"#deductible").val() : "";
	var project_lender			= $(idPrefix+"#project_lender").val();
	var lend_amount				= $(idPrefix+"#lend_amount").val();
	var addressLine1 			= $(idPrefix+"#addressLine1").val();
	var addressLine2 			= $(idPrefix+"#addressLine2").val();
	var city 					= $(idPrefix+"#city").val();
	var state 					= $(idPrefix+"#state").val();
	var country 				= $(idPrefix+"#country").val();
	var zipCode					= $(idPrefix+"#zipCode").val();

	// Contractor ID is multi-select option, So clubing the values and dropping it in one MySql table field
	$(idPrefix+"#contractorSearchSelected li").each(
		function() {
			contractor_id.push($(this).attr("data-contractorid"));
		}
	);

	if(contractor_id.length) {
		contractor_id = contractor_id.join(",");
	}

	// Adjuster ID is multi-select option, So clubing the values and dropping it in one MySql table field
	$(idPrefix+"#adjusterSearchSelected li").each(
		function() {
			adjuster_id.push($(this).attr("data-adjusterid"));
		}
	);

	if(adjuster_id.length) {
		adjuster_id = adjuster_id.join(",");
	}

	$.ajax({
		method: "POST",
		url: "/projects/projects/add",
		data: {
			projectTitle 		: projectTitle,
			description 		: description,
			associated_claim_num: associated_claim_num,
			project_type 		: project_type,
			start_date 			: start_date,
			end_date 			: end_date,
			project_status 		: project_status,
			project_budget		: project_budget,
			property_owner_id	: property_owner_id,
			contractor_id 		: contractor_id,
			adjuster_id			: adjuster_id,
			customer_id			: customer_id,
			remaining_budget	: remaining_budget,
			deductible			: deductible,
			project_lender		: project_lender,
			lend_amount 		: lend_amount,
			addressLine1 		: addressLine1,
			addressLine2 		: addressLine2,
			city 				: city,
			state 				: state,
			country 			: country,
			zipCode 			: zipCode
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
			$("#popupForAll").html(response);
			projectObj._projects.openDialog({"title" : "Edit Project"});
			projectObj._projects.setDropdownValue();
			projectObj._projects.setMandatoryFields();
			projectObj._projects.hideContractorDetails('all');
			projectObj._projects.hideAdjusterDetails('all');
			projectObj._projects.hideDropdowns
			projectObj._projects.getContractorDetails( $("#contractorIdDb").val() );
			projectObj._projects.getAdjusterDetails( $("#adjusterIdDb").val() );

			projectObj._projects.hideDropDowns();
			utilObj.setCustomerDataList();
			utilObj.setAdjusterDataList();
			utilObj.getAndSetCountryStatus("update_project_form");
			utilObj.setAsDateRangeFields({fromDateField: "start_date", toDateField: "end_date"});
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
	var idPrefix 				="#update_project_form ";
	var project_sno 			= $(idPrefix+"#project_sno").val();
	var projectTitle 			= $(idPrefix+"#projectTitle").val();
	var description 			= $(idPrefix+"#description").val();
	var associated_claim_num 	= $(idPrefix+"#associated_claim_num").val();
	var project_type			= $(idPrefix+"#project_type").val();
	var start_date 				= utilObj.toMySqlDateFormat($(idPrefix+"#start_date").val());
	var end_date 				= utilObj.toMySqlDateFormat($(idPrefix+"#end_date").val());
	var project_status			= $(idPrefix+"#project_status").val();
	var project_budget			= $(idPrefix+"#project_budget").val();
	var property_owner_id		= $(idPrefix+"#property_owner_id").val();
	var contractor_id			= [];
	var adjuster_id				= [];//$(idPrefix+"#adjuster_id").val();
	var customer_id				= $(idPrefix+"#customer_id").val();
	var remaining_budget		= $(idPrefix+"#remaining_budget").val();
	var deductible				= $(idPrefix+"#deductible").length ? $(idPrefix+"#deductible").val() : "";
	var project_lender			= $(idPrefix+"#project_lender").val();
	var lend_amount				= $(idPrefix+"#lend_amount").val();
	var addressLine1 			= $(idPrefix+"#addressLine1").val();
	var addressLine2 			= $(idPrefix+"#addressLine2").val();
	var city 					= $(idPrefix+"#city").val();
	var state 					= $(idPrefix+"#state").val();
	var country 				= $(idPrefix+"#country").val();
	var zipCode					= $(idPrefix+"#zipCode").val();

	// Contractor ID is multi-select option, So clubing the values and dropping it in one MySql table field
	$("#contractorSearchSelected li").each(
		function() {
			contractor_id.push($(this).attr("data-contractorid"));
		}
	);

	if(contractor_id.length) {
		contractor_id = contractor_id.join(",");
	}

	// Adjuster ID is multi-select option, So clubing the values and dropping it in one MySql table field
	$(idPrefix+"#adjusterSearchSelected li").each(
		function() {
			adjuster_id.push($(this).attr("data-adjusterid"));
		}
	);

	if(adjuster_id.length) {
		adjuster_id = adjuster_id.join(",");
	}

	$.ajax({
		method: "POST",
		url: "/projects/projects/update",
		data: {
			project_sno 			: project_sno,
			projectTitle 			: projectTitle,
			description 			: description,
			associated_claim_num 	: associated_claim_num,
			project_type 			: project_type,
			start_date 				: start_date,
			end_date 				: end_date,
			project_status 			: project_status,
			project_budget 			: project_budget,
			property_owner_id 		: property_owner_id,
			contractor_id 			: contractor_id,
			adjuster_id 			: adjuster_id,
			customer_id 			: customer_id,
			remaining_budget		: remaining_budget,
			deductible				: deductible,
			project_lender 			: project_lender,
			lend_amount 			: lend_amount,
			addressLine1 			: addressLine1,
			addressLine2 			: addressLine2,
			city					: city,
			state 					: state,
			country					: country,
			zipCode					: zipCode

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

project.prototype.deleteRecord = function() {
	
	var deleteConfim = confirm("Do you want to delete this project");

	if(!deleteConfim)
		return;

	$.ajax({
		method: "POST",
		url: "/projects/projects/deleteRecord",
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
	
	// Project Details View
	setTimeout(function() {
		projectObj._projects.getProjectDetails();
		setTimeout(function() {
			projectObj._projects.getProjectTasksList();
			projectObj._projects.getProjectNotesList();
			projectObj._projects.getProjectDocumentList();
		}, 1000);
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
			$( "#projectDescrAccordion" ).accordion(
				{
					collapsible : true,  
					icons 		: { "header": "ui-icon-plus", "activeHeader": "ui-icon-minus" },
					active 		: false
				}
			);
			$( "#contractor_accordion" ).accordion(
				{
					collapsible : true,  
					icons 		: { "header": "ui-icon-triangle-1-e", "activeHeader": "ui-icon-triangle-1-s" },
					active 		: false
				}
			);
			$( "#partner_accordion" ).accordion(
				{
					collapsible : true,  
					icons 		: { "header": "ui-icon-triangle-1-e", "activeHeader": "ui-icon-triangle-1-s" },
					active 		: false
				}
			);
			//projectObj._projects.budgetFormat();
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
			var taskCount = $("#tasksCount").val();
			taskCount = taskCount != "" || taskCount > 0 ? " ("+taskCount+")" : 0;
			$("#taskCountDisplay").html(taskCount);
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
				$("#note_content").html(response);

				projectObj._notes.noteRequestSent = projectObj._notes.noteListStartRecord;
				projectObj._notes.noteListStartRecord += 5;

				var noteCount = $("#notesCountForProject").val();
				noteCount = noteCount != "" || noteCount > 0 ? " ("+noteCount+")" : 0;
				$("#notesCountForProjectDisplay").html(noteCount);
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
				$("#attachment_content").html(response);

				var docsCount = $("#docsCount").val();
				docsCount = docsCount != "" || docsCount > 0 ? " ("+docsCount+")" : 0;
				$("#docsCountDisplay").html(docsCount);
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
			projectId: projectObj._projects.projectId
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
		url: "/projects/docs/deleteRecord",
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
	var deleteConfim = confirm("Do you want to delete the task");

	if(!deleteConfim)
		return;

	$.ajax({
		method: "POST",
		url: "/projects/tasks/deleteRecord",
		data: {
			task_id 	: 	task_id,
			project_id 	: 	project_id
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
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
		url: "/projects/notes/deleteRecord",
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
			projectObj._projects.getOwnerList( $("#contractorIdDb").val() );
			
			dateOptions = {
				fromDateField 	: "task_start_date",
				toDateField		: "task_end_date"
			}
			utilObj.setAsDateRangeFields(dateOptions);
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
			projectObj._projects.getOwnerList( $("#contractorIdDb").val() );
			projectObj._tasks.setDropdownValue();

			dateOptions = {
				fromDateField 	: "task_start_date",
				toDateField		: "task_end_date"
			}
			utilObj.setAsDateRangeFields(dateOptions);
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
	task_start_date 			= utilObj.toMySqlDateFormat($("#task_start_date").val());
	task_end_date 				= utilObj.toMySqlDateFormat($("#task_end_date").val());
	task_status					= $("#task_status").val();
	task_owner_id				= "";
	task_percent_complete		= $("#task_percent_complete").val();
	task_dependency				= $("#task_dependency").val();
	task_trade_type				= $("#task_trade_type").val();

	var ownerSelected = $("input[type='radio'][name='optionSelectedOwner']:checked");
	if (ownerSelected.length > 0) {
	    task_owner_id = ownerSelected.val();
	}

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
	task_start_date 		= utilObj.toMySqlDateFormat($("#task_start_date").val());
	task_end_date 			= utilObj.toMySqlDateFormat($("#task_end_date").val());
	task_status 			= $("#task_status").val();
	task_owner_id 			= "";
	task_percent_complete	= $("#task_percent_complete").val();
	task_dependency			= $("#task_dependency").val();
	task_trade_type			= $("#task_trade_type").val();

	var ownerSelected = $("input[type='radio'][name='optionSelectedOwner']:checked");
	if (ownerSelected.length > 0) {
	    task_owner_id = ownerSelected.val();
	}

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

project.prototype.openDialog = function( options, popupType ) {
	if(popupType && popupType != "" ) {
		projectObj._projects.openDialog2( options );
	} else {
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
}

project.prototype.openDialog2 = function( options ) {
	if(typeof(this.popupDialog2) == "undefined") {
		this.popupDialog2 = $( "#popupForAll2" ).dialog({
	      	autoOpen: false,
	      	maxHeight : 600,
	      	width: 600,
	      	modal: true
	  	});
	}
  	this.popupDialog2.dialog("open");
  	if( typeof(options) != "undefined" ) {
  		this.popupDialog2.dialog("option", "title", options.title); 
  	}
}

project.prototype.closeDialog = function( options ) {
	var popupType = (options && options.popupType) ? options.popupType : "";
	$( "#popupForAll"+popupType ).dialog( "close" );
}

project.prototype.hideContractorDetails = function(hide) {
	if(!hide || hide == "" || hide == "all" || hide == "results") {
		$(".contractor-search-result").hide();
	}
	if(!hide || hide == "" || hide == "all" || hide == "selected") {
		$(".contractor-search-selected").hide();
	}
}

project.prototype.showContractorDetails = function(show) {
	if(!show || show == "" || show == "all" || show == "results") {
		$(".contractor-search-result").show();
	}
	if(!show || show == "" || show == "all" || show == "selected") {
		$(".contractor-search-selected").show();
	}
}

project.prototype.hideAdjusterDetails = function(hide) {
	if(!hide || hide == "" || hide == "all" || hide == "results") {
		$(".adjuster-search-result").hide();
	}
	if(!hide || hide == "" || hide == "all" || hide == "selected") {
		console.log("hideFN");
		$(".adjuster-search-selected").hide();
	}
}

project.prototype.showAdjusterDetails = function(show) {
	if(!show || show == "" || show == "all" || show == "results") {
		$(".adjuster-search-result").show();
	}
	if(!show || show == "" || show == "all" || show == "selected") {
		$(".adjuster-search-selected").show();
	}
}

project.prototype.hideDropDowns = function() {
	$(".adjuster-search-result").hide();
	$(".customer-search-result").hide()
}

project.prototype.getContractorDetails = function( records ) {
	$.ajax({
		method: "POST",
		url: "/projects/contractors/getList",
		data: {
			records : records
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				var contractors = {
					"list" 			: response["contractors"],
					"appendTo"		: "contractorSearchSelected",
					"type"			: "selectedList",
					"prefixId" 		: "contractorSearch"
				}
				utilObj.createContractorOptionsList(contractors);
				projectObj._projects.selectedContractor = []; 
		    	$("#contractorSearchSelected li").each(
					function() {
						projectObj._projects.selectedContractor.push($(this).attr("id"));
					}
				);
				projectObj._projects.showContractorDetails("selected");
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
		$("#contractorId").val($("#contractorIdDb").val().split(","));
	}, 100);
}

project.prototype.updateContractorSelectionList = function() {
	$.ajax({
		method: "POST",
		url: "/projects/contractors/getList",
		data: {},
		success: function( response ) {
			response = $.parseJSON(response);
			$("#contractorId").children().remove();
			if(response.status == "success") {
				contractors = response["contractors"];
				for(var i =0 ; i < contractors.length; i++) {
					$('#contractorId').append($('<option>', {
					    value: contractors[i].id,
					    text: contractors[i].name
					}));
				}
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

project.prototype.setSelectedContractor = function() {
	$("#contractorIdDb").val($("#contractorId").val().join(","));
}

project.prototype.getContractorListUsingServiceZip = function( prefix ) {
	var serviceZip = $("#contractorZipCode").val();

	$.ajax({
		method: "POST",
		url: "/projects/contractors/getList",
		data: {
			serviceZip: serviceZip
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				response.contractorList;
				$("#contractorSearchResult").children().remove();				

				var contractors = {
					"list" 			: response["contractors"],
					"appendTo"		: "contractorSearchResult",
					"type"			: "searchList",
					"excludeList" 	: projectObj._projects.selectedContractor,
					"prefixId" 		: "contractorSearch",
					"actionButton"	: "plus"
				}
				utilObj.createContractorOptionsList(contractors);
				//$('#contractorSearchResult li .ui-icon').hide();

				projectObj._projects.showContractorDetails('all');
			
			
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

/*project.prototype.allowDrop = function(ev) {
    ev.preventDefault();
}

project.prototype.drag = function (ev) {
    ev.dataTransfer.setData("text", ev.target.id);
}

project.prototype.drop = function(ev) {
	if(ev.toElement.id == "contractorSearchSelected" || ev.toElement.id == "contractorSearchResult") {
		ev.preventDefault();
	    var data = ev.dataTransfer.getData("text");
	    ev.target.appendChild(document.getElementById(data));
    }
    if(ev.toElement.id == "contractorSearchSelected") {
    	$("#contractorSearchSelected li .ui-icon-plus").removeClass("ui-icon-plus").addClass("ui-icon-minus");
    	$("#contractorSearchSelected .ui-state-default").removeClass("ui-state-default").addClass("ui-state-highlight");
    	projectObj._projects.selectedContractor = []; 
    	$("#contractorSearchSelected li").each(
			function() {
				projectObj._projects.selectedContractor.push($(this).attr("id"));
			}
		);
		$('#contractorSearchSelected li .ui-icon').show();
    }
    if(ev.toElement.id == "contractorSearchResult") {
    	$("#contractorSearchResult li .ui-icon-minus").removeClass("ui-icon-minus").addClass("ui-icon-plus");
    	$("#contractorSearchResult .ui-state-highlight").removeClass("ui-state-highlight").addClass("ui-state-default");
    	$('#contractorSearchResult li .ui-icon').hide();
    }
}*/

/*project.prototype.removeSelectedContractor = function(ev, element) {
	if(element.className.indexOf("ui-icon-minus") >= 0 ) {
		var id = element.parentElement.id;
		projectObj._projects.selectedContractor.splice(projectObj._projects.selectedContractor.indexOf(id),1);
		element.parentElement.remove();
	}
}*/

/*
	Contractor Search Add and Remove
	for create and Edit form
*/
project.prototype.searchContractorAction = function( events ) {
	var element 	= event.target;
	var clickedId 	= $(element).attr("data-contractorid");
	var prefixId 		= $(element).attr("data-prefixid");
	var actionToDo 	= $(element).hasClass("ui-icon-plus") ? "add" : "";
	actionToDo 		= $(element).hasClass("ui-icon-minus") ? "remove" : actionToDo;

	if(actionToDo == "add") {
		$("#"+prefixId+"Selected").append($("#"+prefixId+clickedId));
		$("#"+prefixId+"Selected li .ui-icon-plus").removeClass("ui-icon-plus").addClass("ui-icon-minus");
		modifyactionToDo = "remove";	
	} else if (actionToDo == "remove") {
		element.parentElement.remove();
	}

	if(actionToDo == "add" || actionToDo == "remove") {
		projectObj._projects.selectedContractor = []; 
		$("#"+prefixId+"Selected li").each(
			function() {
				projectObj._projects.selectedContractor.push($(this).attr("data-contractorid"));
			}
		);
	}

	if(!$("#"+prefixId+"Result").children().length) {
		$(".contractor-search-result").hide();
	}
	if(!$("#"+prefixId+"Selected").children().length) {
		$(".contractor-search-selected").hide();
	}	
}

project.prototype.getAdjusterListUsingNameCompany = function( prefix ) {
	var nameOrComp = $("#searchAdjusterName").val();

	$.ajax({
		method: "POST",
		url: "/projects/partners/getList",
		data: {
			companyName : nameOrComp,
			name 		: nameOrComp
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				response.adjusterList;
				$("#adjusterSearchResult").children().remove();				

				var adjusters = {
					"list" 			: response["partners"],
					"appendTo"		: "adjusterSearchResult",
					"type"			: "searchList",
					"excludeList" 	: projectObj._projects.selectedAdjuster,
					"prefixId" 		: "adjusterSearch",
					"actionButton"	: "plus"
				}
				utilObj.createAdjusterOptionsList(adjusters);
				projectObj._projects.showAdjusterDetails('all');
			
			
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

project.prototype.getAdjusterDetails = function( records ) {
	$.ajax({
		method: "POST",
		url: "/projects/partners/getList",
		data: {
			records : records
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				var adjusters = {
					"list" 			: response["partners"],
					"appendTo"		: "adjusterSearchSelected",
					"type"			: "selectedList",
					"prefixId" 		: "adjusterSearch"
				}
				utilObj.createAdjusterOptionsList( adjusters );
				projectObj._projects.selectedAdjuster = []; 
		    	$("#adjusterSearchSelected li").each(
					function() {
						projectObj._projects.selectedAdjuster.push($(this).attr("id"));
					}
				);
				projectObj._projects.showAdjusterDetails("selected");
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

project.prototype.searchAdjusterAction = function( events ) {
	var element 	= event.target;
	var clickedId 	= $(element).attr("data-adjusterid");
	var prefixId 		= $(element).attr("data-prefixid");
	var actionToDo 	= $(element).hasClass("ui-icon-plus") ? "add" : "";
	actionToDo 		= $(element).hasClass("ui-icon-minus") ? "remove" : actionToDo;

	if(actionToDo == "add") {
		$("#"+prefixId+"Selected").append($("#"+prefixId+clickedId));
		$("#"+prefixId+"Selected li .ui-icon-plus").removeClass("ui-icon-plus").addClass("ui-icon-minus");
		modifyactionToDo = "remove";	
	} else if (actionToDo == "remove") {
		element.parentElement.remove();
	}

	if(actionToDo == "add" || actionToDo == "remove") {
		projectObj._projects.selectedAdjuster = []; 
		$("#"+prefixId+"Selected li").each(
			function() {
				projectObj._projects.selectedAdjuster.push($(this).attr("data-adjusterid"));
			}
		);
	}

	if(!$("#"+prefixId+"Result").children().length) {
		$(".adjuster-search-result").hide();
	}
	if(!$("#"+prefixId+"Selected").children().length) {
		console.log("setActionFN");
		$(".adjuster-search-selected").hide();
	}
	
}

project.prototype.getOwnerList = function( records ) {
	$.ajax({
		method: "POST",
		url: "/projects/contractors/getList",
		data: {
			records : records
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				var contractors = {
					"list" 			: response["contractors"],
					"appendTo"		: "ownerSearchResult",
					"type"			: "ownerList",
					"prefixId" 		: "ownerSearch"
				}
				utilObj.createContractorOptionsList(contractors);
				projectObj._tasks.setOwnerOption();
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

project.prototype.viewOnlyBudget = function () {
	$.ajax({
		method: "POST",
		url: "/projects/projects/viewOnlyBudget",
		data: {
			projectId :  projectObj._projects.projectId
		},
		success: function( response ) {
			$("#viewOneProjectBudget").html(response);
			//projectObj._projects.budgetFormat();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

project.prototype.showCustomerListInDropDown = function() {
	var customer = $("#searchCustomerName").val();
	$(".customer-search-result").show();
	$("#customerNameList").show();

	for(var i = 0; i < $("#customerNameList").children().length; i++) {
		if($($("#customerNameList").children()[i]).text().indexOf(customer) > -1 ) {
			$($("#customerNameList").children()[i]).show();
		} else {
			$($("#customerNameList").children()[i]).hide();
		}
	}
}

project.prototype.showAdjusterListInDropDown = function() {
	var adjuster = $("#searchAdjusterName").val();
	$(".adjuster-search-result").show();
	$("#adjusterNameList").show();

	for(var i = 0; i < $("#adjusterNameList").children().length; i++) {
		if($($("#adjusterNameList").children()[i]).text().indexOf(adjuster) > -1 ) {
			$($("#adjusterNameList").children()[i]).show();
		} else {
			$($("#adjusterNameList").children()[i]).hide();
		}
	}
}

project.prototype.setCustomerId = function(event, element, options) {
	$("#searchCustomerName").val(options.first_name+" "+options.last_name);
	$("#customer_id").val(options.searchId);
}

project.prototype.setAdjusterId = function(event, element, options) {
	$("#searchAdjusterName").val(options.first_name+" "+options.last_name);
	$("#adjuster_id").val(options.searchId);
}

project.prototype.budgetFormat = function() {
	$(".dollers").each(function() {
		console.log($(this).html());
		$(this).html("$ "+utilObj.toDisplayNumberFormat($(this).html()));
	})
}