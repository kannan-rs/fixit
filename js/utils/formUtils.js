/* Utils JS */
function formUtils() {
}

formUtils.prototype.state = [];

formUtils.prototype.getCountryStatus =  function(stateId, countryId) {
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
			    value: formUtilObj.state[i].abbreviation,
			    text: formUtilObj.state[i].name,
			    class: formUtilObj.state[i].country
			}));
		}
	}
}