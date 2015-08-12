/* Utils JS */
function utils() {
}

utils.prototype.state = [];

utils.prototype.getAndSetCountryStatus =  function(moduleId) {
	$.ajax({
		method: "POST",
		url: "/utils/formUtils/getCountryStatus",
		data: {},
		success: function( response ) {
			response = $.parseJSON(response);
			if(response.status == "success") {
				utilObj.state = response["state"];
				var country = [];
				for(var i =0 ; i < utilObj.state.length; i++) {
					if(country.indexOf(utilObj.state[i].country) < 0) {
						country.push(utilObj.state[i].country);
						$('#'+moduleId+" #country").append($('<option>', {
						    value: utilObj.state[i].country,
						    text: utilObj.state[i].country
						}));
					}

				}
			} else {
				alert(response.message);
			}
			utilObj.setCountryStateOfDb( moduleId );
		},
		error: function( error ) {
			error = error;
		}
	})
	.fail(function ( failedObj ) {
		fail_error = failedObj;
	});
}

utils.prototype.populateState = function( country, moduleId ) {
	$('#'+moduleId+" #state").html("");
	
	$('#'+moduleId+" #state").append($('<option>', {
	    value: "",
	    text: "--Select State--"
	}));

	for(var i =0 ; i < utilObj.state.length; i++) {
		if(utilObj.state[i].country == country) {
			$('#'+moduleId+" #state").append($('<option>', {
			    'value': utilObj.state[i].abbreviation,
			    'text': utilObj.state[i].name,
			    'class': utilObj.state[i].country
			}));
		}
	}
}

utils.prototype.setCountryStateOfDb = function( moduleId ) {
	var country = $("#countryDbVal").val();
	var state = $("#stateDbVal").val();

	if(!country) {
		country = $($("#"+moduleId+" #country").children()[1]).val();
	}

	$("#"+moduleId+" #country").val(country).trigger("change");

	if(state) {
		setTimeout(function() {$("#"+moduleId+" #state").val(state)}, 10);
	}
}

utils.prototype.createContractorOptionsList = function(contractors) {
	var excludeList 	= !contractors.excludeList ? [] : contractors.excludeList;
	var css 			= {
							"selectedList" 		: {"li" : "ui-state-highlight", "symbol" : "ui-icon ui-icon-minus" }, 
							"searchList"		: {"li" : "ui-state-default", "symbol" : "ui-icon ui-icon-plus" },
							"ownerList" 		: {"li" : "ui-state-default", "symbol" : "" }
						};
	var list 			= contractors.list;
	var type			= contractors.type;
	var prefixId 		= contractors.prefixId;

	for(var i =0 ; i < list.length; i++) {
		if(excludeList.indexOf(list[i].id) == -1) {
			
			var inputRadio = " ";
			if(type == "ownerList") {
				inputRadio = "<input type=\"radio\" name=\"optionSelectedOwner\" value=\""+list[i].id+"\" />";
			}
			
			var li = "<li class=\""+css[type].li+"\" id=\""+prefixId+list[i].id+"\" "+(type != "ownerList" ? "draggable=\"true\" ondragstart=\"projectObj._projects.drag(event)\"" : "");
				li += " data-contractorid = "+list[i].id;
				li += ">";
				li += "<div>"+list[i].company+"</div>";
				li += "<div class=\"company\">"+list[i].city+", "+list[i].state+"</div>";
				li += "<span class=\""+css[type].symbol+" search-action\" ";
				li += " data-contractorid = "+list[i].id;
				li += " data-prefixid = "+prefixId;
				//li + = clickFn;
				li += ">";
				li += inputRadio
				li += "</span>";
				li += "</li>";
			$('#'+contractors.appendTo).append(li);
		}
	}
}

