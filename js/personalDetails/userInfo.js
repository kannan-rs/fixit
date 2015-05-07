function userInfo() {

}

userInfo.prototype.getUserData = function() {
	$.ajax({
		method: "POST",
		url: "/personalDetails/personalDetails/getUserDatas",
		data: {},
		success: function( response ) {
			$("#index_content").html(response);
			personalDetailsObj._userInfo.setPrimaryContact();
			personalDetailsObj._userInfo.setPrefContact();
			personalDetailsObj._userInfo.hideUnsetPrimaryContact();
			personalDetailsObj._userInfo.hideUnsetPrefContact();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

userInfo.prototype.editPage = function() {
	$.ajax({
		method: "POST",
		url: "/personalDetails/personalDetails/editPage",
		data: {},
		success: function( response ) {
			$("#index_content").html(response);
			personalDetailsObj._userInfo.setPrimaryContact();
			personalDetailsObj._userInfo.setPrefContact();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

userInfo.prototype.updateValidate = function() {
	$("#user_details_form").validate();

	var validator = $( "#user_details_form" ).validate();

	if(validator.form()) {
		this.updateSubmit();
	}
};

userInfo.prototype.updateSubmit = function() {
	var sno 				= $("#user_details_sno").val();
	var firstName 			= $("#firstName").val();
	var lastName 			= $("#lastName").val();
	var belongsTo 			= $("#belongsTo").val();
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
		url: "/personalDetails/personalDetails/update",
		data: { 
			sno: 				sno,
			firstName: 			firstName,
			lastName: 			lastName, 
			belongsTo: 			belongsTo,
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
			$("#index_content").html(response);
			personalDetailsObj._userInfo.setPrimaryContact();
			personalDetailsObj._userInfo.setPrefContact();
			personalDetailsObj._userInfo.hideUnsetPrimaryContact();
			personalDetailsObj._userInfo.hideUnsetPrefContact();

			if($("#user_details_form").length) {
				alert("Error while updating, Try after some time");
			} else {
				alert("User Record updated successfully");
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

userInfo.prototype.changePassForm = function() {
		$.ajax({
		method: "POST",
		url: "/personalDetails/personalDetails/changePassForm",
		data: {},
		success: function( response ) {
			$("#index_content").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

userInfo.prototype.updatePasswordValidate = function() {
	$("#update_password_form").validate({
		rules: {
			confirmPassword: {
				required: true,
				equalTo: "#password"
			}
		}
	});

	var validator = $( "#update_password_form" ).validate();

	if(validator.form()) {
		this.updatePasswordSubmit();
	}
}

userInfo.prototype.updatePasswordSubmit = function() {
	var sno 				= $("#user_details_sno").val();
	var password 			= $("#password").val();
	var passwordHint 		= $("#passwordHint").val();
	var email 				= $("#email").val();
	
	$.ajax({
		method: "POST",
		url: "/personalDetails/personalDetails/updatePassword",
		data: { 
			sno: sno,
			password: password, 
			passwordHint: passwordHint,
			email: email
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				$("#password").val("");
				$("#confirmPassword").val("");
				$("#passwordHint").val("");
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

/* Utils Function */
userInfo.prototype.setPrimaryContact = function() {
	var primaryContact 	= $("#dbPrimaryContact").val();

	$("input[name=primaryContact]").each(function() {
		if(this.value == primaryContact) {
			$(this).prop('checked', true);
		}
	});

};

userInfo.prototype.setPrefContact = function() {
	var prefContact	= $("#dbPrefContact").val().split(",");

	$("input[name=prefContact]").each(function() {
		if(prefContact.indexOf(this.value) >= 0) {
			$(this).prop("checked", true);
		}
	});
};

userInfo.prototype.hideUnsetPrimaryContact = function() {
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

userInfo.prototype.hideUnsetPrefContact = function() {
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