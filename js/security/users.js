/**
	Users functions
*/
function securityUsers() {

};

/**
	Create User Validation
*/
securityUsers.prototype.createValidate =  function () {
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

		securityObj._users.createSubmit();
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

securityUsers.prototype.createForm = function() {
	$.ajax({
		method: "POST",
		url: "/security/users/createForm",
		data: {},
		success: function( response ) {
			$("#security_content").html(response);
			securityObj._users.showContractor();
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

securityUsers.prototype.createSubmit = function() {
	var privilege 			= $("#privilege").val();
	var firstName 			= $("#firstName").val();
	var lastName 			= $("#lastName").val();
	var password 			= $("#password").val();
	var passwordHint 		= $("#passwordHint").val();
	var belongsTo 			= $("#belongsTo").val();
	var contractorId 		= "";
	var userStatus 			= $("#userStatus").val();
	var emailId 			= $("#emailId").val();
	var contactPhoneNumber 	= $("#contactPhoneNumber").val();
	var mobileNumber 		= $("#mobileNumber").val();
	var altNumber 			= $("#altNumber").val();
	var primaryContact		= $("input[name=primaryContact]:checked").val();
	var prefContact			= "";
	var addressLine1 		= $("#addressLine1").val();
	var addressLine2 		= $("#addressLine2").val();
	var addressLine3 		= $("#addressLine3").val();
	var addressLine4 		= $("#addressLine4").val();
	var city 				= $("#city").val();
	var state 				= $("#state").val();
	var country 			= $("#country").val();
	var pinCode				= $("#pinCode").val();

	$("input[name=prefContact]:checked").each(
		function() {
			prefContact += prefContact != "" ? (","+this.value) : this.value;
		}
	);

	var ownerSelected = $("input[type='radio'][name='optionSelectedOwner']:checked");
	if (belongsTo == "contractor" && ownerSelected.length > 0) {
	    contractorId = ownerSelected.val();
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
			contractorId 		: contractorId,
			userStatus 			: userStatus,
			emailId 			: emailId,
			contactPhoneNumber 	: contactPhoneNumber,
			mobileNumber 		: mobileNumber,
			altNumber 			: altNumber,
			primaryContact 		: primaryContact,
			prefContact 		: prefContact, 
			addressLine1 		: addressLine1,
			addressLine2 		: addressLine2,
			addressLine3 		: addressLine3,
			addressLine4 		: addressLine4,
			city 				: city,
			state 				: state,
			country 			: country,
			pinCode 			: pinCode
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				securityObj._users.viewOne(response.insertedId);
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
			if(session.page == "personalDetails") {
				$("#index_content").html(response);
			} else {
				$("#security_content").html(response);
			}

			securityObj._users.setPrimaryContact();
			securityObj._users.setPrefContact();
			securityObj._users.setBelongsTo();
			securityObj._users.setPrivilege();
			securityObj._users.setStatus();
			
			securityObj._users.showContractor();
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

securityUsers.prototype.updateSubmit = function(userId) {
	var userId 				= $("#userId").val();
	var privilege 			= $("#privilege").length ? $("#privilege").val() : "";
	var sno 				= $("#user_details_sno").val();
	var firstName 			= $("#firstName").val();
	var lastName 			= $("#lastName").val();
	var belongsTo 			= $("#belongsTo").val();
	var activeStartDate 	= $("#activeStartDate").length ? $("#activeStartDate").val() : "";
	var activeEndDate 		= $("#activeEndDate").length ? $("#activeEndDate").val() : "";
	var contractorId 		= "";
	var userStatus 			= $("#userStatus").val();
	var contactPhoneNumber 	= $("#contactPhoneNumber").val();
	var mobileNumber 		= $("#mobileNumber").val();
	var altNumber 			= $("#altNumber").val();
	var primaryContact		= $("input[name=primaryContact]:checked").val();
	var prefContact			= "";
	var addressLine1 		= $("#addressLine1").val();
	var addressLine2 		= $("#addressLine2").val();
	var addressLine3 		= $("#addressLine3").val();
	var addressLine4 		= $("#addressLine4").val();
	var city 				= $("#city").val();
	var state 				= $("#state").val();
	var country 			= $("#country").val();
	var pinCode				= $("#pinCode").val();

	var ownerSelected = $("input[type='radio'][name='optionSelectedOwner']:checked");
	if (belongsTo == "contractor" && ownerSelected.length > 0) {
	    contractorId = ownerSelected.val();
	}

	$("input[name=prefContact]:checked").each(
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
			contractorId 		: contractorId,
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
			addressLine3		: addressLine3,
			addressLine4		: addressLine4,
			city				: city,
			state 				: state,
			country				: country,
			pinCode				: pinCode
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

securityUsers.prototype.deleteRecord = function(userId) {
	$.ajax({
		method: "POST",
		url: "/security/users/deleteRecord",
		data: {
			userId: userId
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

securityUsers.prototype.viewOne = function(userId) {
	$.ajax({
		method: "POST",
		url: "/security/users/viewOne",
		data: {
			userId 		: userId,
			viewFrom 	: session.page
		},
		success: function( response ) {
			if(session.page == "personalDetails") {
				$("#index_content").html(response);
			} else {
				$("#security_content").html(response);
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

securityUsers.prototype.setPrivilege = function() {
	if($("#privilege_db_val").length) { 
		if($("#privilege_db_val").val() == "admin") {
			$("#privilege").val("1");
		} else if($("#privilege_db_val").val() == "user") {
			$("#privilege").val("2")
		}
	}
}

securityUsers.prototype.setStatus = function() {
	var status = $("#userStatusDb").val();
	if(status != "" && $("#userStatus").length) {
		$("#userStatus").val(status);
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

securityUsers.prototype.showContractor = function( ) {
	var belongsTo = $("#belongsTo").val();
	if( belongsTo == "contractor") {
		$(".contractor-search").show();
		$("#selectedContractorDB").show();

		if($("#contractorList li").length) {
			$("#contractorList").show();
		} else {
			$("#contractorList").hide();
		}
	} else {
		$(".contractor-search").hide();
		$("#contractorList").hide();
		$("#selectedContractorDB").hide();
	}
}

securityUsers.prototype.getContractorListUsingZip = function() {
	$.ajax({
		method: "POST",
		url: "/projects/contractors/getList",
		data: {
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				contractors = response["contractors"];
				for(var i =0 ; i < contractors.length; i++) {
					var li = "<li class=\"ui-state-highlight\" id=\""+contractors[i].id+"\">";
						li += "<div>"+contractors[i].name+"</div>";
						li += "<div class=\"company\">"+contractors[i].company+"</div>";
						li += "<span class=\"search-action\"><input type=\"radio\" name=\"optionSelectedOwner\" value=\""+contractors[i].id+"\" /></span>";
						li += "</li>";
					$('#contractorList').append(li);
				}
				$("#contractorList").show();
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