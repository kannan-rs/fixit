function issues() {	
};

issues.prototype.validationRules = function() {
	return {
		issueName : {
			required : true
		},
		issueDescr : {
			required : true
		},
		assignedToUserType : {
			required : false
		},
		issueAssignedToCustomer : {
			required : true
		},
		issueContractorResult : {
			required : false
		},
		issueAdjusterResult : {
			required : false
		},
		issueFromdate : {
			required : true
		},
		issueStatus : {
			required : true
		},
		issueNotes : {
			required : true
		},
		contactPhoneNumber : {
			digits : true	
		},
		mobileNumber : {
			digits : true	
		}
	};
};

issues.prototype.errorMessage = function() {
	return {
		issueName : {
			required : _lang.english.errorMessage.issueForm.issueName
		},
		issueDescr : {
			required : _lang.english.errorMessage.issueForm.issueDescr
		},
		assignedToUserType : {
			required : _lang.english.errorMessage.issueForm.assignedToUserType
		},
		issueAssignedToCustomer : {
			required : _lang.english.errorMessage.issueForm.issueAssignedToCustomer
		},
		issueContractorResult : {
			required : _lang.english.errorMessage.issueForm.issueContractorResult
		},
		issueAdjusterResult : {
			required : _lang.english.errorMessage.issueForm.issueAdjusterResult
		},
		issueFromdate : {
			required : _lang.english.errorMessage.issueForm.issueFromdate
		},
		issueStatus : {
			required : _lang.english.errorMessage.issueForm.issueStatus
		},
		issueNotes : {
			required : _lang.english.errorMessage.issueForm.issueNotes
		},
		contactPhoneNumber : {
			digits : _lang.english.errorMessage.issueForm.contactPhoneNumber
		},
		mobileNumber : {
			digits : _lang.english.errorMessage.issueForm.mobileNumber
		}
	};
};

