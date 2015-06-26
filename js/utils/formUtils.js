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