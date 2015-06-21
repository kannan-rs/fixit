function securityRoles() {
	
}
/**
 	Roles functions
*/
securityRoles.prototype.createValidate = function() {
	$("#create_role_form").validate({
		rules: {
			roleId: {
				required: true
			},
			roleName: {
				required: true
			},
			roleDescr: {
				required: true
			}
		}
	});

	var validator = $( "#create_role_form" ).validate();


	if(validator.form()) {
		securityObj._roles.createSubmit();
	}
}

securityRoles.prototype.updateValidate = function() {
	$("#update_role_form").validate({
		rules: {
			roleId: {
				required: true
			},
			roleName: {
				required: true
			},
			roleDescr: {
				required: true
			}
		}
	});

	var validator = $( "#update_role_form" ).validate();


	if(validator.form()) {
		securityObj._roles.updateSubmit();
	}
}

securityRoles.prototype.viewAll = function() {
	$.ajax({
		method: "POST",
		url: "/security/roles/viewAll",
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

securityRoles.prototype.createForm = function() {
	$.ajax({
		method: "POST",
		url: "/security/roles/createForm",
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

securityRoles.prototype.createSubmit = function() {
	role_id = $("#roleId").val();
	role_name = $("#roleName").val();
	role_desc = $("#roleDescr").val();

	$.ajax({
		method: "POST",
		url: "/security/roles/add",
		data: {
			role_id: role_id,
			role_name: role_name,
			role_desc: role_desc
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response["status"] == "success") {
				securityObj._roles.viewOne(response["insertedId"]);
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

securityRoles.prototype.editRole = function(role_sno) {
	$.ajax({
		method: "POST",
		url: "/security/roles/editForm",
		data: {'role_sno' : role_sno},
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

securityRoles.prototype.updateSubmit = function() {
	role_id = $("#roleId").val();
	role_name = $("#roleName").val();
	role_desc = $("#roleDescr").val();
	role_sno =  $("#role_sno").val();

	$.ajax({
		method: "POST",
		url: "/security/roles/update",
		data: {
			role_id: role_id,
			role_name: role_name,
			role_desc: role_desc,
			role_sno: role_sno
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response["status"] == "success") {
				securityObj._roles.viewOne(response["updatedId"]);
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

securityRoles.prototype.deleteRecord = function(role_sno) {
	$.ajax({
		method: "POST",
		url: "/security/roles/deleteRecord",
		data: {
			role_sno: role_sno
		},
		success: function( response ) {
			//$("#security_content").html(response);
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response["status"] == "success") {
				securityObj._roles.viewAll();
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

securityRoles.prototype.viewOne = function(role_sno) {
	$.ajax({
		method: "POST",
		url: "/security/roles/viewOne",
		data: {
			role_sno: role_sno
		},
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