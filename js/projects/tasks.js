/**
	Tasks functions
*/
function task() {

};

task.prototype.viewAll = function( projectId ) {
	projectObj.resetCounter("docs");
	projectObj.resetCounter("notes");
	projectObj.clearRest();
	projectObj.toggleAccordiance("task");
	$.ajax({
		method: "POST",
		url: "/projects/tasks/viewAll",
		data: {
			projectId: projectId
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
};

task.prototype.createForm = function( projectId ) {
	projectObj.clearRest();
	projectObj.toggleAccordiance("task");
	$.ajax({
		method: "POST",
		url: "/projects/tasks/createForm",
		data: {
			projectId: projectId
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
};

task.prototype.createValidate =  function () {
	$("#create_task_form").validate({
		rules: {
			task_end_date: {
				greaterThanOrEqualTo: "#task_start_date"
			}
		},
		messages: {
			task_end_date: {
				greaterThanOrEqualTo: "End Date need to be greater that start date"
			}
		}
	});

	var validator = $( "#create_task_form" ).validate();


	if(validator.form()) {
		projectObj._tasks.createSubmit();
	}
};

task.prototype.createSubmit = function() {
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
			parentId: 				parentId,
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
				projectObj._tasks.viewOne(response.insertedId);
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

task.prototype.editTask = function(taskId) {
	projectObj.clearRest();
	projectObj.toggleAccordiance("task");
	$.ajax({
		method: "POST",
		url: "/projects/tasks/editForm",
		data: {
			'taskId' : taskId
		},
		success: function( response ) {
			$("#project_content").html(response);
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

task.prototype.updateValidate = function( viewFor ) {
	$("#update_task_form").validate({
		rules: {
			task_end_date: {
				greaterThanOrEqualTo: "#task_start_date"
			}
		},
		messages: {
			task_end_date: {
				greaterThanOrEqualTo: "End Date need to be greater that start date"
			}
		}
	});
	
	var validator = $( "#update_task_form" ).validate();

	if(validator.form()) {
		if(!viewFor) {
			projectObj._tasks.updateSubmit();
		} else {
			projectObj._projects.taskUpdateSubmit();
		}
	}
};

task.prototype.updateSubmit = function() {
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
				projectObj._tasks.viewOne(response.updatedId);
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

task.prototype.delete = function(task_id, project_id, viewFor) {
	$.ajax({
		method: "POST",
		url: "/projects/tasks/delete",
		data: {
			task_id 	: 	task_id,
			project_id 	: 	project_id,
			viewFor 	: 	viewFor
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._tasks.viewAll();
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

task.prototype.viewOne = function( taskId ) {
	projectObj.clearRest();
	projectObj.toggleAccordiance("task");
	$.ajax({
		method: "POST",
		url: "/projects/tasks/viewOne",
		data: {
			taskId: taskId
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
};

task.prototype.setDropdownValue = function() {
	var db_task_status = $("#db_task_status").val();

	$("#task_status").val(db_task_status);
};

task.prototype.setPercentage = function(statusValue) {
	if(statusValue == "completed") {
		$("#task_percent_complete").val("100").attr("disabled",true);
	} else {
		if($("#task_percent_complete").val() == "100" || $("#task_percent_complete").val() != $("#task_percent_complete").attr("defaultValue")) {
			$("#task_percent_complete").val($("#task_percent_complete").attr("defaultValue")).attr("disabled",false);
		}
	}
};

task.prototype.percentageChange = function (percentageValue) {
	$("#task_percent_complete").attr("defaultValue", percentageValue);
}