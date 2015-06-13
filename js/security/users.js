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
	var userType 			= $("#userType").val();
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

	$.ajax({
		method: "POST",
		url: "/security/users/add",
		data: {
			privilege: 			privilege,
			firstName: 			firstName,
			lastName: 			lastName, 
			password: 			password,
			passwordHint: 		passwordHint,
			belongsTo: 			belongsTo,
			userType: 			userType,
			userStatus: 		userStatus,
			emailId: 			emailId,
			contactPhoneNumber: contactPhoneNumber,
			mobileNumber: 		mobileNumber,
			altNumber: 			altNumber,
			primaryContact: 	primaryContact,
			prefContact: 		prefContact, 
			addressLine1:		addressLine1,
			addressLine2:		addressLine2,
			addressLine3:		addressLine3,
			addressLine4:		addressLine4,
			city: 				city,
			state: 				state,
			country: 			country,
			pinCode: 			pinCode
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
			$("#security_content").html(response);
			securityObj._users.setPrimaryContact();
			securityObj._users.setPrefContact();
			if($("#privilege_db_val").val() == "admin") {
				$("#privilege").val("1");
			} else if($("#privilege_db_val").val() == "user") {
				$("#privilege").val("2")
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

securityUsers.prototype.updateSubmit = function(userId) {
	var userId 				= $("#userId").val();
	//password = $("#password").val();
	var privilege 			= $("#privilege").val();

	var sno 				= $("#user_details_sno").val();
	var firstName 			= $("#firstName").val();
	var lastName 			= $("#lastName").val();
	var belongsTo 			= $("#belongsTo").val();
	var activeStartDate 	= $("#activeStartDate").length ? $("#activeStartDate").val() : "";
	var activeEndDate 		= $("#activeEndDate").length ? $("#activeEndDate").val() : "";
	var userType 			= $("#userType").val();
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

	$("input[name=prefContact]:checked").each(
		function() {
			prefContact += prefContact != "" ? (","+this.value) : this.value;
		}
	);

	$.ajax({
		method: "POST",
		url: "/security/users/update",
		data: {
			userId: 			userId,
			//password: password,
			privilege: 			privilege,
			sno: 				sno,
			firstName: 			firstName,
			lastName: 			lastName, 
			belongsTo: 			belongsTo,
			activeStartDate: 	activeStartDate,
			activeEndDate: 		activeEndDate,
			userType: 			userType,
			userStatus: 		userStatus,
			contactPhoneNumber: contactPhoneNumber,
			mobileNumber: 		mobileNumber,
			altNumber: 			altNumber,
			primaryContact: 	primaryContact,
			prefContact: 		prefContact,
			addressLine1: 		addressLine1,
			addressLine2: 		addressLine2,
			addressLine3: 		addressLine3,
			addressLine4: 		addressLine4,
			city: 				city,
			state: 				state,
			country: 			country,
			pinCode: 			pinCode
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

securityUsers.prototype.delete = function(userId) {
	$.ajax({
		method: "POST",
		url: "/security/users/delete",
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
			userId: userId
		},
		success: function( response ) {
			$("#security_content").html(response);
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