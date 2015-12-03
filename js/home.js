var homeObj = (function () {
    'use strict';
    return {
        loginValidate: function() {
            var validator = $("#login_form").validate();

            if (validator.form()) {
                submit.loginSubmit();
            }
        },
        forgotPassValidate: function() {
            var validator = $("#forgotpass_form").validate();

            if (validator.form()) {
                submit.forgotPassSubmit();
            }
        },
        signupValidate: function() {
            $("#signup_user_form").validate({
                rules: {
                    password: {
                        required: true
                    },
                    confirmPassword: {
                        required: true,
                        equalTo: "#password"
                    }
                }
            });

            var validator = $("#signup_user_form").validate();

            if (validator.form()) {
                submit.signupSubmit();
            }
        }
    }
})();


$().ready(function() {
    /*
        Set Module to lode
        1. If Module is already set then load that module,
        2. Else Set the default module based on the current page and lode.

        For Page Security   -> Load Users list
            Page home       -> Load Personal details
            Project         -> Load Issues                        
    */

    var module = session.module != "" ? session.module : (session.page == "security" ? "users" : (session.page == "home" ? "view_my_details" : (session.page == "projects" ? "projects" : ( session.page == "signup" ? "signup" : ""))));

    switch (module) {
        /* Security Page */
        case "users":
            _users.viewAll();
            break;
        case "operations":
            _operations.viewAll();
            break;
        case "roles":
            _roles.viewAll();
            break;
        case "functions":
            _functions.viewAll();
            break;
        case "data_filters":
            _dataFilters.viewAll();
            break;
        case "permissions":
            _permissions.getPageForType();
            _permissions.getComponentInfo();
            break;
            /*Project Page*/
        case "issues":
            _issues.viewAll();
            break;
        case "create_issue":
            _issues.createForm();
            break;
        case "projects":
            _projects.viewAll();
            break;
        case "create_project":
            _projects.createForm();
            break;
        case "contractors":
            _contractors.viewAll();
            break;
        case "create_contractor":
            _contractors.createForm();
            break;
        case "partners":
            _partners.viewAll();
            break;
        case "create_partner":
            _partners.createForm();
            break;
            /*Personal Details*/
        case "view_my_details":
            _userInfo.getUserData();
            break;
        case "change_pass_form":
            _userInfo.changePassForm();
            break;
        case "signup":
            _utils.getAndSetCountryStatus("create_user_form");
        break;
    }

    $("#login_error").hide();
    $("#forgotpass_error").hide();
    $("#forgotpass_success").hide();
});