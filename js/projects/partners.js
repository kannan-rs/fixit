function partners() {
	
}

partners.prototype.createForm = function( options ) {

	event.stopPropagation();
	
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";
	var projectId 	= options && options.projectId ? options.projectId : "";

	if(!openAs) {
		projectObj.clearRest();
		projectObj.toggleAccordiance("partners", "new");
	}
	
	$.ajax({
		method: "POST",
		url: "/projects/partners/createForm",
		data: {
			openAs 		: openAs,
			popupType 	: popupType,
			projectId 	: projectId
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Add Partner"}, popupType);
			} else{
				$("#partner_content").html(response);
			}
			projectObj._projects.setMandatoryFields();
			utilObj.setStatus("status", "statusDb");
			utilObj.getAndSetCountryStatus("create_partner_form");
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

partners.prototype.createValidate =  function ( openAs, popupType ) {
	var validator = $( "#create_partner_form" ).validate({
		rules: {
			zipCode : {
				required: true,
				minlength: 5,
				maxlength: 5,
				digits : true
			},
			wNumber : {
				digits : true	
			},
			pNumber : {
				digits : true	
			}
		}
	});

	if(validator.form()) {
		projectObj._partners.createSubmit( openAs, popupType );
	}
};

partners.prototype.createSubmit = function( openAs, popupType ) {
	var idPrefix 				= "#create_partner_form "
	var name 					= $(idPrefix+"#name").val();
	var company 				= $(idPrefix+"#company").val();
	var type 					= $(idPrefix+"#type").val();
	var license 				= $(idPrefix+"#license").val();
	var status 					= $(idPrefix+"#status").val();
	var addressLine1 			= $(idPrefix+"#addressLine1").val();
	var addressLine2 			= $(idPrefix+"#addressLine2").val();
	var city 					= $(idPrefix+"#city").val();
	var state 					= $(idPrefix+"#state").val();
	var country 				= $(idPrefix+"#country").val();
	var zipCode 				= $(idPrefix+"#zipCode").val();
	var wNumber 				= $(idPrefix+"#wNumber").val();
	var wEmailId 				= $(idPrefix+"#wEmailId").val();
	var pNumber 				= $(idPrefix+"#pNumber").val();
	var pEmailId 				= $(idPrefix+"#pEmailId").val();
	var prefContact 			= "";
	var websiteURL 				= $(idPrefix+"#websiteURL").val();

	$(idPrefix+"input[name=prefContact]:checked").each(
		function() {
			prefContact += prefContact != "" ? (","+this.value) : this.value;
		}
	);

	$.ajax({
		method: "POST",
		url: "/projects/partners/add",
		data: {
			name 					: name,
			company 				: company,
			type 					: type,
			license 				: license,
			status 					: status,
			addressLine1 			: addressLine1,
			addressLine2 			: addressLine2,
			city 					: city,
			state 					: state,
			country 				: country,
			zipCode 				: zipCode,
			wNumber 				: wNumber,
			wEmailId 				: wEmailId,
			pNumber 				: pNumber,
			pEmailId 				: pEmailId,
			prefContact 			: prefContact,
			websiteURL 				: websiteURL,
			openAs 					: openAs,
			popupType 				: popupType
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._partners.viewOne(response.insertedId, openAs, popupType);
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

partners.prototype.viewOne = function( partnerId, openAs, popupType ) {
	this.partnerId 	= partnerId;
	popupType 			= popupType ? popupType : "";
	if(!openAs || openAs != "popup") {
		projectObj.clearRest();
		projectObj.toggleAccordiance("partners", "viewOne");
	}
	
	$.ajax({
		method: "POST",
		url: "/projects/partners/viewOne",
		data: {
			partnerId 	: projectObj._partners.partnerId,
			openAs 			: openAs,
			popupType 		: popupType
		},
		success: function( response ) {
			if( openAs && openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Partner Details"}, popupType);
				projectObj._projects.updatePartnerSelectionList();
				projectObj._projects.setPartnerDetails();
			} else {
				$("#partner_content").html(response);
			}
			projectObj._partners.setPrefContact();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

partners.prototype.viewAll = function() {
	projectObj.clearRest();
	projectObj.toggleAccordiance("partners", "viewAll");

	$.ajax({
		method: "POST",
		url: "/projects/partners/viewAll",
		data: {},
		success: function( response ) {
			$("#partner_content").html(response);
			projectObj._partners.showPartnersList();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

partners.prototype.editForm = function( options ) {
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";

	$.ajax({
		method: "POST",
		url: "/projects/partners/editForm",
		data: {
			'partnerId' : projectObj._partners.partnerId,
			'openAs' 		: openAs,
			'popupType' 	: popupType
			
		},
		success: function( response ) {
			$("#popupForAll"+popupType).html(response);
			projectObj._projects.openDialog({"title" : "Edit Partner"}, popupType);
			projectObj._partners.setPrefContact();
			utilObj.setStatus("status", "statusDb");
			utilObj.getAndSetCountryStatus("update_partner_form");

		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

partners.prototype.updateValidate = function() {
	var validator = $( "#update_partner_form" ).validate({
		rules: {
			zipCode : {
				required: true,
				minlength: 5,
				maxlength: 5,
				digits : true
			},
			wNumber : {
				digits : true	
			},
			pNumber : {
				digits : true	
			}
		}
	});

	if(validator.form()) {
		projectObj._partners.updateSubmit();
	}
};

partners.prototype.updateSubmit = function() {
	var idPrefix 				= "#update_partner_form ";
	var partnerId				= $(idPrefix+"#partnerId").val();
	var name 					= $(idPrefix+"#name").val();
	var company 				= $(idPrefix+"#company").val();
	var type 					= $(idPrefix+"#type").val();
	var license 				= $(idPrefix+"#license").val();
	var status 					= $(idPrefix+"#status").val();
	var addressLine1 			= $(idPrefix+"#addressLine1").val();
	var addressLine2 			= $(idPrefix+"#addressLine2").val();
	var city 					= $(idPrefix+"#city").val();
	var state 					= $(idPrefix+"#state").val();
	var country 				= $(idPrefix+"#country").val();
	var zipCode 				= $(idPrefix+"#zipCode").val();
	var wNumber 				= $(idPrefix+"#wNumber").val();
	var wEmailId 				= $(idPrefix+"#wEmailId").val();
	var pNumber 				= $(idPrefix+"#pNumber").val();
	var pEmailId 				= $(idPrefix+"#pEmailId").val();
	var prefContact 			= "";
	var websiteURL 				= $(idPrefix+"#websiteURL").val();

	$(idPrefix+"input[name=prefContact]:checked").each(
		function() {
			prefContact += prefContact != "" ? (","+this.value) : this.value;
		}
	);

	$.ajax({
		method: "POST",
		url: "/projects/partners/update",
		data: {
			partnerId 			: partnerId,
			name 					: name,
			company 				: company,
			type 					: type,
			license 				: license,
			status 					: status,
			addressLine1 			: addressLine1,
			addressLine2 			: addressLine2,
			city 					: city,
			state 					: state,
			country 				: country,
			zipCode 				: zipCode,
			wNumber 				: wNumber,
			wEmailId 				: wEmailId,
			pNumber 				: pNumber,
			pEmailId 				: pEmailId,
			prefContact 			: prefContact,
			websiteURL 				: websiteURL
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				$(".ui-button").trigger("click");
				alert(response.message);
				projectObj._partners.viewOne(response.updatedId);
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

partners.prototype.deleteRecord = function() {
	$.ajax({
		method: "POST",
		url: "/projects/partners/deleteRecord",
		data: {
			partnerId: projectObj._partners.partnerId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._partners.viewAll();
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

partners.prototype.setPrefContact = function() {
	var prefContact	= $("#prefContactDb").val().split(",");

	$("input[name=prefContact]").each(function() {
		if(prefContact.indexOf(this.value) >= 0) {
			$(this).prop("checked", true);
		}
	});
};

partners.prototype.showPartnersList = function ( event ) {
	var options = "active";

	if( event ) {
		options = event.target.getAttribute("data-option");
		if(options) {
			$($(".partners.internal-tab-as-links").children()).removeClass("active");
			$(".partners-table-list .row").hide();
			$(event.target).addClass("active");
		} 
	} else {
		$($(".partners.internal-tab-as-links").children()).removeClass("active");
		$(".partners-table-list .row").hide();
		$($(".partners.internal-tab-as-links").children()[0]).addClass("active");
	}

	if(options == "all") {
		$(".partners-table-list .row").show();
	} else if (options != "") {
		$(".partners-table-list .row."+options).show();
	}
}