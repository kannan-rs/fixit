function contractors() {
	
}

contractors.prototype.createForm = function( openAs, popupType ) {
	popupType = popupType ? popupType : "";
	if(!openAs) {
		projectObj.clearRest();
		projectObj.toggleAccordiance("contractors", "new");
	}
	
	$.ajax({
		method: "POST",
		url: "/projects/contractors/createForm",
		data: {
			openAs 		: openAs,
			popupType 	: popupType
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Add Contractor"}, popupType);
			} else{
				$("#contractor_content").html(response);
			}
			projectObj._projects.setMandatoryFields();
			formUtilObj.getAndSetCountryStatus("state", "country");
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
	var name 					= $("#name").val();
	var company 				= $("#company").val();
	var type 					= $("#type").val();
	var license 				= $("#license").val();
	var bbb 					= $("#bbb").val();
	var status 					= $("#status").val();
	var addressLine1 			= $("#addressLine1").val();
	var addressLine2 			= $("#addressLine2").val();
	var addressLine3 			= $("#addressLine3").val();
	var addressLine4 			= $("#addressLine4").val();
	var city 					= $("#city").val();
	var state 					= $("#state").val();
	var country 				= $("#country").val();
	var pinCode 				= $("#pinCode").val();
	var emailId 				= $("#emailId").val();
	var contactPhoneNumber 		= $("#contactPhoneNumber").val();
	var mobileNumber 			= $("#mobileNumber").val();
	var prefContact 			= "";
	var websiteURL 				= $("#websiteURL").val();
	var serviceZip				= $("#serviceZip").val();

	$("input[name=prefContact]:checked").each(
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
			addressLine3 			: addressLine3,
			addressLine4 			: addressLine4,
			city 					: city,
			state 					: state,
			country 				: country,
			pinCode 				: pinCode,
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

contractors.prototype.edit = function() {
	$.ajax({
		method: "POST",
		url: "/projects/contractors/editForm",
		data: {
			'contractorId' : projectObj._contractors.contractorId
			
		},
		success: function( response ) {
			$("#popupForAll").html(response);
			projectObj._projects.openDialog({"title" : "Edit Contractor"});
			projectObj._contractors.setPrefContact();
			formUtilObj.getAndSetCountryStatus("state", "country");
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
	var contractorId			= $("#contractorId").val();
	var name 					= $("#name").val();
	var company 				= $("#company").val();
	var type 					= $("#type").val();
	var license 				= $("#license").val();
	var bbb 					= $("#bbb").val();
	var status 					= $("#status").val();
	var addressLine1 			= $("#addressLine1").val();
	var addressLine2 			= $("#addressLine2").val();
	var addressLine3 			= $("#addressLine3").val();
	var addressLine4 			= $("#addressLine4").val();
	var city 					= $("#city").val();
	var state 					= $("#state").val();
	var country 				= $("#country").val();
	var pinCode 				= $("#pinCode").val();
	var emailId 				= $("#emailId").val();
	var contactPhoneNumber 		= $("#contactPhoneNumber").val();
	var mobileNumber 			= $("#mobileNumber").val();
	var prefContact 			= "";
	var websiteURL 				= $("#websiteURL").val();
	var serviceZip 				= $("#serviceZip").val();

	$("input[name=prefContact]:checked").each(
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
			addressLine3 			: addressLine3,
			addressLine4 			: addressLine4,
			city 					: city,
			state 					: state,
			country 				: country,
			pinCode 				: pinCode,
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
