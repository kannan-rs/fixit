/**
     Roles functions
*/
var _roles = (function () {
    "use strict";
    return {
        errorMessage: function () {
            return {
                roleId: {
                    required:  _lang.english.errorMessage.roleForm.roleId,
                    maxlength:  _lang.english.errorMessage.operationForm.roleId,
                    minlength:  _lang.english.errorMessage.operationForm.roleId,
                    digits:  _lang.english.errorMessage.operationForm.roleId
                },
                roleName: {
                    required: _lang.english.errorMessage.roleForm.roleName
                },
                roleDescr: {
                    required: _lang.english.errorMessage.roleForm.roleDescr
                }
            };
        },
        validationRules: function () {
            return {
                roleId: {
                    required: true,
                    maxlength: 5,
                    minlength: 5,
                    digits: true
                },
                roleName: {
                    required: true
                },
                roleDescr: {
                    required: true
                }
            };
        },
        createValidate: function () {
            var validator = $("#create_role_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                _roles.createSubmit();
            }
        },

        updateValidate: function () {
            var validator = $("#update_role_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                _roles.updateSubmit();
            }
        },

        viewAll: function () {
            $.ajax({
                method: "POST",
                url: "/security/roles/viewAll",
                data: {},
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
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
                url: "/security/roles/createForm",
                data: {},
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
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
            var role_id = $("#roleId").val();
            var role_name = $("#roleName").val();
            var role_desc = $("#roleDescr").val();

            $.ajax({
                method: "POST",
                url: "/security/roles/add",
                data: {
                    role_id: role_id,
                    role_name: role_name,
                    role_desc: role_desc
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status === "success") {
                        _roles.viewOne(response.insertedId);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        editRole: function (role_sno) {
            $.ajax({
                method: "POST",
                url: "/security/roles/editForm",
                data: {role_sno : role_sno},
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
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
            var role_id = $("#roleId").val();
            var role_name = $("#roleName").val();
            var role_desc = $("#roleDescr").val();
            var role_sno =  $("#role_sno").val();

            $.ajax({
                method: "POST",
                url: "/security/roles/update",
                data: {
                    role_id: role_id,
                    role_name: role_name,
                    role_desc: role_desc,
                    role_sno: role_sno
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status === "success") {
                        _roles.viewOne(response.updatedId);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        deleteRecord: function (role_sno) {
            var deleteConfim = confirm("Do you want to delete this role");

            if (!deleteConfim) {
                return;
            }
            
            $.ajax({
                method: "POST",
                url: "/security/roles/deleteRecord",
                data: {
                    role_sno: role_sno
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    //$("#security_content").html(response);
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status === "success") {
                        _roles.viewAll();
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        viewOne: function (role_sno) {
            $.ajax({
                method: "POST",
                url: "/security/roles/viewOne",
                data: {
                    role_sno: role_sno
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
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