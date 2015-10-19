function home() {
    this._userInfo = new userInfo();
}

home.prototype.loginValidate = function() {
    var validator = $("#login_form").validate();

    if (validator.form()) {
        submit.loginSubmit();
    }
}

home.prototype.signupValidate = function() {
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


$().ready(function() {
    homeObj = new home();
    utilObj = new utils();
    projectObj = new projects();
    securityObj = new security();
    homeObj = new home();

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
            securityObj._users.viewAll();
            break;
        case "operations":
            securityObj._operations.viewAll();
            break;
        case "roles":
            securityObj._roles.viewAll();
            break;
        case "functions":
            securityObj._functions.viewAll();
            break;
        case "data_filters":
            securityObj._dataFilters.viewAll();
            break;
        case "permissions":
            securityObj._permissions.viewAll();
            break;
            /*Project Page*/
        case "issues":
            projectObj._issues.viewAll();
            break;
        case "create_issue":
            projectObj._issues.createForm();
            break;
        case "projects":
            projectObj._projects.viewAll();
            break;
        case "create_project":
            projectObj._projects.createForm();
            break;
        case "contractors":
            projectObj._contractors.viewAll();
            break;
        case "create_contractor":
            projectObj._contractors.createForm();
            break;
        case "partners":
            projectObj._partners.viewAll();
            break;
        case "create_partner":
            projectObj._partners.createForm();
            break;
            /*Personal Details*/
        case "view_my_details":
            homeObj._userInfo.getUserData();
            break;
        case "change_pass_form":
            homeObj._userInfo.changePassForm();
            break;
        case "signup":
            utilObj.getAndSetCountryStatus("create_user_form");
            utilObj.getPostalCodeList("create_user_form");
        break;
    }

    $("#login_error").hide();
});