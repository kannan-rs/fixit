function issues() {
	
}

issues.prototype.createForm = function( options ) {

	event.stopPropagation();
	
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";
	var projectId 	= options && options.projectId ? options.projectId : "";

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
			projectId 	: projectId
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Add Issue"}, popupType);
			} else{
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
	var validator = $( "#create_issue_form" ).validate();

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
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._issues.viewOne(response.insertedId, openAs, popupType);
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

issues.prototype.viewOne = function( issueId, openAs, popupType ) {
	this.issueId 	= issueId;
	popupType 		= session.module == "projects" ? "2" : "";
	openAs 			= session.module == "projects" ? "popup" : "";
	
	$.ajax({
		method: "POST",
		url: "/projects/issues/viewOne",
		data: {
			issueId 		: projectObj._issues.issueId,
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

	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";
	var projectId 	= options && options.projectId ? options.projectId : "";
	var taskId 		= options && options.taskId ? options.taskId : "";

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

	$.ajax({
		method: "POST",
		url: "/projects/issues/editForm",
		data: {
			'issueId' : projectObj._issues.issueId,
			'openAs' 		: openAs,
			'popupType' 	: popupType
			
		},
		success: function( response ) {
			$("#popupForAll"+popupType).html(response);
			projectObj._projects.openDialog({"title" : "Edit Issue"}, popupType);
			projectObj._issues.setPrefContact();
			utilObj.setIssueStatus("issueStatus", "issueStatusDb");
			utilObj.getAndSetCountryStatus("update_issue_form");

		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

issues.prototype.updateValidate = function() {
	var validator = $( "#update_issue_form" ).validate();

	if(validator.form()) {
		projectObj._issues.updateSubmit();
	}
};

issues.prototype.updateSubmit = function() {
	var idPrefix 				= "#update_issue_form ";
	var issueId			= $(idPrefix+"#issueId").val();
	var name 					= $(idPrefix+"#name").val();
	var company 				= $(idPrefix+"#company").val();
	var type 					= $(idPrefix+"#type").val();
	var license 				= $(idPrefix+"#license").val();
	var bbb 					= $(idPrefix+"#bbb").val();
	var status 					= $(idPrefix+"#status").val();
	var addressLine1 			= $(idPrefix+"#addressLine1").val();
	var addressLine2 			= $(idPrefix+"#addressLine2").val();
	var city 					= $(idPrefix+"#city").val();
	var state 					= $(idPrefix+"#state").val();
	var country 				= $(idPrefix+"#country").val();
	var zipCode 				= $(idPrefix+"#zipCode").val();
	var emailId 				= $(idPrefix+"#emailId").val();
	var contactPhoneNumber 		= $(idPrefix+"#contactPhoneNumber").val();
	var mobileNumber 			= $(idPrefix+"#mobileNumber").val();
	var prefContact 			= "";
	var websiteURL 				= $(idPrefix+"#websiteURL").val();
	var serviceZip 				= $(idPrefix+"#serviceZip").val();

	$(idPrefix+"input[name=prefContact]:checked").each(
		function() {
			prefContact += prefContact != "" ? (","+this.value) : this.value;
		}
	);

	$.ajax({
		method: "POST",
		url: "/projects/issues/update",
		data: {
			issueId 			: issueId,
			name 					: name,
			company 				: company,
			type 					: type,
			license 				: license,
			bbb 					: bbb,
			status 					: status,
			addressLine1 			: addressLine1,
			addressLine2 			: addressLine2,
			city 					: city,
			state 					: state,
			country 				: country,
			zipCode 				: zipCode,
			emailId 				: emailId,
			contactPhoneNumber 		: contactPhoneNumber,
			mobileNumber 			: mobileNumber,
			prefContact 			: prefContact,
			websiteURL 				: websiteURL,
			serviceZip 				: serviceZip
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				$(".ui-button").trigger("click");
				alert(response.message);
				projectObj._issues.viewOne(response.updatedId);
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
	var contractors = {
		"list" 				: response["contractorDetails"],
		"appendTo"			: "issueContractorResult",
		"type"				: "ownerList",
		"prefixId" 			: "issueContractor",
		"radioOptionName" 	: "issueRadioContractorSelected"
	}
	utilObj.createContractorOptionsList(contractors);

	var adjusters = {
		"list" 				: response["adjusterDetails"],
		"appendTo"			: "issueAdjusterResult",
		"type"				: "ownerList",
		"prefixId" 			: "issueAdjuster",
		"radioOptionName" 	: "issueRadioAdjusterSelected"
	}
	utilObj.createAdjusterOptionsList(adjusters);

	if(response["customerDetails"]) {
		$("#issueAssignedToCustomer").val(response["customerDetails"][0]["first_name"]+" "+response["customerDetails"][0]["last_name"]);
		this.issueAssignedToCustomerId = response["customerDetails"]["user_sno"];
	}
}
