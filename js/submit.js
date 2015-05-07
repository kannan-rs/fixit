/* Form AJAX submit */
function form_submit()
{

}
var error = null;
var fail_error = null;

form_submit.prototype.login_submit = function() {
	
	var email = $("#login_email").val();
	var password = $("#login_password").val();

	$.ajax({
		method: "POST",
		url: "/validation/login",
		data: { login_email: email, login_password: password },
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				$(location).attr('href',response.redirect);
			} else if(response.status.toLowerCase() == "error") {
				$("#login_error").text(response.message).show();
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

form_submit.prototype.signupSubmit = function() {
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
		url: "/validation/signup",
		data: { 
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
				$(location).attr('href',response.redirect);
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

form_submit.prototype.updateUserDetailsSubmit = function() {
	var sno 				= $("#user_details_sno").val();
	var lastName 			= $("#lastName").val();
	var firstName 			= $("#firstName").val();
	var belongsTo 			= $("#belongsTo").val();
	var userType 			= $("#userType").val();
	var userStatus 			= $("#userStatus").val();
	var activeStartDate 	= $("#activeStartDate").val();
	var activeEndDate 		= $("#activeEndDate").val();
	var contactPhoneNumber 	= $("#contactPhoneNumber").val();
	var mobileNumber 		= $("#mobileNumber").val();
	var pagerNumber 		= $("#pagerNumber").val();
	var addressLine1 		= $("#addressLine1").val();
	var addressLine2 		= $("#addressLine2").val();
	var addressLine3 		= $("#addressLine3").val();
	var addressLine4 		= $("#addressLine4").val();
	var city 				= $("#city").val();
	var state 				= $("#state").val();
	var country 			= $("#country").val();
	var pref 				= $("#pref").val();

	console.log("submit");
	$.ajax({
		method: "POST",
		url: "/validation/updateUserDetails",
		data: { 
			sno: sno,
			lastName: lastName, 
			firstName: firstName,
			belongsTo: belongsTo,
			userType: userType,
			userStatus: userStatus,
			activeStartDate: activeStartDate,
			activeEndDate: activeEndDate,
			contactPhoneNumber: contactPhoneNumber,
			mobileNumber: mobileNumber,
			pagerNumber: pagerNumber,
			addressLine1: addressLine1,
			addressLine2: addressLine2,
			addressLine3: addressLine3,
			addressLine4: addressLine4,
			city: city,
			state: state,
			country: country,
			pref: pref
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				//$(location).attr('href',response.redirect);
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


var submit = new form_submit();