utils.prototype.createAdjusterOptionsList = function(adjuster) {
	var excludeList 	= !adjuster.excludeList ? [] : adjuster.excludeList;
	var css 			= {
							"selectedList" 		: {"li" : "ui-state-highlight", "symbol" : "ui-icon ui-icon-minus" }, 
							"searchList"		: {"li" : "ui-state-default", "symbol" : "ui-icon ui-icon-plus" },
							"ownerList" 		: {"li" : "ui-state-default", "symbol" : "" }
						};
	var list 			= adjuster.list;
	var type			= adjuster.type;
	var prefixId 		= adjuster.prefixId;

	for(var i =0 ; i < list.length; i++) {
		if(excludeList.indexOf(list[i].id) == -1) {
			
			var inputRadio = " ";
			
			var li = "<li class=\""+css[type].li+"\" id=\""+prefixId+list[i].id+"\" "+(type != "ownerList" ? "draggable=\"true\" ondragstart=\"projectObj._projects.drag(event)\"" : "");
				li += " data-adjusterid = "+list[i].id;
				li += ">";
				li += "<div>"+list[i].company_name+"</div>";
				li += "<div class=\"company\">"+list[i].city+", "+list[i].state+"</div>";
				li += "<span class=\""+css[type].symbol+" search-action\" ";
				li += " data-adjusterid = "+list[i].id;
				li += " data-prefixid = "+prefixId;
				li += ">";
				li += inputRadio
				li += "</span>";
				li += "</li>";
			$('#'+adjuster.appendTo).append(li);
		}
	}
}


utils.prototype.getCustomerList = function() {
	var customer = $.ajax({
		method: "POST",
		url: "/utils/formUtils/getCustomerList",
		data: {},
		async: false
	}).responseText;
	return customer;
}


utils.prototype.getAdjusterList = function() {
	var adjuster = $.ajax({
		method: "POST",
		url: "/utils/formUtils/getAdjusterList",
		data: {},
		async: false
	}).responseText;
	return adjuster;
}

utils.prototype.setCustomerDataList = function() {
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

utils.prototype.setAdjusterDataList = function(){
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

utils.prototype.setSearchList = function ( searchList ) {
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

utils.prototype.setAsDateFields = function(options) {
	$( "#"+options.dateField ).datepicker( {
		showAnim		: "fadeIn",
		dateFormat 		: "mm/dd/y"
	});
}

/*
	options = {
		fromDateField 	: string 	<ID for Date Field of starting from field>,
		toDateField		: string 	<ID for Date Field of ending with field>,
		numberOfMonths	: Int 		<Number of month to show> / Default "3" [optional]
	}
*/
utils.prototype.setAsDateRangeFields = function(options) {
	var numberOfMonths = options.numberOfMonths ? options.numberOfMonths : 3;
	$(function() {
		$( "#"+options.fromDateField ).datepicker({
			defaultDate 	: "+1w",
			changeMonth 	: true,
			numberOfMonths 	: numberOfMonths,
			showAnim		: "fadeIn",
			dateFormat 		: "mm/dd/y",
			
			onClose 		: function( selectedDate ) {
				$( "#"+options.toDateField ).datepicker( "option", "minDate", selectedDate );
			}
		});
		$( "#"+options.toDateField ).datepicker({
			defaultDate 	: "+1w",
			changeMonth 	: true,
			numberOfMonths 	: numberOfMonths,
			showAnim		: "fadeIn",
			dateFormat 		: "mm/dd/y",
			
			onClose 		: function( selectedDate ) {
				$( "#"+options.fromDateField ).datepicker( "option", "maxDate", selectedDate );
			}
		});
	});
}

/*
	Convert from "mm/dd/yy" to "yyyy-mm-dd"
*/
utils.prototype.toMySqlDateFormat = function( dateStr ) {
	var dateStrArr 		= dateStr.split("/");
	var month 		= dateStrArr[0];
	var date 		= dateStrArr[1];
	var year 		= "20"+dateStrArr[2];
	return (year+"-"+month+"-"+date);
}

/*
	Convert from "yyyy-mm-dd" to "mm/dd/yy"
*/
utils.prototype.toDisplayDateFormat = function( dateStr ) {
	var dateStrArr 		= dateStr.split("-");
	var year 		= dateStrArr[0].substr(2,2);
	var month 		= dateStrArr[1];
	var date 		= dateStrArr[2];
	return (month+"/"+date+"/"+year);
}

/*
	Number display formatting. Adding "," to seperate numbers
	US 		: 4,231,546.44
*/
utils.prototype.toDisplayNumberFormat = function( number ) {
	return number.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

utils.prototype.setStatus = function(statusDD, statusDbVal) {
	var statusDbVal = typeof(statusDbVal) != "undefined" ? statusDbVal : "";
	var status = $("#"+statusDbVal).val();
	status = typeof(status) != "undefined" && status != "" ? status.toLocaleLowerCase() : "";
	if(typeof(status) != "undefined" && status != "" && $("#"+statusDD).length && $("#"+statusDD+" option[value='"+status+"']").length) {
		$("#"+statusDD).val(status);
	} else {
		$("#"+statusDD).val("active");
	}
}