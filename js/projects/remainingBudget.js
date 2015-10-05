function remainingbudget() {
}

remainingbudget.prototype.getListWithForm = function( options ) {
	event.stopPropagation();
	
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";
	var budgetId	= options && options.budgetId ? options.budgetId : "";
	
	$.ajax({
		method: "POST",
		url: "/projects/remainingbudget/getListWithForm",
		data: {
			openAs 		: openAs,
			popupType 	: popupType,
			projectId 	: projectObj._projects.projectId,
			budgetId 	: budgetId
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Paid From Budget"}, popupType);
				utilObj.setAsDateFields({"dateField":"date"});
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

remainingbudget.prototype.validate =  function ( openAs, popupType ) {
	var validator = $( "#create_pfbudget_form" ).validate({
		rules: {
			amount : {
				number : true
			}
		}
	});

	if(validator.form()) {
		projectObj._remainingbudget.addUpdate( openAs, popupType );
	}
};

remainingbudget.prototype.addUpdate = function( openAs, popupType ) {
	var idPrefix 				= "#create_pfbudget_form ";
	var budgetId 				= $(idPrefix+"#budgetId").val();
	var date 					= utilObj.toMySqlDateFormat($(idPrefix+"#date").val());
	var descr 					= $(idPrefix+"#descr").val();
	var amount 					= $(idPrefix+"#amount").val();
	var urlSuffix				= budgetId == "" ? "add" : "update";

	var url = "/projects/remainingbudget/"+urlSuffix;
	
	$.ajax({
		method: "POST",
		url: url,
		data: {
			date 				: date,
			descr 				: descr,
			amount 				: amount,
			projectId 			: projectObj._projects.projectId,
			budgetId 			: budgetId,
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._remainingbudget.getListWithForm({"openAs" : openAs, "popupType" : popupType});
				projectObj._projects.viewOnlyBudget();
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

remainingbudget.prototype.editRecordForm = function( budgetId ) {
	projectObj._remainingbudget.getListWithForm({"openAs" : "popup", "popupType" : "2", "budgetId" : budgetId});
};

remainingbudget.prototype.deleteRecord = function( budgetId ) {
	$.ajax({
		method: "POST",
		url: "/projects/remainingbudget/deleteRecord",
		data: {
			remainingbudgetId: budgetId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response.message);
			if(response.status.toLowerCase() == "success") {
				projectObj._remainingbudget.getListWithForm({"openAs" : "popup", "popupType" : "2"});
				projectObj._projects.viewOnlyBudget();
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