/**
	Users functions
*/
function securityUsers() {

};

/**
	Create User Validation
*/
securityUsers.prototype.createValidate =  function ( openAs, popupType, belongsTo ) {
	$("#create_user_form").validate({
		rules: {
			password: {
				required: true
			},
			confirmPassword: {
				required: true,
				equalTo: "#password"
			},
			privilege: {
				required: {
					depends: function(element) {
						if('' == $('#privilege').val()){
	                        $('#privilege').val('');
                    	}
                    	return true;
					}
				}
			}
		},
		messages: {
			privilege: {
				required: "Please select any privilege"
			}
		}
	});

	var validator = $( "#create_user_form" ).validate();


	if(validator.form()) {

		securityObj._users.createSubmit( openAs, popupType, belongsTo );
	}
};

securityUsers.prototype.updateValidate = function() {
	$("#update_user_form").validate({
		rules: {
			activeEndDate: {
				greaterThanOrEqualTo: "#activeStartDate"
			},
			privilege: {
				required: {
					depends: function(element) {
						if('' == $('#privilege').val()){
	                        $('#privilege').val('');
                    	}
                    	return true;
					}
				}
			}
		},
		messages: {
			privilege: {
				required: "Please select an option from the list"
			},
			activeEndDate: {
				greaterThanOrEqualTo: "End Date need to be greater that start date"
			}
		}
	});

	var validator = $( "#update_user_form" ).validate();

	if(validator.form()) {
		securityObj._users.updateSubmit();
	}
};