issues.prototype.createForm = function( options ) {

	event.stopPropagation();
	
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";
	var projectId 	= options && options.projectId ? options.projectId : "";
	var taskId 		= options && options.taskId ? options.taskId : "";

	if(!openAs) {
		projectObj.clearRest();
		projectObj.toggleAccordiance("issues", "new");
	}
	
	$.ajax({
		method: "POST",
		url: "/projects/issues/createForm",
		data: {
			openAs 		: openAs,
			popupType 	: popupType,
			projectId 	: projectId,
			taskId 		: taskId
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Add Issue"}, popupType);
			} else {
				$("#issue_content").html(response);
			}

			utilObj.setAsDateFields({"dateField": "issueFromdate"})
			utilObj.setIssueStatus("issueStatus", "issueStatusDb");
			utilObj.setIssueAssignedTo("assignedToUserType", "assignedToUserTypeDB");
			projectObj._issues.showAssignedToOptions();
			projectObj._issues.getAndListAssignees( projectId );
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

issues.prototype.createValidate =  function ( openAs, popupType ) {
	var validator = $( "#create_issue_form" ).validate({
		rules: this.validationRules(),
		messages: this.errorMessage()
	});

	if(validator.form()) {
		projectObj._issues.createSubmit( openAs, popupType );
	}
};

issues.prototype.createSubmit = function( openAs, popupType ) {
	var idPrefix 				= "#create_issue_form "
	var issueName 				= $(idPrefix+"#issueName").val();
	var issueDescr 				= $(idPrefix+"#issueDescr").val();
	var assignedToUserType		= $(idPrefix+"#assignedToUserType").val();
	var issueFromdate 			= utilObj.toMySqlDateFormat($(idPrefix+"#issueFromdate").val());
	var issueStatus 			= $(idPrefix+"#issueStatus").val();
	var issueNotes 				= $(idPrefix+"#issueNotes").val();
	var issueProjectId 			= $(idPrefix+"#issueProjectId").val();
	var issueTaskId 			= $(idPrefix+"#issueTaskId").val();

	var assignedToContractorId 	= "";
	var assignedToAdjusterId 	= "";
	var assignedToCustomerId 	= this.issueAssignedToCustomerId;

	$(idPrefix+"input[name=issueRadioContractorSelected]:checked").each(
		function() {
			assignedToContractorId = this.value;
		}
	);

	$(idPrefix+"input[name=issueRadioAdjusterSelected]:checked").each(
		function() {
			assignedToAdjusterId = this.value;
		}
	);

	$.ajax({
		method: "POST",
		url: "/projects/issues/add",
		data: {
			issueName 				: issueName,
			issueDescr 				: issueDescr,
			issueProjectId 			: issueProjectId,
			issueTaskId 			: issueTaskId,
			assignedToUserType		: assignedToUserType,
			issueFromdate 			: issueFromdate,
			issueStatus 			: issueStatus,
			issueNotes 				: issueNotes,
			assignedToContractorId	: assignedToContractorId,
			assignedToAdjusterId 	: assignedToAdjusterId,
			assignedToCustomerId 	: assignedToCustomerId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			projectObj._issues.viewAll({projectId : issueProjectId, taskId : issueTaskId});
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._issues.viewOne(response.insertedId, openAs, popupType);
			} else if(response.status.toLowerCase() == "error") {
				alert(response.message);
			}
			

			if(projectObj._projects.projectId) {
				projectObj._projects.viewOne( issueProjectId, {"triggeredBy" : "issues", "taskId": issueTaskId} );
			} else {
				projectObj._projects.viewAll();	
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

issues.prototype.viewOne = function( issueId, openAs, popupType ) {
	//this.issueId 	= issueId;
	popupType 		= session.page == "projects" ? "2" : "";
	openAs 			= session.page == "projects" ? "popup" : "";
	
	$.ajax({
		method: "POST",
		url: "/projects/issues/viewOne",
		data: {
			issueId 		: issueId,
			openAs 			: openAs,
			popupType 		: popupType
		},
		success: function( response ) {
			if( openAs && openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Issue Details"}, popupType);
			} else {
				$("#issue_content").html(response);
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

issues.prototype.viewAll = function( options ) {
	//var openAs 		= options && options.openAs ? options.openAs : "";
	//var popupType 	= options && options.popupType ? options.popupType : "";
	/*var projectId 	= options && options.projectId ? options.projectId : ( this.projectId ? this.projectId : "" );
	var taskId 		= options && options.taskId ? options.taskId : ( this.taskId ? this.taskId : "" );*/
    var projectId 	= options && options.projectId ? options.projectId : "" ;
	var taskId 		= options && options.taskId ? options.taskId : "" ;
	var popupType 	= "";
	var openAs 		= session.page == "projects" ? "popup" : "";

	/*this.projectId 	= projectId;
	this.taskId 	= taskId;*/

	if(!projectId && !taskId) {
		return;
	}

	$.ajax({
		method: "POST",
		url: "/projects/issues/viewAll",
		data: {
			openAs 		: openAs,
			popupType 	: popupType,
			projectId 	: projectId,
			taskId 		: taskId
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Issues"}, popupType);
			} else {
				$("#issue_content").html(response);
			}
			projectObj._issues.showIssuesList();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

issues.prototype.editForm = function( options ) {
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";
	var issueId 	= options && options.issueId ? options.issueId : "";
	var projectId 	= options && options.projectId ? options.projectId : "";
	var taskId 		= options && options.taskId ? options.taskId : "";

	$.ajax({
		method: "POST",
		url: "/projects/issues/editForm",
		data: {
			'issueId' 		: issueId,
			'openAs' 		: openAs,
			'popupType' 	: popupType,
			'projectId' 	: projectId,
			'taskId' 		: taskId
			
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html(response);
				projectObj._projects.openDialog({"title" : "Edit Issue"}, popupType);
			} else {
				$("#issue_content").html(response);
			}
			
			utilObj.setAsDateFields({"dateField": "issueFromdate"})
			utilObj.setIssueStatus("issueStatus", "issueStatusDb");
			utilObj.setIssueAssignedTo("assignedToUserType", "assignedToUserTypeDB");
			projectObj._issues.showAssignedToOptions();
			projectObj._issues.getAndListAssignees( projectId );
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

issues.prototype.updateValidate = function( openAs, popupType ) {
	var validator = $( "#update_issue_form" ).validate({
		rules 		: this.validationRules(),
		messages 	: this.errorMessage()
	});

	if(validator.form()) {
		projectObj._issues.updateSubmit( openAs, popupType );
	}
};

issues.prototype.updateSubmit = function( openAs, popupType ) {
	var idPrefix 				= "#update_issue_form ";
	var issueId 				= $(idPrefix+"#issueId").val();
	var issueProjectId 			= $(idPrefix+"#issueProjectId").val();
	var issueTaskId 				= $(idPrefix+"#issueTaskId").val();
	var issueName 				= $(idPrefix+"#issueName").val();
	var issueDescr 				= $(idPrefix+"#issueDescr").val();
	var assignedToUserTypeDB	= $(idPrefix+"#assignedToUserTypeDB").val();
	var assignedToUserType		= $(idPrefix+"#assignedToUserType").val();
	var assignedToUserDB 		= $(idPrefix+"#assignedToUserDB").val();
	var issueFromdate 			= utilObj.toMySqlDateFormat($(idPrefix+"#issueFromdate").val());
	var issueStatus 			= $(idPrefix+"#issueStatus").val();
	var issueNotes 				= $(idPrefix+"#issueNotes").val();

	var assignedToContractorId 	= "";
	var assignedToAdjusterId 	= "";
	var assignedToCustomerId 	= this.issueAssignedToCustomerId;

	$(idPrefix+"input[name=issueRadioContractorSelected]:checked").each(
		function() {
			assignedToContractorId = this.value;
		}
	);

	$(idPrefix+"input[name=issueRadioAdjusterSelected]:checked").each(
		function() {
			assignedToAdjusterId = this.value;
		}
	);

	$.ajax({
		method: "POST",
		url: "/projects/issues/update",
		data: {
			issueId 				: issueId,
			issueProjectId 			: issueProjectId,
			issueTaskId 			: issueTaskId,
			issueName 				: issueName,
			issueDescr 				: issueDescr,
			assignedToUserType		: assignedToUserType,
			issueFromdate 			: issueFromdate,
			issueStatus 			: issueStatus,
			issueNotes 				: issueNotes,
			assignedToContractorId	: assignedToContractorId,
			assignedToAdjusterId 	: assignedToAdjusterId,
			assignedToCustomerId 	: assignedToCustomerId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._issues.viewOne(response.updatedId, openAs, popupType);
			} else if(response.status.toLowerCase() == "error") {
				alert(response.message);
			}
			projectObj._issues.viewAll({projectId : issueProjectId, taskId : issueTaskId});
			
			if(projectObj._projects.projectId) {
				projectObj._projects.viewOne( issueProjectId );
			} else {
				projectObj._projects.viewAll();	
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

issues.prototype.deleteRecord = function() {
	$.ajax({
		method: "POST",
		url: "/projects/issues/deleteRecord",
		data: {
			issueId: projectObj._issues.issueId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._issues.viewAll();
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

issues.prototype.showAssignedToOptions = function() {
	var assignetTo	= $("#assignedToUserType").val();

	$("#assignedToUserCustomer").hide();
	$("#assignedToUserContractor").hide();
	$("#assignedToUserAdjuster").hide();

	if(assignetTo && assignetTo != "") {
		var suffix = assignetTo.capitalizeFirstLetter();
		$("#assignedToUser"+suffix).show();
	}
};

issues.prototype.getAndListAssignees = function( projectId ) {
	$.ajax({
		method: "POST",
		url: "/projects/projects/getAssignees",
		data: {
			projectId: projectId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				projectObj._issues.setAssignees( response );
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

issues.prototype.setAssignees = function ( response ) {
	var assignedToUserTypeDB 	= $("#assignedToUserTypeDB").val();
	var assignedToUserDB 		= $("#assignedToUserDB").val();

	var contractors = {
		"list" 				: response["contractorDetails"],
		"appendTo"			: "issueContractorResult",
		"type"				: "ownerList",
		"prefixId" 			: "issueContractor",
		"radioOptionName" 	: "issueRadioContractorSelected"
	}

	if(assignedToUserDB != "" && assignedToUserTypeDB == "contractor") {
		contractors.selectId = assignedToUserDB;
	}

	utilObj.createContractorOptionsList(contractors);

	var adjusters = {
		"list" 				: response["adjusterDetails"],
		"appendTo"			: "issueAdjusterResult",
		"type"				: "ownerList",
		"prefixId" 			: "issueAdjuster",
		"radioOptionName" 	: "issueRadioAdjusterSelected"
	}

	if(assignedToUserDB != "" && assignedToUserTypeDB == "adjuster") {
		adjusters.selectId = assignedToUserDB;
	}

	utilObj.createAdjusterOptionsList(adjusters);

	if(response["customerDetails"]) {
		$("#issueAssignedToCustomer").val(response["customerDetails"][0]["first_name"]+" "+response["customerDetails"][0]["last_name"]);
		this.issueAssignedToCustomerId = response["customerDetails"]["user_sno"];
	}
}


issues.prototype.showIssuesList = function ( event ) {
	var options = "open";

	if( event ) {
		options = event.target.getAttribute("data-option");
		if(options) {
			$($(".issues.internal-tab-as-links").children()).removeClass("active");
			$(".issues-table-list .row").hide();
			$(event.target).addClass("active");
		} 
	} else {
		$($(".issues.internal-tab-as-links").children()).removeClass("active");
		$(".issues-table-list .row").hide();
		$($(".issues.internal-tab-as-links").children()[0]).addClass("active")
	}

	if(options == "all") {
		$(".issues-table-list .row").show();
	} else if (options != "") {
		$(".issues-table-list .row."+options).show();
	}
}