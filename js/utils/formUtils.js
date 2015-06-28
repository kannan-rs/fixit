/* Utils JS */
function formUtils() {
}

formUtils.prototype.state = [];

formUtils.prototype.getAndSetCountryStatus =  function(stateId, countryId) {
	$.ajax({
		method: "POST",
		url: "/utils/formUtils/getCountryStatus",
		data: {},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				formUtilObj.state = response["state"];
				var country = [];
				for(var i =0 ; i < formUtilObj.state.length; i++) {
					if(country.indexOf(formUtilObj.state[i].country) < 0) {
						country.push(formUtilObj.state[i].country);
						$('#'+countryId).append($('<option>', {
						    value: formUtilObj.state[i].country,
						    text: formUtilObj.state[i].country
						}));
					}

				}
			} else {
				alert(response.message);
			}
			formUtilObj.setCountryStateOfDb();
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

formUtils.prototype.populateState = function( country, stateId ) {
	$('#'+stateId).html("");
	
	$('#'+stateId).append($('<option>', {
	    value: "",
	    text: "--Select State--"
	}));

	for(var i =0 ; i < formUtilObj.state.length; i++) {
		if(formUtilObj.state[i].country == country) {
			$('#'+stateId).append($('<option>', {
			    'value': formUtilObj.state[i].abbreviation,
			    'text': formUtilObj.state[i].name,
			    'class': formUtilObj.state[i].country
			}));
		}
	}
}

formUtils.prototype.setCountryStateOfDb = function() {
	var country = $("#countryDbVal").val();
	var state = $("#stateDbVal").val();

	if(!country) {
		country = $($("#country").children()[1]).val();
	}

	$("#country").val(country).trigger("change");

	if(state) {
		setTimeout(function() {$("#state").val(state)}, 10);
	}
}

formUtils.prototype.createContractorOptionsList = function(contractors) {
	var excludeList 	= !contractors.excludeList ? [] : contractors.excludeList;
	var css 			= {
							"selectedList" 		: {"li" : "ui-state-highlight", "symbol" : "ui-icon ui-icon-minus" }, 
							"searchList"		: {"li" : "ui-state-default", "symbol" : "ui-icon ui-icon-plus" },
							"ownerList" 		: {"li" : "ui-state-default", "symbol" : "" }
						};
	var list 			= contractors.list;
	var type			= contractors.type;

	for(var i =0 ; i < list.length; i++) {
		if(excludeList.indexOf(list[i].id) == -1) {
			var li = "<li class=\""+css[type].li+"\" id=\""+list[i].id+"\" "+(type != "ownerList" ? "draggable=\"true\" ondragstart=\"projectObj._projects.drag(event)\"" : "") +">";
				li += "<div>"+list[i].company+"</div>";
				li += "<div class=\"company\">"+list[i].city+", "+list[i].state+"</div>";
				li += "<span class=\""+css[type].symbol+" search-action\" "+(type != "ownerList" ? "" : "onclick=\"projectObj._projects.removeSelectedContractor(event, this);\"")+">";
				li += (type == "ownerList") ? "<input type=\"radio\" name=\"optionSelectedOwner\" value=\""+list[i].id+"\" />" : "";
				li += "</span>";
				li += "</li>";
			$('#'+contractors.appendTo).append(li);
		}
	}
}

formUtils.prototype.getCustomerList = function() {
	var customer = $.ajax({
		method: "POST",
		url: "/utils/formUtils/getCustomerList",
		data: {},
		async: false
	}).responseText;
	return customer;
}


formUtils.prototype.getAdjusterList = function() {
	var adjuster = $.ajax({
		method: "POST",
		url: "/utils/formUtils/getAdjusterList",
		data: {},
		async: false
	}).responseText;
	return adjuster;
}

formUtils.prototype.setCustomerDataList = function() {
	var response = this.getCustomerList();
	var responseObj = $.parseJSON(response);
	var customer = [];
	if(responseObj.status == "success") {
		customer = responseObj.customer;
	}

	var searchList = {
		"list"				: customer,
		"excludeList" 		: [],
		"appendTo"			: "customerNameList",
		"type" 				: "searchList",
		"functionName" 		: "projectObj._projects.setCustomerId",
		"searchBoxId"		: "searchCustomerName",
		"dbEntryId"			: "customer_id"

	}

	this.setSearchList(searchList);
}

formUtils.prototype.setAdjusterDataList = function(){
	var response = this.getAdjusterList();
	var responseObj = $.parseJSON(response);
	var adjuster = [];
	if(responseObj.status == "success") {
		adjuster = responseObj.adjuster;
	}
	var searchList = {
		"list"				: adjuster,
		"excludeList" 		: [],
		"appendTo"			: "adjusterNameList",
		"type" 				: "searchList",
		"functionName" 		: "projectObj._projects.setAdjusterId",
		"searchBoxId"		: "searchAdjusterName",
		"dbEntryId"			: "adjuster_id"

	}

	this.setSearchList(searchList);
}

formUtils.prototype.setSearchList = function ( searchList ) {
	var excludeList 	= !searchList.excludeList ? [] : searchList.excludeList;
	var css 			= {
							"searchList"		: {"li" : "ui-state-default" },
						};
	var list 			= searchList.list;
	var type 			= searchList.type;
	var appendTo 		= searchList.appendTo;
	var functionName 	= searchList.functionName;

	var dBVal 			= $("#"+searchList.dbEntryId).val();

	for(var i =0 ; i < list.length; i++) {
		if(excludeList.indexOf(list[i].id) == -1) {
			var clickParamsObj = {
				"searchBoxId" 		: appendTo,
				"first_name" 		: list[i].first_name,
				"last_name" 		: list[i].last_name,
				"searchId"			: list[i].sno,
				"email" 			: list[i].email
			};
			if(dBVal == list[i].sno) {
				$("#"+searchList.searchBoxId).val(list[i].first_name+" "+list[i].last_name);
			}
			var clickParams = JSON.stringify(clickParamsObj);
			var li = "<li class=\""+css[type].li+"\" id=\""+list[i].id+"\" onclick='"+functionName+"(event, this,"+clickParams+")'>";
				li += "<div>"+list[i].first_name+" "+list[i].last_name+"</div>";
				li += "<div class=\"second\">"+list[i].email+"</div>";
				li += "</li>";
			$('#'+appendTo).append(li);
		}
	}
}
