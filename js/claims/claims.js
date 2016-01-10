/**
    Projects functions
*/
var _claims = (function () {
    "use strict";

    function errorMessage() {

    };

    function validationRules() {

    };

    function createSubmit() {

    };

    function updateSubmit() {

    };

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
    	viewOne: function() {
    		var fail_error = null;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/claims/viewOne",
                data: {
                },
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
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
    	},
    	createValidate: function (openAs, popupType) {
            var cityError = false;
            var validator = $("#create_claim_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            }).form();

            cityError = _utils.cityFormValidation();
            if (cityError) {
                return false;
            }

            if (validator) {
                createSubmit(openAs, popupType);
            }
        },
    	editForm: function() {
    		var fail_error = null;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/claims/editForm",
                data: {
                },
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
    	updateValidate: function () {
            var cityError = false;
            var validator = $("#update_claim_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

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
                    claimId: _claims.claimId
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
        }
    };
})();