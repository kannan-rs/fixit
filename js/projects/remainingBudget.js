function remainingBudget() {
	this.updateId = "";
}

remainingBudget.prototype.getListWithForm = function( options ) {
	event.stopPropagation();
	
	var openAs 		= options && options.openAs ? options.openAs : "";
	var popupType 	= options && options.popupType ? options.popupType : "";
	
	$.ajax({
		method: "POST",
		url: "/projects/remainingBudget/getListWithForm",
		data: {
			openAs 		: openAs,
			popupType 	: popupType,
			projectId 	: projectObj._projects.projectId
		},
		success: function( response ) {
			if(openAs == "popup") {
				$("#popupForAll"+popupType).html( response );
				projectObj._projects.openDialog({"title" : "Paid From Budget"}, popupType);
				formUtilObj.setAsDateFields({"dateField":"date"});
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

remainingBudget.prototype.validate =  function ( openAs, popupType ) {
	var validator = $( "#create_pfbudget_form" ).validate();

	if(validator.form()) {
		projectObj._remainingBudget.addUpdate( openAs, popupType );
	}
};

remainingBudget.prototype.addUpdate = function( openAs, popupType ) {
	var idPrefix 				= "#create_pfbudget_form "
	var date 					= formUtilObj.toMySqlDateFormat($(idPrefix+"#date").val());
	var descr 					= $(idPrefix+"#descr").val();
	var amount 					= $(idPrefix+"#amount").val();

	var url = "/projects/remainingBudget/add";

	if(this.updateId) { url = "/projects/remainingBudget/update"; }
	
	$.ajax({
		method: "POST",
		url: url,
		data: {
			date 				: date,
			descr 				: descr,
			amount 				: amount,
			projectId 			: projectObj._projects.projectId,
			updateId 			: this.updateId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status.toLowerCase() == "success") {
				alert(response.message);
				projectObj._remainingBudget.getListWithForm({"openAs" : openAs, "popupType" : popupType});
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

remainingBudget.prototype.deleteRecord = function( budgetId ) {
	$.ajax({
		method: "POST",
		url: "/projects/remainingBudget/deleteRecord",
		data: {
			remainingBudgetId: budgetId
		},
		success: function( response ) {
			response = $.parseJSON(response);
			alert(response.message);
			if(response.status.toLowerCase() == "success") {
				projectObj._remainingBudget.getListWithForm({"openAs" : "popup", "popupType" : "2"});
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