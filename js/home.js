function home() {

}

home.prototype.loginValidate = function() {
	var validator = $( "#login_form" ).validate();

	if(validator.form()) {
		submit.loginSubmit();
	}
}

home.prototype.signupValidate = function() {
	$("#signup_user_form").validate({
		rules: {
			password: {
				required: true
			},
			confirmPassword: {
				required: true,
				equalTo: "#password"
			}
		}
	});

	var validator = $( "#signup_user_form" ).validate();

	if(validator.form()) {
		submit.signupSubmit();
	}
}

home.prototype.hideContractorDetails = function() {
	$(".contractorDetails").hide();
	$(".contractorCompany").hide();
	$(".contractorCompanyInfo > span").hide();
}

home.prototype.getContractorDetails = function() {
	$.ajax({
		method: "POST",
		url: "/projects/contractors/getList",
		data: {},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				contractors = response["contractors"];
				for(var i =0 ; i < contractors.length; i++) {
					$('#contractorId').append($('<option>', {
					    value: contractors[i].id,
					    text: contractors[i].name
					}));

					$('.contractorCompanyInfo').append('<span id="contractorCompany_'+contractors[i].id+'">'+contractors[i].company+'</span>');
				}
				homeObj.hideContractorDetails();
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

home.prototype.showContractor = function( belongsTo ) {
	if(belongsTo == "contractor") {
		$(".contractorDetails").show();
	} else {
		homeObj.hideContractorDetails();
	}
}

home.prototype.showContractorCompany = function( contractorId ) {
	if(contractorId == "") {
		$(".contractorCompany").hide();
		$(".contractorCompanyInfo > span").hide();
	} else {
		$(".contractorCompany").show();
		$(".contractorCompanyInfo > span").hide();
		$("#contractorCompany_"+contractorId).show();
	}
}

$().ready(function() {
	homeObj = new home();
	formUtilObj = new formUtils();

	homeObj.getContractorDetails();
	homeObj.hideContractorDetails();

	formUtilObj.getCountryStatus("state", "country");
});
