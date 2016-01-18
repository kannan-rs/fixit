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
            url: "/claims/claims/add",
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
                description : description
            },
            success: function (response) {
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
        var contactPhoneNumber  = $("#contactPhoneNumber").val();
        var emailId             = $("#emailId").val();
        var claim_number        = $("#claim_number").val();
        var description         = $("#description").val();

        $.ajax({
            method: "POST",
            url: "/claims/claims/update",
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
        _utils.getAndSetCountryStatus(form+"_claim_form");
    }

    function presetViewOne() {
        $( "#claim_tabs" ).tabs();
        _claim_notes.viewAll(_claims._claim_id);
        _claim_dairy_updates.viewAll(_claims._claim_id);
        _claim_docs.viewAll(_claims._claim_id);
    }

    return {
    	viewAll: function() {
    		var fail_error = null;
            
            $.ajax({
                method: "POST",
                url: "/claims/claims/viewAll",
                data: {},
                success: function (response) {
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
                    $("#claims_content").html(response);
                    $("#accordion").accordion(
                        {
                            collapsible: true,
                            icons: {header: "ui-icon-plus", activeHeader: "ui-icon-minus"},
                            active: 0
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

            validator = validator && _utils.cityFormValidation() ? false : validator;

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

            cityError = _utils.cityFormValidation();
            if (cityError) {
                return false;
            }

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
        },
        /*claimNotesCreateForm : function(event, options) {
            var fail_error  = null;
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            var openAs = (options && options.openAs) ? options.openAs: "";
            var popupType = (options && options.popupType) ? options.popupType: "";

            $.ajax({
                method: "POST",
                url: "/claims/claims/notesCreateForm",
                data: {
                    openAs      : openAs,
                    popupType   : popupType,
                    claim_id    : _claims._claim_id
                },
                success: function (response) {
                    if (openAs === "popup") {
                        $("#popupForAll" + popupType).html(response);
                        _projects.openDialog({title: "Add Claim Notes"}, popupType);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },*/
        /*claimDairyUpdateCreateForm : function(event, options) {
            var fail_error  = null;
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            var openAs = (options && options.openAs) ? options.openAs: "";
            var popupType = (options && options.popupType) ? options.popupType: "";

            $.ajax({
                method: "POST",
                url: "/claims/claims/getListWithForm",
                data: {
                    openAs      : openAs,
                    popupType   : popupType,
                    claim_id    : _claims._claim_id
                },
                success: function (response) {
                    if (openAs === "popup") {
                        $("#popupForAll" + popupType).html(response);
                        _claims.openDialog({title: "Paid From Budget"}, popupType);
                        _utils.setAsDateFields({dateField: "date"});
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },*/
        /*claimDocsCreateForm : function(event, options) {
            var fail_error  = null;
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            var openAs = (options && options.openAs) ? options.openAs: "";
            var popupType = (options && options.popupType) ? options.popupType: "";

            $.ajax({
                method: "POST",
                url: "/claims/claims/getListWithForm",
                data: {
                    openAs      : openAs,
                    popupType   : popupType,
                    claim_id    : _claims._claim_id
                },
                success: function (response) {
                    if (openAs === "popup") {
                        $("#popupForAll" + popupType).html(response);
                        _claims.openDialog({title: "Paid From Budget"}, popupType);
                        _utils.setAsDateFields({dateField: "date"});
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },*/
    };
})();