/**
     Functions Tab related functions
*/
var _functions = (function () {
    //'use strict';
    return {
        errorMessage: function () {
            return {
                functionId: {
                    required:  _lang.english.errorMessage.functionForm.functionId,
                    maxlength:  _lang.english.errorMessage.functionForm.functionId,
                    minlength:  _lang.english.errorMessage.functionForm.functionId,
                    digits:  _lang.english.errorMessage.functionForm.functionId
                },
                functionName: {
                    required: _lang.english.errorMessage.functionForm.functionName
                },
                functionDescr: {
                    required: _lang.english.errorMessage.functionForm.functionDescr
                }
            };
        },

        validationRules: function () {
            return {
                functionId: {
                    required: true,
                    maxlength: 5,
                    minlength: 5,
                    digits: true
                },
                functionName: {
                    required: true
                },
                functionDescr: {
                    required: true
                }
            };
        },

        createValidate: function () {
            var validator = $("#create_function_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                _functions.createSubmit();
            }
        },

        updateValidate: function () {
            var validator = $("#update_function_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                _functions.updateSubmit();
            }
        },

        viewAll: function () {
            $.ajax({
                method: "POST",
                url: "/security/functions/viewAll",
                data: {},
                success: function (response) {
                    $("#security_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createForm: function () {
            $.ajax({
                method: "POST",
                url: "/security/functions/createForm",
                data: {},
                success: function (response) {
                    $("#security_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createSubmit: function () {
            var function_id = $("#functionId").val();
            var function_name = $("#functionName").val();
            var function_desc = $("#functionDescr").val();

            $.ajax({
                method: "POST",
                url: "/security/functions/add",
                data: {
                    function_id: function_id,
                    function_name: function_name,
                    function_desc: function_desc
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _functions.viewOne(response.insertedId);
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

        editFunction: function (function_sno) {
            $.ajax({
                method: "POST",
                url: "/security/functions/editForm",
                data: {function_sno : function_sno},
                success: function (response) {
                    $("#security_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        updateSubmit: function () {
            var function_id = $("#functionId").val();
            var function_name = $("#functionName").val();
            var function_desc = $("#functionDescr").val();
            var function_sno =  $("#function_sno").val();

            $.ajax({
                method: "POST",
                url: "/security/functions/update",
                data: {
                    function_id: function_id,
                    function_name: function_name,
                    function_desc: function_desc,
                    function_sno: function_sno
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status.toLowerCase() === "success") {
                        _functions.viewOne(response.updatedId);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        deleteRecord: function (function_sno) {
            var deleteConfim = confirm("Do you want to delete this function");

            if (!deleteConfim) {
                return;
            }
            $.ajax({
                method: "POST",
                url: "/security/functions/deleteRecord",
                data: {
                    function_sno: function_sno
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status === "success") {
                        _functions.viewAll();
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        viewOne: function (function_sno) {
            $.ajax({
                method: "POST",
                url: "/security/functions/viewOne",
                data: {
                    function_sno: function_sno
                },
                success: function (response) {
                    $("#security_content").html(response);
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