var homeObj = (function () {
    //'use strict';
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
        },

        loadPage : function( menus ) {
            var pageModuleMap = {
                "security"              : "users",
                "home"                  : "view_my_details",
                "projects"              : "projects",
                "signup"                : "signup",
                "service_providers"     : "service_providers",
                "adjusters"             : "adjusters",
                "claims"                : "claims"
            }

            var module = _utils.get_current_page();
            var logged_in_user_details = _utils.get_logged_in_user_details();

            for(var i = 0; i < menus.length; i++) {
                if( module == menus[i].link.split("/").reverse()[0]) {
                    if( menus[i].sub_menus && menus[i].sub_menus.length) {
                        module = menus[i].sub_menus[0].link.split("/").reverse()[0];
                        break;
                    }
                }
            }

            switch (module) {
                /* Security Page */
                case "users":
                    _users.viewAll();
                break;
                case "create_user":
                    _users.createForm();
                break;
                case "operations":
                    _operations.viewAll();
                break;
                case "create_operation":
                    _operations.createForm()
                break;
                case "roles":
                    _roles.viewAll();
                break;
                case "create_role":
                    _roles.createForm()
                break;
                case "functions":
                    _functions.viewAll();
                break;
                case "create_function":
                    _functions.createForm()
                break;
                case "data_filters":
                    _dataFilters.viewAll();
                break;
                case "create_data_filter":
                    _dataFilters.createForm();
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
                case "service_providers":
                    _contractors.viewAll();
                break;
                case "create_contractor":
                    _contractors.createForm();
                break;
                case "adjusters":
                    _partners.viewAll();
                break;
                case "create_adjuster":
                case "create_partner":
                    _partners.createForm();
                break;
                case "claims":
                    _claims.viewAll();
                break;
                case "create_claim":
                    _claims.createForm();
                break;
                    /*Personal Details*/
                case "home":
                case "view_my_details":
                    _userInfo.getUserData();
                break;
                case "change_pass_form":
                    _userInfo.changePassForm();
                break;
                case "signup":
                    _utils.getAndSetCountryStatus("create_user_form");
                break;
                case "insurance_company":
                    _ins_comp.viewAll();
                break;
                case "create_insurance_company":
                    _ins_comp.createForm();
                break;
            }
        },

        loginFormKeyPress : function(event) {
            if(event.keyCode == 13 || event.charCode == 13) {
                homeObj.loginValidate();
            };
        }
    }
})();


$(document).ready(function() {
    /*
        Set Module to lode
        1. If Module is already set then load that module,
        2. Else Set the default module based on the current page and lode.

        For Page Security   -> Load Users list
            Page home       -> Load Personal details
            Project         -> Load Issues                        
    */
    $('ul.nav li.dropdown').on("mouseover", function () {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn();
        //$(this).find('.sub-menu-level1').css("top", $(this).position().top+"px");
        //css("top", $(this).position().top)
    });
    $('ul.nav li.dropdown').on("mouseout", function () {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut();
        //$(this).find('.sub-menu-level1').css("top", $(this).position().top);
    });  
    
    $("#login_error").hide();
    $("#forgotpass_error").hide();
    $("#forgotpass_success").hide();

    /*var jssor_1_options = {
        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$
        },
        $ThumbnailNavigatorOptions: {
            $Class: $JssorThumbnailNavigator$,
            $Cols: 15,
            $SpacingX: 3,
            $SpacingY: 3,
            $Align: 455
        }
    };*/

    //var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
            
    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizing
    /*function ScaleSlider() {
        var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
        if (refSize) {
            refSize = Math.min(refSize, 980);
            jssor_1_slider.$ScaleWidth(refSize);
        }
        else {
            window.setTimeout(ScaleSlider, 30);
        }
    }
    ScaleSlider();
    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);*/
});