function securityOperations() {

}
/**
 	Operations functions
*/
securityOperations.prototype.createValidate = function() {
	$("#create_operation_form").validate({
		rules: {
			operationId: {
				required: true
			},
			operationName: {
				required: true,
			},
			operationDescr: {
				required: {
					depends: function(element) {
						if('' == $('#ope_descr').val()){
	                        $('#ope_descr').val('');
                    	}
                    	return true;
					}
				}
			}
		}
	});

	var validator = $( "#create_operation_form" ).validate();


	if(validator.form()) {
		securityObj._operations.createSubmit();
	}
}

securityOperations.prototype.updateValidate = function() {
	$("#update_operation_form").validate({
		rules: {
			operationId: {
				required: true
			},
			operationName: {
				required: true,
			},
			operationDescr: {
				required: {
					depends: function(element) {
						if('' == $('#ope_descr').val()){
	                        $('#ope_descr').val('');
                    	}
                    	return true;
					}
				}
			}
		}
	});

	var validator = $( "#update_operation_form" ).validate();


	if(validator.form()) {
		securityObj._operations.updateSubmit();
	}
}

securityOperations.prototype.viewAll = function() {
	$.ajax({
		method: "POST",
		url: "/security/operations/viewAll",
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

securityOperations.prototype.createForm = function() {
	$.ajax({
		method: "POST",
		url: "/security/operations/createForm",
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

securityOperations.prototype.createSubmit = function() {
	ope_id = $("#operationId").val();
	ope_name = $("#operationName").val();
	ope_desc = $("#operationDescr").val();

	$.ajax({
		method: "POST",
		url: "/security/operations/add",
		data: {
			ope_id: ope_id,
			ope_name: ope_name,
			ope_desc: ope_desc
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				securityObj._operations.viewOne(response.insertedId);
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

securityOperations.prototype.editOperation = function(ope_sno) {
	$.ajax({
		method: "POST",
		url: "/security/operations/editForm",
		data: {'ope_sno' : ope_sno},
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

securityOperations.prototype.updateSubmit = function() {
	ope_id = $("#operationId").val();
	ope_name = $("#operationName").val();
	ope_desc = $("#operationDescr").val();
	ope_sno =  $("#ope_sno").val();

	$.ajax({
		method: "POST",
		url: "/security/operations/update",
		data: {
			ope_id: ope_id,
			ope_name: ope_name,
			ope_desc: ope_desc,
			ope_sno: ope_sno
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response.status.toLowerCase() == "success") {
				securityObj._operations.viewOne(response.updatedId);
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

securityOperations.prototype.delete = function(ope_sno) {
	$.ajax({
		method: "POST",
		url: "/security/operations/delete",
		data: {
			ope_sno: ope_sno
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response["status"] == "success") {
				securityObj._operations.viewAll();
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

securityOperations.prototype.viewOne = function(ope_id) {
	$.ajax({
		method: "POST",
		url: "/security/operations/viewOne",
		data: {
			ope_id: ope_id
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