securityUsers.prototype.viewAll = function() {
	$.ajax({
		method: "POST",
		url: "/security/users/viewAll",
		data: {},
		success: function( response ) {
			$("#security_content").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

securityUsers.prototype.createForm = function( options ) {
	var openAs 			= options && options.openAs ? options.openAs : "";
	var popupType 		= options && options.popupType ? options.popupType : "";
	var belongsTo 		= options && options.belongsTo ? options.belongsTo : "";
	var requestFrom 	= options && options.requestFrom ? options.requestFrom : "";

	$.ajax({
		method: "POST",
		url: "/security/users/createForm",
		data: {
			openAs 		: openAs,
			belongsTo 	: belongsTo,
			popupType 	: popupType,
			requestFrom : requestFrom
		},
		success: function( response ) {
			if(openAs == "") {
				$("#security_content").html(response);
			} else {
				$("#popupForAll"+popupType).html( response );
				if(belongsTo == "adjuster") {
					projectObj._projects.openDialog({"title" : "Add Adjuster"}, popupType);
				} else if(belongsTo == "customer") {
					projectObj._projects.openDialog({"title" : "Add Customer"}, popupType);
				}
			}
			//securityObj._users.setStatus();
			utilObj.setStatus("userStatus", "userStatusDb");
			securityObj._users.showBelongsToOption();
			securityObj._users.showreferredByOption();
			utilObj.getAndSetCountryStatus("create_user_form");
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

securityUsers.prototype.createSubmit = function( openAs, popupType, belongsToForPopup ) {
	var idPrefix 			= "#create_user_form ";

	var privilege 			= $(idPrefix+"#privilege").val();
	var firstName 			= $(idPrefix+"#firstName").val();
	var lastName 			= $(idPrefix+"#lastName").val();
	var password 			= $(idPrefix+"#password").val();
	var passwordHint 		= $(idPrefix+"#passwordHint").val();
	var belongsTo 			= $(idPrefix+"#belongsTo").val();
	var referredBy 			= $(idPrefix+"#referredBy").val();
	var belongsToId 		= "";
	var referredById 		= "";
	var userStatus 			= $(idPrefix+"#userStatus").val();
	var emailId 			= $(idPrefix+"#emailId").val();
	var contactPhoneNumber 	= $(idPrefix+"#contactPhoneNumber").val();
	var mobileNumber 		= $(idPrefix+"#mobileNumber").val();
	var altNumber 			= $(idPrefix+"#altNumber").val();
	var primaryContact		= $(idPrefix+"input[name=primaryContact]:checked").val();
	var prefContact			= "";
	var addressLine1 		= $(idPrefix+"#addressLine1").val();
	var addressLine2 		= $(idPrefix+"#addressLine2").val();
	var city 				= $(idPrefix+"#city").val();
	var state 				= $(idPrefix+"#state").val();
	var country 			= $(idPrefix+"#country").val();
	var zipCode				= $(idPrefix+"#zipCode").val();

	$(idPrefix+"input[name=prefContact]:checked").each(
		function() {
			prefContact += prefContact != "" ? (","+this.value) : this.value;
		}
	);

	/*
		Belongs to options
	*/
	var ownerSelected = $(idPrefix+"input[type='radio'][name='optionSelectedOwner']:checked");
	var adjusterSelected = $(idPrefix+"input[type='radio'][name='optionSelectedAdjuster']:checked");
	
	if (belongsTo == "contractor" && ownerSelected.length > 0) {
	    belongsToId 	= ownerSelected.val();
	} else if(belongsTo == "adjuster" && adjusterSelected.length > 0) {
		belongsToId 	= adjusterSelected.val();
	}

	/*
		Referred to options
	*/
	var referredByownerSelected = $(idPrefix+"input[type='radio'][name='referredByoptionSelectedOwner']:checked");
	var referredByadjusterSelected = $(idPrefix+"input[type='radio'][name='referredByoptionSelectedAdjuster']:checked");
	
	if (referredBy == "contractor" && referredByownerSelected.length > 0) {
	    referredById 	= referredByownerSelected.val();
	} else if(referredBy == "adjuster" && referredByadjusterSelected.length > 0) {
		referredById 	= referredByadjusterSelected.val();
	}


	$.ajax({
		method: "POST",
		url: "/security/users/add",
		data: {
			privilege 			: privilege,
			firstName 			: firstName,
			lastName 			: lastName, 
			password 			: password,
			passwordHint 		: passwordHint,
			belongsTo 			: belongsTo,
			referredBy 			: referredBy,
			belongsToId 		: belongsToId,
			referredById 		: referredById,
			userStatus 			: userStatus,
			emailId 			: emailId,
			contactPhoneNumber 	: contactPhoneNumber,
			mobileNumber 		: mobileNumber,
			altNumber 			: altNumber,
			primaryContact 		: primaryContact,
			prefContact 		: prefContact, 
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
				if(session.is_logged_in && session.page != "signup") {
					securityObj._users.viewOne(response.insertedId, openAs, popupType, belongsToForPopup);
				} else if (session.page == "signup") {
					$(".content").html( response["createConfirmPage"] );
				}
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

securityUsers.prototype.editUser = function(userId) {
	$.ajax({
		method: "POST",
		url: "/security/users/editForm",
		data: {'userId' : userId},
		success: function( response ) {
			if(session.page == "home") {
				$("#index_content").html(response);
			} else {
				$("#security_content").html(response);
			}

			securityObj._users.setPrimaryContact();
			securityObj._users.setPrefContact();
			securityObj._users.setBelongsTo();
			securityObj._users.setPrivilege();
			//securityObj._users.setStatus();
			utilObj.setStatus("userStatus", "userStatusDb");
			securityObj._users.setreferredBy();
			
			securityObj._users.showBelongsToOption();
			securityObj._users.showreferredByOption();
			utilObj.getAndSetCountryStatus("update_user_form");

			dateOptions = {
				fromDateField 	: "activeStartDate",
				toDateField		: "activeEndDate"
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

securityUsers.prototype.updateSubmit = function(userId) {
	
	var idPrefix 			= "#update_user_form ";
	var userId 				= $(idPrefix+"#userId").val();
	var privilege 			= $(idPrefix+"#privilege").length ? $("#privilege").val() : "";
	var sno 				= $(idPrefix+"#user_details_sno").val();
	var firstName 			= $(idPrefix+"#firstName").val();
	var lastName 			= $(idPrefix+"#lastName").val();
	var belongsTo 			= $(idPrefix+"#belongsTo").val();
	var activeStartDate 	= $(idPrefix+"#activeStartDate").length ? utilObj.toMySqlDateFormat($("#activeStartDate").val()) : "";
	var activeEndDate 		= $(idPrefix+"#activeEndDate").length ? utilObj.toMySqlDateFormat($("#activeEndDate").val()) : "";
	var belongsToId 		= "";
	var userStatus 			= $(idPrefix+"#userStatus").val();
	var contactPhoneNumber 	= $(idPrefix+"#contactPhoneNumber").val();
	var mobileNumber 		= $(idPrefix+"#mobileNumber").val();
	var altNumber 			= $(idPrefix+"#altNumber").val();
	var primaryContact		= $(idPrefix+"input[name=primaryContact]:checked").val();
	var prefContact			= "";
	var addressLine1 		= $(idPrefix+"#addressLine1").val();
	var addressLine2 		= $(idPrefix+"#addressLine2").val();
	var city 				= $(idPrefix+"#city").val();
	var state 				= $(idPrefix+"#state").val();
	var country 			= $(idPrefix+"#country").val();
	var zipCode				= $(idPrefix+"#zipCode").val();

	var referredBy 			= $(idPrefix+"#referredBy").val();
	var referredById 		= "";

	var ownerSelected = $(idPrefix+"input[type='radio'][name='optionSelectedOwner']:checked");
	var adjusterSelected = $(idPrefix+"input[type='radio'][name='optionSelectedAdjuster']:checked");
	
	if (belongsTo == "contractor" && ownerSelected.length > 0) {
	    belongsToId = ownerSelected.val();
	} else if(belongsTo == "adjuster" && adjusterSelected.length > 0) {
		belongsToId 	= adjusterSelected.val();
	}

	if(belongsTo == "contractor" || belongsTo == "adjuster") {
		belongsToId = belongsToId != "" ? belongsToId : $("#belongsToIdDb").val();
	}

	var referredByOwnerSelected = $(idPrefix+"input[type='radio'][name='referredByoptionSelectedOwner']:checked");
	var referredByAdjusterSelected = $(idPrefix+"input[type='radio'][name='referredByoptionSelectedAdjuster']:checked");

	if (referredBy == "contractor" && referredByOwnerSelected.length > 0) {
	    referredById 	= referredByOwnerSelected.val();
	} else if(referredBy == "adjuster" && referredByAdjusterSelected.length > 0) {
		referredById 	= referredByAdjusterSelected.val();
	}

	if(referredBy == "contractor" || referredBy == "adjuster") {
		referredById = referredById != "" ? referredById : $("#belongsToIdDb").val();
	}


	$(idPrefix+"input[name=prefContact]:checked").each(
		function() {
			prefContact += prefContact != "" ? (","+this.value) : this.value;
		}
	);

	$.ajax({
		method: "POST",
		url: "/security/users/update",
		data: {
			userId 				: userId,
			privilege 			: privilege,
			sno 				: sno,
			firstName 			: firstName,
			lastName			: lastName, 
			belongsTo			: belongsTo,
			belongsToId 		: belongsToId,
			activeStartDate		: activeStartDate,
			activeEndDate		: activeEndDate,
			userStatus			: userStatus,
			contactPhoneNumber	: contactPhoneNumber,
			mobileNumber		: mobileNumber,
			altNumber			: altNumber,
			primaryContact 		: primaryContact,
			prefContact 		: prefContact,
			addressLine1 		: addressLine1,
			addressLine2 		: addressLine2,
			city				: city,
			state 				: state,
			country				: country,
			zipCode				: zipCode,
			referredBy 			: referredBy,
			referredById 		: referredById
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response.status.toLowerCase() == "success") {
				securityObj._users.viewOne(userId);
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

securityUsers.prototype.deleteRecord = function(userId, emailId) {
	$.ajax({
		method: "POST",
		url: "/security/users/deleteRecord",
		data: {
			userId: userId,
			emailId : emailId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response["status"] == "success") {
				securityObj._users.viewAll();
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

securityUsers.prototype.viewOne = function(userId, openAs, popupType, belongsTo ) {
	$.ajax({
		method: "POST",
		url: "/security/users/viewOne",
		data: {
			userId 		: userId,
			viewFrom 	: session.page
		},
		success: function( response ) {
			if( openAs && openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				var title = "";
				title = belongsTo == "adjuster" ? "Adjuster" : title;
				title = belongsTo == "customer" ? "Customer" : title;

				projectObj._projects.openDialog({"title" : title+" Details"}, popupType);
			} else {
				if(session.page == "home") {
					$("#index_content").html(response);
				} else {
					$("#security_content").html(response);
				}
			}
			
			securityObj._users.setPrimaryContact();
			securityObj._users.setPrefContact();
			securityObj._users.hideUnsetPrimaryContact();
			securityObj._users.hideUnsetPrefContact();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

/* Utils Function */
securityUsers.prototype.setPrimaryContact = function() {
	var primaryContact 	= $("#dbPrimaryContact").val();

	$("input[name=primaryContact]").each(function() {
		if(this.value == primaryContact) {
			$(this).prop('checked', true);
		}
	});

};

securityUsers.prototype.setPrefContact = function() {
	var prefContact	= $("#dbPrefContact").val().split(",");

	$("input[name=prefContact]").each(function() {
		if(prefContact.indexOf(this.value) >= 0) {
			$(this).prop("checked", true);
		}
	});
};

securityUsers.prototype.setBelongsTo = function() {
	var belongsToDb = $("#belongsToDb").val();
	if(belongsToDb != "" && $("#belongsTo").length) {
		$("#belongsTo").val(belongsToDb);
	}
}

securityUsers.prototype.setreferredBy = function() {
	var referredByDb = $("#referredByDb").val();
	if(referredByDb != "" && $("#referredByDb").length) {
		$("#referredBy").val(referredByDb);
	}
}

securityUsers.prototype.setPrivilege = function() {
	if($("#privilege_db_val").length) { 
		if($("#privilege_db_val").val() == "admin") {
			$("#privilege").val("1");
		} else if($("#privilege_db_val").val() == "user") {
			$("#privilege").val("2")
		}
	}
}

securityUsers.prototype.hideUnsetPrimaryContact = function() {
	if($("#user_details_form").length) {
		var primaryContact = $("#dbPrimaryContact").val();

		$("input[name=primaryContact]").each(function() {
			if(!$(this).prop('checked')) {
				var value = this.value;
				var type = $(this).prop("type");
				$("#"+value+type).hide();
			}
		});
	}
};

securityUsers.prototype.hideUnsetPrefContact = function() {
	if($("#user_details_form").length) {
		var prefContact	= $("#dbPrefContact").val().split(",");

		$("input[name=prefContact]").each(function() {
			if(!$(this).prop('checked')) {
				var value = this.value;
				var type = $(this).prop("type");
				$("#"+value+type).hide();
				$("#"+value+type+"label").hide();
			}
		});
	}
};

securityUsers.prototype.showBelongsToOption = function( ) {
	var belongsTo = $("#belongsTo").val();
	
	// Hide all search container by default
	$(".contractor-search").hide();
	$("#contractorList").hide();
	$("#selectedContractorDB").hide();

	$(".adjuster-search").hide();
	$("#adjusterList").hide();
	$("#selectedAdjusterDB").hide();

	if( belongsTo == "contractor") {
		$(".contractor-search").show();
		$("#selectedContractorDB").show();

		if($("#contractorList li").length) {
			$("#contractorList").show();
		} else {
			$("#contractorList").hide();
		}
	} else if(belongsTo == "adjuster") {
		$(".adjuster-search").show();
		$("#selectedAdjusterDB").show();

		if($("#adjusterList li").length) {
			$("#adjusterList").show();
		} else {
			$("#adjusterList").hide();
		}
	}
}

securityUsers.prototype.showreferredByOption = function( ) {
	var referredBy = $("#referredBy").val();
	
	// Hide all search container by default
	$(".referredBycontractor-search").hide();
	$("#referredBycontractorList").hide();
	$("#referredByselectedContractorDB").hide();

	$(".referredByadjuster-search").hide();
	$("#referredByadjusterList").hide();
	$("#referredByselectedAdjusterDB").hide();

	if( referredBy == "contractor") {
		$(".referredBycontractor-search").show();
		$("#referredByselectedContractorDB").show();

		if($("#referredBycontractorList li").length) {
			$("#referredBycontractorList").show();
		} else {
			$("#referredBycontractorList").hide();
		}
	} else if(referredBy == "adjuster") {
		$(".referredByadjuster-search").show();
		$("#referredByselectedAdjusterDB").show();

		if($("#referredByadjusterList li").length) {
			$("#referredByadjusterList").show();
		} else {
			$("#referredByadjusterList").hide();
		}
	}
}


securityUsers.prototype.getContractorListUsingZip = function( prefix ) {
	$('#'+prefix+'contractorList').children().remove();
	if($("#"+prefix+"contractorZipCode").val().trim() == "") {
		return;
	}
	$.ajax({
		method: "POST",
		url: "/projects/contractors/getList",
		data: {
			zip 	: $("#"+prefix+"contractorZipCode").val()
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				contractors = response["contractors"];
				for(var i =0 ; i < contractors.length; i++) {
					var li = "<li class=\"ui-state-highlight\" id=\""+contractors[i].id+"\">";
						li += "<div>"+contractors[i].name+"</div>";
						li += "<div class=\"company\">"+contractors[i].company+"</div>";
						li += "<span class=\"search-action\"><input type=\"radio\" name=\""+prefix+"optionSelectedOwner\" value=\""+contractors[i].id+"\" /></span>";
						li += "</li>";
					$('#'+prefix+'contractorList').append(li);
				}
				$("#"+prefix+"contractorList").show();
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

securityUsers.prototype.getAdjusterByCompanyName = function( prefix ) {
	$('#'+prefix+'adjusterList').children().remove();
	if($("#"+prefix+"partnerCompanyName").val().trim() == "") return;
	$.ajax({
		method: "POST",
		url: "/projects/partners/getPartnerByCompanyName",
		data: {
			companyName : $("#"+prefix+"partnerCompanyName").val().trim()
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				partners = response["partners"];
				for(var i =0 ; i < partners.length; i++) {
					var li = "<li class=\"ui-state-highlight\" id=\""+partners[i].id+"\">";
						li += "<div>"+partners[i].company_name+"</div>";
						li += "<div class=\"company\">"+partners[i].name+"</div>";
						li += "<span class=\"search-action\"><input type=\"radio\" name=\""+prefix+"optionSelectedAdjuster\" value=\""+partners[i].id+"\" /></span>";
						li += "</li>";
					$('#'+prefix+'adjusterList').append(li);
				}
				$("#"+prefix+"adjusterList").show();
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
