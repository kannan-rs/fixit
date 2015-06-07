function login_validate() {
	var validator = $( "#login_form" ).validate();

	if(validator.form()) {
		submit.login_submit();
	}
}

function signupValidate() {
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

function hideContractorDetails() {
	$(".contractorDetails").hide();
	$(".contractorCompany").hide();
	$(".contractorCompanyInfo > span").hide();
}

function getContractorDetails() {
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
				hideContractorDetails();
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

function showContractor(belongsTo) {
	if(belongsTo == "contractor") {
		$(".contractorDetails").show();
	} else {
		hideContractorDetails();
	}
}

function showContractorCompany( contractorId) {
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
	getContractorDetails();
	hideContractorDetails();
});
