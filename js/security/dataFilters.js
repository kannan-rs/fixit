function securityDataFilters() {

}
/**
 	DataFilters Tab related functions
*/
securityDataFilters.prototype.errorMessage = function () {
	return {
		dataFilterId: {
			required:  _lang.english.errorMessage.dataFilterForm.dataFilterId,
			maxlength:  _lang.english.errorMessage.dataFilterForm.dataFilterId,
			minlength:  _lang.english.errorMessage.dataFilterForm.dataFilterId,
			digits:  _lang.english.errorMessage.dataFilterForm.dataFilterId
		},
		dataFilterName: {
			required: _lang.english.errorMessage.dataFilterForm.dataFilterName
		},
		dataFilterDescr: {
			required: _lang.english.errorMessage.dataFilterForm.dataFilterDescr
		}
	};
}

securityDataFilters.prototype.validationRules = function() {
	return {
		dataFilterId: {
			required: true,
			maxlength:5,
			minlength:5,
			digits:true
		},
		dataFilterName: {
			required: true
		},
		dataFilterDescr: {
			required: true
		}
	}
}

securityDataFilters.prototype.createValidate = function() {
	var validator = $("#create_dataFilter_form").validate({
		rules: this.validationRules(),
		messages: this.errorMessage()
	});

	if(validator.form()) {
		securityObj._dataFilters.createSubmit();
	}
}

securityDataFilters.prototype.updateValidate = function() {
	var validator = $("#update_dataFilter_form").validate({
		rules: this.validationRules(),
		messages: this.errorMessage()
	});

	if(validator.form()) {
		securityObj._dataFilters.updateSubmit();
	}
}

securityDataFilters.prototype.viewAll = function() {
	$.ajax({
		method: "POST",
		url: "/security/dataFilters/viewAll",
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

securityDataFilters.prototype.createForm = function() {
	$.ajax({
		method: "POST",
		url: "/security/dataFilters/createForm",
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

securityDataFilters.prototype.createSubmit = function() {
	dataFilter_id = $("#dataFilterId").val();
	dataFilter_name = $("#dataFilterName").val();
	dataFilter_desc = $("#dataFilterDescr").val();

	$.ajax({
		method: "POST",
		url: "/security/dataFilters/add",
		data: {
			dataFilter_id: dataFilter_id,
			dataFilter_name: dataFilter_name,
			dataFilter_desc: dataFilter_desc
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				securityObj._dataFilters.viewOne(response.insertedId);
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

securityDataFilters.prototype.editDataFilter = function(dataFilter_sno) {
	$.ajax({
		method: "POST",
		url: "/security/dataFilters/editForm",
		data: {'dataFilter_sno' : dataFilter_sno},
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

securityDataFilters.prototype.updateSubmit = function() {
	dataFilter_id = $("#dataFilterId").val();
	dataFilter_name = $("#dataFilterName").val();
	dataFilter_desc = $("#dataFilterDescr").val();
	dataFilter_sno =  $("#dataFilter_sno").val();

	$.ajax({
		method: "POST",
		url: "/security/dataFilters/update",
		data: {
			dataFilter_id: dataFilter_id,
			dataFilter_name: dataFilter_name,
			dataFilter_desc: dataFilter_desc,
			dataFilter_sno: dataFilter_sno
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response.status.toLowerCase() == "success") {
				securityObj._dataFilters.viewOne(response["updatedId"]);
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

securityDataFilters.prototype.deleteRecord = function(dataFilter_sno) {
	$.ajax({
		method: "POST",
		url: "/security/dataFilters/deleteRecord",
		data: {
			dataFilter_sno: dataFilter_sno
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response["message"]);
			if(response["status"] == "success") {
				securityObj._dataFilters.viewAll();
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

securityDataFilters.prototype.viewOne = function(dataFilter_sno) {
	$.ajax({
		method: "POST",
		url: "/security/dataFilters/viewOne",
		data: {
			dataFilter_sno: dataFilter_sno
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

