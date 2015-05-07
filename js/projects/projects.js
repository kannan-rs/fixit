/**
	Projects functions
*/
function project() {

};

/**
	Create Project Validation
*/
project.prototype.createValidate =  function () {
	/*
	$("#create_project_form").validate({
		rules: {
			assign_user: {
				required: {
					depends: function(element) {
						if('' == $('#assign_user').val()){
	                        $('#assign_user').val('');
                    	}
                    	return true;
					}
				}
			}
		},
		messages: {
			assign_user: {
				required: "Please select an option from the list"
			}
		}
	});
	*/
	var validator = $( "#create_project_form" ).validate();

	if(validator.form()) {
		projectObj._projects.createSubmit();
	}
};

project.prototype.viewAll = function() {
	projectObj.clearRest();
	$.ajax({
		method: "POST",
		url: "/projects/projects/viewAll",
		data: {},
		success: function( response ) {
			$("#project_content").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

project.prototype.createForm = function() {
	projectObj.clearRest();
	$.ajax({
		method: "POST",
		url: "/projects/projects/createForm",
		data: {},
		success: function( response ) {
			$("#project_content").html(response);
			projectObj._projects.setMandatoryFields();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

project.prototype.createSubmit = function() {
	var projectTitle 			= $("#projectTitle").val();
	var description 			= $("#description").val();
	//var assign_user 			= $("#assign_user").val();
	var associated_claim_num 	= $("#associated_claim_num").val();
	var project_type			= $("#project_type").val();
	var project_status			= $("#project_status").val();
	var project_budget			= $("#project_budget").val();
	var property_owner_id		= $("#property_owner_id").val();
	var contractor_id			= $("#contractor_id").val();
	var adjuster_id				= $("#adjuster_id").val();
	var customer_id				= $("#customer_id").val();
	var paid_from_budget		= $("#paid_from_budget").val();
	var remaining_budget		= $("#remaining_budget").val();
	var referral_fee			= $("#referral_fee").val();
	var project_lender			= $("#project_lender").val();
	var lend_amount				= $("#lend_amount").val();

	$.ajax({
		method: "POST",
		url: "/projects/projects/add",
		data: {
			projectTitle: 			projectTitle,
			description: 			description,
			//assign_user: 			assign_user,
			associated_claim_num: 	associated_claim_num,
			project_type: 			project_type,
			project_status: 		project_status,
			project_budget: 		project_budget,
			property_owner_id: 		property_owner_id,
			contractor_id: 			contractor_id,
			adjuster_id: 			adjuster_id,
			customer_id: 			customer_id,
			paid_from_budget: 		paid_from_budget,
			remaining_budget: 		remaining_budget,
			referral_fee: 			referral_fee,
			project_lender: 		project_lender,
			lend_amount: 			lend_amount
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._projects.viewOne(response.insertedId);
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

project.prototype.editProject = function(projectId) {
	projectObj.clearRest();
	$.ajax({
		method: "POST",
		url: "/projects/projects/editForm",
		data: {
			'projectId' : projectId
		},
		success: function( response ) {
			$("#project_content").html(response);
			projectObj._projects.setDropdownValue();
			projectObj._projects.setMandatoryFields();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

project.prototype.updateValidate = function() {
	/*
	$("#update_project_form").validate({
		rules: {
			assign_user: {
				required: {
					depends: function(element) {
						if('' == $('#assign_user').val()){
	                        $('#assign_user').val('');
                    	}
                    	return true;
					}
				}
			}
		},
		messages: {
			assign_user: {
				required: "Please select an option from the list"
			}
		}
	});
	*/
	var validator = $( "#update_project_form" ).validate();

	if(validator.form()) {
		projectObj._projects.updateSubmit();
	}
};

project.prototype.updateSubmit = function() {
	var project_sno 			= $("#project_sno").val();
	var projectTitle 			= $("#projectTitle").val();
	var description 			= $("#description").val();
	//var assign_user 			= $("#assign_user").val();
	var associated_claim_num 	= $("#associated_claim_num").val();
	var project_type			= $("#project_type").val();
	var project_status			= $("#project_status").val();
	var project_budget			= $("#project_budget").val();
	var property_owner_id		= $("#property_owner_id").val();
	var contractor_id			= $("#contractor_id").val();
	var adjuster_id				= $("#adjuster_id").val();
	var customer_id				= $("#customer_id").val();
	var paid_from_budget		= $("#paid_from_budget").val();
	var remaining_budget		= $("#remaining_budget").val();
	var referral_fee			= $("#referral_fee").val();
	var project_lender			= $("#project_lender").val();
	var lend_amount				= $("#lend_amount").val();

	$.ajax({
		method: "POST",
		url: "/projects/projects/update",
		data: {
			project_sno: 			project_sno,
			projectTitle: 			projectTitle,
			description: 			description,
			associated_claim_num: 	associated_claim_num,
			project_type: 			project_type,
			project_status: 		project_status,
			project_budget: 		project_budget,
			property_owner_id: 		property_owner_id,
			contractor_id: 			contractor_id,
			adjuster_id: 			adjuster_id,
			customer_id: 			customer_id,
			paid_from_budget: 		paid_from_budget,
			remaining_budget: 		remaining_budget,
			referral_fee: 			referral_fee,
			project_lender: 		project_lender,
			lend_amount: 			lend_amount

		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._projects.viewOne(response.updatedId);
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

project.prototype.delete = function(projectId) {
	$.ajax({
		method: "POST",
		url: "/projects/projects/delete",
		data: {
			projectId: projectId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._projects.viewAll();
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

project.prototype.viewOne = function(projectId) {
	projectObj.clearRest();
	$.ajax({
		method: "POST",
		url: "/projects/projects/viewOne",
		data: {
			projectId: projectId
		},
		success: function( response ) {
			$("#project_content").html(response);
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

project.prototype.setDropdownValue = function() {
	var db_project_type = $("#db_project_type").val();
	var db_project_status = $("#db_project_status").val();

	$("#project_type").val(db_project_type);
	$("#project_status").val(db_project_status);

}

project.prototype.setMandatoryFields = function(){
	
}
