/**
     Operations functions
*/
var _operations = (function () {
    "use strict";
    return {
        errorMessage: function () {
            return {
                operationId: {
                    required:  _lang.english.errorMessage.operationForm.operationId,
                    maxlength:  _lang.english.errorMessage.operationForm.operationId,
                    minlength:  _lang.english.errorMessage.operationForm.operationId,
                    digits:  _lang.english.errorMessage.operationForm.operationId
                },
                operationName: {
                    required: _lang.english.errorMessage.operationForm.operationName
                },
                operationDescr: {
                    required: _lang.english.errorMessage.operationForm.operationDescr
                }
            };
        },
        validationRules: function () {
            return {
                operationId: {
                    required: true,
                   maxlength: 5,
                    minlength: 5,
                    digits: true
                },
                operationName: {
                    required: true
                },
                operationDescr: {
                    required: {
                        depends: function () {
                            if ('' === $('#ope_descr').val()) {
                                $('#ope_descr').val('');
                            }
                            return true;
                        }
                    }
                }
            };
        },
        createValidate: function () {
            var validator = $("#create_operation_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                securityObj._operations.createSubmit();
            }
        },
        updateValidate: function () {
            var validator = $("#update_operation_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                securityObj._operations.updateSubmit();
            }
        },
        viewAll: function () {
            $.ajax({
                method: "POST",
                url: "/security/operations/viewAll",
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
                url: "/security/operations/createForm",
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
            var ope_id = $("#operationId").val();
            var ope_name = $("#operationName").val();
            var ope_desc = $("#operationDescr").val();

            $.ajax({
                method: "POST",
                url: "/security/operations/add",
                data: {
                    ope_id: ope_id,
                    ope_name: ope_name,
                    ope_desc: ope_desc
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        securityObj._operations.viewOne(response.insertedId);
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
        editOperation: function (ope_sno) {
            $.ajax({
                method: "POST",
                url: "/security/operations/editForm",
                data: {ope_sno : ope_sno},
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
            var ope_id = $("#operationId").val();
            var ope_name = $("#operationName").val();
            var ope_desc = $("#operationDescr").val();
            var ope_sno =  $("#ope_sno").val();

            $.ajax({
                method: "POST",
                url: "/security/operations/update",
                data: {
                    ope_id: ope_id,
                    ope_name: ope_name,
                    ope_desc: ope_desc,
                    ope_sno: ope_sno
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status.toLowerCase() === "success") {
                        securityObj._operations.viewOne(response.updatedId);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        deleteRecord: function (ope_sno) {
            $.ajax({
                method: "POST",
                url: "/security/operations/deleteRecord",
                data: {
                    ope_sno: ope_sno
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status === "success") {
                        securityObj._operations.viewAll();
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        viewOne: function (ope_id) {
            $.ajax({
                method: "POST",
                url: "/security/operations/viewOne",
                data: {
                    ope_id: ope_id
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

