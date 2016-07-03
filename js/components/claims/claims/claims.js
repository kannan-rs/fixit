/**
    Projects functions
*/
var _claims = (function () {
    "use strict";

    var _claim_id = "";

    function errorMessage() {
        return {
            searchCustomerName: {
                required: _lang.english.errorMessage.claimForm.customer_name
            },
            addressLine1: {
                required: _lang.english.errorMessage.claimForm.addressLine1
            },
            addressLine2: {
                required: _lang.english.errorMessage.claimForm.addressLine2
            },
            city: {
                required: _lang.english.errorMessage.claimForm.city
            },
            country: {
                required: _lang.english.errorMessage.claimForm.country
            },
            state: {
                required: _lang.english.errorMessage.claimForm.state
            },
            zipCode: {
                required: _lang.english.errorMessage.claimForm.zipCode
            },
            contactPhoneNumber: {
                required:_lang.english.errorMessage.claimForm.contactPhoneNumber
            },
            emailId: {
                required:_lang.english.errorMessage.claimForm.emailId
            },
            claim_number: {
                required:_lang.english.errorMessage.claimForm.claim_number
            },
            description: {
                required:_lang.english.errorMessage.claimForm.description
            },
        };
    };

    function validationRules() {

    };

    function createSubmit() {
        var customer_name       = $("#customer_name").val();
        var customer_id         = $("#customer_id").val();

        var addressLine1        = $("#addressLine1").val();
        var addressLine2        = $("#addressLine2").val();
        var city                = $("#cityDbVal").val();
        var country             = $("#countryDbVal").val();
        var state               = $("#stateDbVal").val();
        var zipCode             = $("#zipcodeDbVal").val();

        var is_property_address_same    = $("#is_property_address_same:checked").val();
        var property_addr1              = !is_property_address_same ? $("#property_addressLine1").val() : addressLine1;
        var property_addr2              = !is_property_address_same ? $("#property_addressLine2").val() : addressLine2;
        var property_addr_city          = !is_property_address_same ? $("#property_city").val() : city;
        var property_addr_country       = !is_property_address_same ? $("#property_country").val() : country;
        var property_addr_state         = !is_property_address_same ? $("#property_state").val() : state;
        var property_addr_pin           = !is_property_address_same ? $("#property_zipCode").val() : zipCode;

        var contactPhoneNumber  = $("#contactPhoneNumber").val();
        var emailId             = $("#emailId").val();
        var claim_number        = $("#claim_number").val();
        var description         = $("#description").val();

        $.ajax({
            method: "POST",
            url: "/claims/claims/add",
            data: {
                customer_name   : customer_name,
                customer_id     : customer_id,
                is_property_address_same    : is_property_address_same,
                property_addr1              : property_addr1,
                property_addr2              : property_addr2,
                property_addr_city          : property_addr_city,
                property_addr_country       : property_addr_country,
                property_addr_state         : property_addr_state,
                property_addr_pin           : property_addr_pin,
                contactPhoneNumber  : contactPhoneNumber,
                emailId             : emailId,
                claim_number        : claim_number,
                description         : description
            },
            success: function (response) {
                if(!_utils.is_logged_in( response )) { return false; }
                response = $.parseJSON(response);
                if (response.status.toLowerCase() === "success") {
                    alert(response.message);
                    _claims.viewOne(response.insertedId);
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

        var is_property_address_same    = $("#is_property_address_same:checked").val();
        var property_addr1              = !is_property_address_same ? $("#property_addressLine1").val() : addressLine1;
        var property_addr2              = !is_property_address_same ? $("#property_addressLine2").val() : addressLine2;
        var property_addr_city          = !is_property_address_same ? $("#property_city").val() : city;
        var property_addr_country       = !is_property_address_same ? $("#property_country").val() : country;
        var property_addr_state         = !is_property_address_same ? $("#property_state").val() : state;
        var property_addr_pin           = !is_property_address_same ? $("#property_zipCode").val() : zipCode;

        var contactPhoneNumber  = $("#contactPhoneNumber").val();
        var emailId             = $("#emailId").val();
        var claim_number        = $("#claim_number").val();
        var description         = $("#description").val();

        $.ajax({
            method: "POST",
            url: "/claims/claims/update",
            data: {
                customer_name   : customer_name,
                customer_id     : customer_id,
                is_property_address_same    : is_property_address_same,
                property_addr1              : property_addr1,
                property_addr2              : property_addr2,
                property_addr_city          : property_addr_city,
                property_addr_country       : property_addr_country,
                property_addr_state         : property_addr_state,
                property_addr_pin           : property_addr_pin,
                contactPhoneNumber  : contactPhoneNumber,
                emailId             : emailId,
                claim_number        : claim_number,
                description         : description,
                claim_id            : _claims._claim_id
            },
            success: function (response) {
                if(!_utils.is_logged_in( response )) { return false; }
                response = $.parseJSON(response);
                if (response.status.toLowerCase() === "success") {
                    alert(response.message);
                    $("#popupForAll").dialog("close");
                    _claims.viewOne(_claims._claim_id);
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
        _utils.getAndSetCountryStatus(form+"_claim_form", "property");

        _utils.set_accordion('claim_accordion');
        _utils.viewOnlyExpandAll('claim_accordion');
        $("#customer_id").bind({
            change : function( event , form) {
                get_customer_address( form );
            }
        });

        $("#is_property_address_same").bind( {
            click : function( event ) {
                $("#claim_property_address").show();
                if($("#is_property_address_same:checked").val()) {
                    $("#claim_property_address").hide();
                }
            }
        });

        if($("#is_property_address_same:checked").val()) {
            $("#claim_property_address").hide();
        }
    }

    function presetViewOne() {
        $( "#claim_tabs" ).tabs();
        _claim_notes.viewAll(_claims._claim_id);
        _claim_dairy_updates.viewAll(_claims._claim_id);
        _claim_docs.viewAll(_claims._claim_id);
    }

    function get_customer_address( form ) {
        $.ajax({
            method: "POST",
            url: "/claims/claims/customer_address",
            data: {
                customer_id : $("#customer_id").val(),
                claim_id    : _claims._claim_id
            },
            success: function (response) {
                if(!_utils.is_logged_in( response )) { return false; }
                $("#claim_customer_address").html("<div>Communication Address</div>"+response);
                _utils.setCustomerDataList();
                _utils.getAndSetCountryStatus(form+"_claim_form");
                $("#is_property_address_same").prop("disabled", false);
            },
            error: function (error) {
                error = error;
            }
        }).fail(function (failedObj) {
            fail_error = failedObj;
        });
    }

    return {
    	viewAll: function() {
    		var fail_error = null;
            
            $.ajax({
                method: "POST",
                url: "/claims/claims/viewAll",
                data: {},
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#claims_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
    	},
    	viewOne: function( claim_id ) {
            this._claim_id = claim_id;
    		var fail_error = null;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/claims/viewOne",
                data: {
                    claim_id : _claims._claim_id
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#claims_content").html(response);
                    _utils.set_accordion('claim_accordion');
                    presetViewOne();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
    	},
    	createForm: function () {
    		var fail_error = null;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/claims/createForm",
                data: {
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#claims_content").html(response);
                    presetInputForm( "create" );
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
    	},
    	createValidate: function () {
            var cityError = false;
            var isCustomerPresent   = false;
            var customer_id         = $("#customer_id").val();
            var searchCustomerName  = $("#searchCustomerName").val();

            var validator = $("#create_claim_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            }).form();

            isCustomerPresent = _utils.validateCustomerByName(customer_id, searchCustomerName);

            if(validator && (!customer_id || !isCustomerPresent)) {
                validator = false;
                alert("Plesae select proper customer from the search list");
            }

            var is_property_address_same    = $("#is_property_address_same:checked").val();

            var cityValidate =  validator && !is_property_address_same ? _utils.cityFormValidation("property_", "create_claim_form") : false;
            
            validator = validator && cityValidate ? false : validator;

            if (validator) {
               createSubmit();
            }
        },
    	editForm: function( options ) {
    		var fail_error = null;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/claims/editForm",
                data: {
                    claim_id : _claims._claim_id,
                    openAs  : options.openAs
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#popupForAll").html(response);
                    _projects.openDialog({title: "Edit Claim"});
                    presetInputForm("update");
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
    	},
    	updateValidate: function () {
            var cityError = false;
            var isCustomerPresent   = false;
            var customer_id         = $("#customer_id").val();
            var searchCustomerName  = $("#searchCustomerName").val();
            
            var validator = $("#update_claim_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            }).form();

            isCustomerPresent = _utils.validateCustomerByName(customer_id, searchCustomerName);

            if(validator && (!customer_id || !isCustomerPresent)) {
                validator = false;
                alert("Plesae select proper customer from the search list");
            }

            var is_property_address_same    = $("#is_property_address_same:checked").val();

            var cityValidate =  validator && !is_property_address_same ? _utils.cityFormValidation("property_", "update_claim_form") : false;
            
            validator = validator && cityValidate ? false : validator;

            if (validator) {
                updateSubmit();
            }
        },
        deleteRecord: function() {
        	var deleteConfim = confirm("Do you want to delete this claim company");
            if (!deleteConfim) {
                return;
            }
            var fail_error = null;
            $.ajax({
                method: "POST",
                url: "/claims/claims/deleteRecord",
                data: {
                    claim_id: _claims._claim_id
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _claims.viewAll();
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
        }
    };
})();