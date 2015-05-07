function securityPermissions() {

}

securityPermissions.prototype.viewAll = function() {
	$.ajax({
		method: "POST",
		url: "/security/permissions/viewAll",
		data: {},
		success: function( response ) {
			$("#security_content").html(response);
			$(".error").hide();
			$(".success").hide();
			$(".user_type_as").hide();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
};

securityPermissions.prototype.getPermissions = function() {
	var user_id = $("#users").val();

	user_type = $("#users").find(":selected").attr("data_user_type");
	$("#user_type").text("'"+user_type+"'");
	$(".user_type_as").show();

	$.ajax({
		method: "POST",
		url: "/security/permissions/getPermissions",
		data: {
			user_id: user_id
		},
		success: function( response ) {
			responseObj = jQuery.parseJSON( response );
			if(responseObj.status == "success") {
				$(".error").hide().text('');
				$(".success").hide().text('');
				securityObj._permissions.clearPermissions();
				if(responseObj.data && responseObj.data.length) {
					securityObj._permissions.resetPermissions(responseObj.data);
				}
			} else if(responseObj.status == "error") {
				$(".error").show().text(responseObj.message);
			}
		},
		error: function( error ) {
			$(".error").show().text( error );
		}
	})
	.fail(function ( failedObj ) {
		$(".error").show().text( failedObj );
	});
};

securityPermissions.prototype.setPermissions = function() {
	var user_id = role_id = fn_id = op_id = df_id = "";
	var op_selected = fn_selected = role_selected = df_selected = "";
	var user_sno = role_sno = op_sno = fn_sno = df_sno = "";
	var user_type = "";

	user_id = $("#users").val();

	if(user_id == 0) {
		$(".user_type_as").hide();
		$("#user_type").text("");
		return;
	}

	role_selected = $("input[type='radio'][name='role']:checked");
	if (role_selected.length > 0) {
		role_sno = role_selected.val();
		role_id = role_selected.attr("data_role_id");

	}

	op_selected = $("input[type='checkbox'][name='operations']:checked");

	if(op_selected && op_selected.length) {
		for(var opIdx = 0; opIdx < op_selected.length; opIdx++) {
			if(opIdx > 0) {
				op_sno +=",";
				op_id += ",";
			}
			op_sno += $(op_selected[opIdx]).val();
			op_id += $(op_selected[opIdx]).attr("data_operation_id");
		}
	}

	fn_selected = $("input[type='checkbox'][name='functions']:checked");

	if(fn_selected && fn_selected.length) {
		for(var fnIdx = 0; fnIdx < fn_selected.length; fnIdx++) {
			if(fnIdx > 0) {
				fn_sno += ",";
				fn_id += ",";
			}
			fn_sno = $(fn_selected[fnIdx]).val();
			fn_id += $(fn_selected[fnIdx]).attr("data_function_id");
		}
	}

	df_selected = $("input[type='checkbox'][name='dataFilters']:checked");

	if(df_selected && df_selected.length) {
		for(var dfIdx = 0; dfIdx < df_selected.length; dfIdx++) {
			if(dfIdx > 0) {
				df_sno += ",";
				df_id += ",";
			}
			df_id += $(df_selected[dfIdx]).attr("data_data_filter_id");
			df_sno += $(df_selected[dfIdx]).val();
		}
	}

	if(user_id == "" || role_id == "" || op_id == "" || fn_id == "" || df_id == "") {
		alert("Please select atleast one option from each section");
		return;
	}

	$.ajax({
		method: "POST",
		url: "/security/permissions/setPermissions",
		data: {
			user_id: user_id,
			role_id: role_id,
			op_id: op_id,
			fn_id: fn_id,
			df_id: df_id
		},
		success: function( response ) {
			responseObj = jQuery.parseJSON( response );
			if(responseObj.status == "success") {
				if(responseObj.data == "inserted") {
					$(".success").show().text("Permissions Added successfully");
				} else if(responseObj.data == "updated") {
					$(".success").show().text("Permissions Updated successfully");
				} 
			} else if( response.status == "error" ) {
				$(".error").show().text( response.message );
			}
		},
		error: function( error ) {
			$(".error").show().text(error);
		}
	})
	.fail(function ( failedObj ) {
		$(".error").show().text( failedObj );
	});
}

securityPermissions.prototype.clearPermissions = function() {
	var role_selected = $("input[type='radio'][name='role']:checked");
	if (role_selected.length > 0) {
		$(role_selected).prop("checked", false);
	}

	var op_selected = $("input[type='checkbox'][name='operations']:checked");
	if(op_selected && op_selected.length) {
		for(var opIdx = 0; opIdx < op_selected.length; opIdx++) {
			$(op_selected[opIdx]).prop("checked", false);
		}
	}

	var fn_selected = $("input[type='checkbox'][name='functions']:checked");
	if(fn_selected && fn_selected.length) {
		for(var fnIdx = 0; fnIdx < fn_selected.length; fnIdx++) {
			$(fn_selected[fnIdx]).prop("checked", false);
		}
	}

	var df_selected = $("input[type='checkbox'][name='dataFilters']:checked");
	if(df_selected && df_selected.length) {
		for(var dfIdx = 0; dfIdx < df_selected.length; dfIdx++) {
			$(df_selected[dfIdx]).prop("checked", false);
		}
	}
	//$("#print_api").text("");
};

securityPermissions.prototype.resetPermissions = function( data ) {

	data = data[0];
	//$("#print_api").text(JSON.stringify(data)).show();

	if(data.role_id && data.role_id != "") {
		role_selected = $("input[type='radio'][name='role'][data_role_id='"+data.role_id+"']").prop("checked", true);
	}

	if(data.op_id && data.op_id != "" ) {
		var op_id = data.op_id.split(",");
		if(op_id && op_id.length) {
			for(var opIdx = 0; opIdx < op_id.length; opIdx++) {
				$("input[type='checkbox'][name='operations'][data_operation_id='"+op_id[opIdx]+"']").prop("checked", true);
			}
		}
	}

	if(data.function_id && data.function_id != "" ) {
		var fn_id = data.function_id.split(",");
		if(fn_id && fn_id.length) {
			for(var fnIdx = 0; fnIdx < fn_id.length; fnIdx++) {
				$("input[type='checkbox'][name='functions'][data_function_id='"+fn_id[fnIdx]+"']").prop("checked", true);
			}
		}
	}

	if(data.data_filter_id && data.data_filter_id != "" ) {
		var df_id = data.data_filter_id.split(",");
		if(df_id && df_id.length) {
			for(var dfIdx = 0; dfIdx < df_id.length; dfIdx++) {
				$("input[type='checkbox'][name='dataFilters'][data_data_filter_id='"+df_id[dfIdx]+"']").prop("checked", true);
			}
		}
	}
};