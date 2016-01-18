var _claim_suborgation = (function() {
	"use strict";

	function errorMessage() {
        return {
            searchCustomerName: {
                required: _lang.english.errorMessage.claimSuborgationForm.customer_name
            },
            climant_name : {
            	required: _lang.english.errorMessage.claimSuborgationForm.climant_name
            },
            addressLine1: {
                required: _lang.english.errorMessage.claimSuborgationForm.addressLine1
            },
            addressLine2: {
                required: _lang.english.errorMessage.claimSuborgationForm.addressLine2
            },
            city: {
                required: _lang.english.errorMessage.claimSuborgationForm.city
            },
            country: {
                required: _lang.english.errorMessage.claimSuborgationForm.country
            },
            state: {
                required: _lang.english.errorMessage.claimSuborgationForm.state
            },
            zipCode: {
                required: _lang.english.errorMessage.claimSuborgationForm.zipCode
            },
            status: {
                required:_lang.english.errorMessage.claimSuborgationForm.status
            },
            description: {
                required:_lang.english.errorMessage.claimSuborgationForm.description
            },
        };
    };

    function validationRules() {

    };

    function createSubmit() {
		var searchCustomerName	= $("#searchCustomerName").val();
		var customer_id			= $("#customer_id").val();
		var climant_name		= $("#climant_name").val();
		var addressLine1		= $("#addressLine1").val();
		var addressLine2		= $("#addressLine2").val();
		var city				= $("#city").val();
		var country				= $("#country").val();
		var state				= $("#state").val();
		var zipCode				= $("#zipCode").val();
		var status				= $("#status").val();
		var description			= $("#description").val();

        $.ajax({
            method: "POST",
            url: "/claims/suborgation/add",
            data: {
            	claim_id : _claims._claim_id,
            	customer_id	: customer_id,
				climant_name : climant_name,
				addressLine1 : addressLine1,
				addressLine2 : addressLine2,
				city : city,
				country : country,
				state : state,
				zipCode : zipCode,
				status : status,
				description : description
            },
            success: function (response) {
                response = $.parseJSON(response);
                if (response.status.toLowerCase() === "success") {
                    alert(response.message);
                    //_claim_suborgation.viewOne(response.insertedId);
                    _claim_suborgation.viewAll();
                } else if (response.status.toLowerCase() === "error") {
                    alert(response.message);
                }
            },
            error: function (error) {
                error = error;
            }
        }).fail(function (failedObj) {
            fail_error = failedObj;
        });
    };

    function updateSubmit() {
        var customer_name       = $("#customer_name").val();
        var customer_id         = $("#customer_id").val();
        var addressLine1        = $("#addressLine1").val();
        var addressLine2        = $("#addressLine2").val();
        var city                = $("#city").val();
        var country             = $("#country").val();
        var state               = $("#state").val();
        var zipCode             = $("#zipCode").val();
        var contactPhoneNumber  = $("#contactPhoneNumber").val();
        var emailId             = $("#emailId").val();
        var claim_number        = $("#claim_number").val();
        var description         = $("#description").val();

        $.ajax({
            method: "POST",
            url: "/claims/suborgation/update",
            data: {
                customer_name : customer_name,
                customer_id : customer_id,
                addressLine1 : addressLine1,
                addressLine2 : addressLine2,
                city : city,
                country : country,
                state : state,
                zipCode : zipCode,
                contactPhoneNumber : contactPhoneNumber,
                emailId : emailId,
                claim_number : claim_number,
                description : description,
                claim_id : _claims._claim_id
            },
            success: function (response) {
                response = $.parseJSON(response);
                if (response.status.toLowerCase() === "success") {
                    alert(response.message);
                    $("#popupForAll").dialog("close");
                    _claim_suborgation.viewOne(_claim_suborgation._suborgation_id);
                } else if (response.status.toLowerCase() === "error") {
                    alert(response.message);
                }
            },
            error: function (error) {
                error = error;
            }
        }).fail(function (failedObj) {
            fail_error = failedObj;
        });
    };

    function presetInputForm( form ) {
        $(".customer-search-result").hide();
        _utils.setCustomerDataList();
        _utils.getAndSetCountryStatus(form+"_suborgation_form");
    };

    function presetViewOne() {
        _claim_suborgation_notes.viewAll(_claim_suborgation._suborgation_id);
        _claim_suborgation_docs.viewAll(_claim_suborgation._suborgation_id);
    };

    function presetViewAll() {

    };
	return {
		viewAll : function() {
			var fail_error = null;
            
            $.ajax({
                method: "POST",
                url: "/claims/suborgation/viewAll",
                data: {
                	claim_id 	: _claims._claim_id
                },
                success: function (response) {
                    $("#tabs_claim_subrogation").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
		},
		createForm : function( options ) {
			var fail_error = null;

			var openAs = options && options.openAs ? options.openAs : "";
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/suborgation/createForm",
                data: {
                	openAs		: openAs,
                	claim_id 	: _claims._claim_id
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    _projects.openDialog({title: "Create Suborgation"});
                    presetInputForm( "create" );
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
		},
		createValidate: function() {
			var isCustomerPresent   = false;
            var customer_id         = $("#customer_id").val();
            var searchCustomerName  = $("#searchCustomerName").val();

            var validator = $("#create_suborgation_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            }).form();

            isCustomerPresent = _utils.validateCustomerByName(customer_id, searchCustomerName);

            if(validator && (!customer_id || !isCustomerPresent)) {
                validator = false;
                alert("Plesae select proper customer from the search list");
            }

            validator = validator && _utils.cityFormValidation() ? false : validator;

            if (validator) {
               createSubmit();
            }
		},
		editForm : function() {

		},
		updateValidate: function() {

		},
		viewOne : function( suborgation_id ) {
			this._suborgation_id = suborgation_id;
    		var fail_error = null;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/suborgation/viewOne",
                data: {
                	claim_id 		: _claims._claim_id,
                    suborgation_id : _claim_suborgation._suborgation_id
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    _projects.openDialog({title: "Suborgation Details"});
                    $("#suborgation_accordion").accordion(
                        {
                            collapsible: true,
                            icons: {header: "ui-icon-plus", activeHeader: "ui-icon-minus"}
                        }
                   );
                    presetViewOne();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
		},
		deleteRecord: function() {

		}
	};
})();