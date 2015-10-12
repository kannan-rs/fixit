function securityFunctions() {

}
/**
 	Functions Tab related functions
*/

securityFunctions.prototype.errorMessage = function () {
	return {
		functionId: {
			required:  _lang.english.errorMessage.functionForm.functionId,
			maxlength:  _lang.english.errorMessage.functionForm.functionId,
			minlength:  _lang.english.errorMessage.functionForm.functionId,
			digits:  _lang.english.errorMessage.functionForm.functionId
		},
		functionName: {
			required: _lang.english.errorMessage.functionForm.functionName
		},
		functionDescr: {
			required: _lang.english.errorMessage.functionForm.functionDescr
		}
	};
}

securityFunctions.prototype.validationRules = function() {
	return {
		functionId: {
			required: true,
			maxlength:5,
			minlength:5,
			digits:true
		},
		functionName: {
			required: true
		},
		functionDescr: {
			required: true
		}
	}
}

securityFunctions.prototype.createValidate = function() {
	var validator = $("#create_function_form").validate({
		rules: this.validationRules(),
		messages: this.errorMessage()
	});

	if(validator.form()) {
		securityObj._functions.createSubmit();
	}
}

securityFunctions.prototype.updateValidate = function() {
	var validator = $("#update_function_form").validate({
		rules: this.validationRules(),
		messages: this.errorMessage()
	});

	if(validator.form()) {
		securityObj._functions.updateSubmit();
	}
}

securityFunctions.prototype.viewAll = function() {
	$.ajax({
		method: "POST",
		url: "/security/functions/viewAll",
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

securityFunctions.prototype.createForm = function() {
	$.ajax({
		method: "POST",
		url: "/security/functions/createForm",
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

securityFunctions.prototype.createSubmit = function() {
	function_id = $("#functionId").val();
	function_name = $("#functionName").val();
	function_desc = $("#functionDescr").val();

	$.ajax({
		method: "POST",
		url: "/security/functions/add",
		data: {
			function_id: function_id,
			function_name: function_name,
			function_desc: function_desc
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				securityObj._functions.viewOne(response.insertedId);
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

securityFunctions.prototype.editFunction = function(function_sno) {
	$.ajax({
		method: "POST",
		url: "/security/functions/editForm",
		data: {'function_sno' : function_sno},
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

securityFunctions.prototype.updateSubmit = function() {
	function_id = $("#functionId").val();
	function_name = $("#functionName").val();
	function_desc = $("#functionDescr").val();
	function_sno =  $("#function_sno").val();

	$.ajax({
		method: "POST",
		url: "/security/functions/update",
		data: {
			function_id: function_id,
			function_name: function_name,
			function_desc: function_desc,
			function_sno: function_sno
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response.status.toLowerCase() == "success") {
				securityObj._functions.viewOne(response["updatedId"]);
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

securityFunctions.prototype.deleteRecord = function(function_sno) {
	$.ajax({
		method: "POST",
		url: "/security/functions/deleteRecord",
		data: {
			function_sno: function_sno
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response["status"] == "success") {
				securityObj._functions.viewAll();
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

securityFunctions.prototype.viewOne = function(function_sno) {
	$.ajax({
		method: "POST",
		url: "/security/functions/viewOne",
		data: {
			function_sno: function_sno
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