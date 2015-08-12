function contractors() {
	
}

contractors.prototype.createForm = function( options ) {

	event.stopPropagation();
	
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";
	var projectId 	= options && options.projectId ? options.projectId : "";

	if(!openAs) {
		projectObj.clearRest();
		projectObj.toggleAccordiance("contractors", "new");
	}
	
	$.ajax({
		method: "POST",
		url: "/projects/contractors/createForm",
		data: {
			openAs 		: openAs,
			popupType 	: popupType,
			projectId 	: projectId
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Add Contractor"}, popupType);
			} else{
				$("#contractor_content").html(response);
			}
			projectObj._projects.setMandatoryFields();
			utilObj.setStatus("status", "statusDb");
			utilObj.getAndSetCountryStatus("create_contractor_form");
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

contractors.prototype.createValidate =  function ( openAs, popupType ) {
	var validator = $( "#create_contractor_form" ).validate();

	if(validator.form()) {
		projectObj._contractors.createSubmit( openAs, popupType );
	}
};

contractors.prototype.createSubmit = function( openAs, popupType ) {
	var idPrefix 				= "#create_contractor_form "
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
	var serviceZip				= $(idPrefix+"#serviceZip").val();

	$(idPrefix+"input[name=prefContact]:checked").each(
		function() {
			prefContact += prefContact != "" ? (","+this.value) : this.value;
		}
	);

	$.ajax({
		method: "POST",
		url: "/projects/contractors/add",
		data: {
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
			serviceZip 				: serviceZip,
			openAs 					: openAs,
			popupType 				: popupType
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._contractors.viewOne(response.insertedId, openAs, popupType);
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

contractors.prototype.viewOne = function( contractorId, openAs, popupType ) {
	this.contractorId 	= contractorId;
	popupType 			= popupType ? popupType : "";
	if(!openAs || openAs != "popup") {
		projectObj.clearRest();
		projectObj.toggleAccordiance("contractors", "viewOne");
	}
	
	$.ajax({
		method: "POST",
		url: "/projects/contractors/viewOne",
		data: {
			contractorId 	: projectObj._contractors.contractorId,
			openAs 			: openAs,
			popupType 		: popupType
		},
		success: function( response ) {
			if( openAs && openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Contractor Details"}, popupType);
				projectObj._projects.updateContractorSelectionList();
				projectObj._projects.setContractorDetails();
			} else {
				$("#contractor_content").html(response);
			}
			projectObj._contractors.setPrefContact();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

contractors.prototype.viewAll = function() {
	projectObj.clearRest();
	projectObj.toggleAccordiance("contractors", "viewAll");

	$.ajax({
		method: "POST",
		url: "/projects/contractors/viewAll",
		data: {},
		success: function( response ) {
			$("#contractor_content").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

contractors.prototype.editForm = function( options ) {
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";

	$.ajax({
		method: "POST",
		url: "/projects/contractors/editForm",
		data: {
			'contractorId' : projectObj._contractors.contractorId,
			'openAs' 		: openAs,
			'popupType' 	: popupType
			
		},
		success: function( response ) {
			$("#popupForAll"+popupType).html(response);
			projectObj._projects.openDialog({"title" : "Edit Contractor"}, popupType);
			projectObj._contractors.setPrefContact();
			utilObj.setStatus("status", "statusDb");
			utilObj.getAndSetCountryStatus("update_contractor_form");

		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

contractors.prototype.updateValidate = function() {
	var validator = $( "#update_contractor_form" ).validate();

	if(validator.form()) {
		projectObj._contractors.updateSubmit();
	}
};

contractors.prototype.updateSubmit = function() {
	var idPrefix 				= "#update_contractor_form ";
	var contractorId			= $(idPrefix+"#contractorId").val();
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
		url: "/projects/contractors/update",
		data: {
			contractorId 			: contractorId,
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
				projectObj._contractors.viewOne(response.updatedId);
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

contractors.prototype.deleteRecord = function() {
	$.ajax({
		method: "POST",
		url: "/projects/contractors/deleteRecord",
		data: {
			contractorId: projectObj._contractors.contractorId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._contractors.viewAll();
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

contractors.prototype.setPrefContact = function() {
	var prefContact	= $("#prefContactDb").val().split(",");

	$("input[name=prefContact]").each(function() {
		if(prefContact.indexOf(this.value) >= 0) {
			$(this).prop("checked", true);
		}
	});
};