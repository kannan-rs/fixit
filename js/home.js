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
            //return;
            var pageModuleMap = {
                "security"              : "users",
                "home"                  : "view_my_details",
                "projects"              : "projects",
                "signup"                : "signup",
                "service_providers"     : "service_providers",
                "adjusters"             : "adjusters",
                "claims"                : "claims"
            }

            this.page                    = _utils.get_current_page_from_url();
            var logged_in_user_details  = _utils.get_logged_in_user_details();

            for(var i = 0; i < menus.length; i++) {
                if( page == menus[i].link.split("/").reverse()[0]) {
                    if( menus[i].sub_menus && menus[i].sub_menus.length) {
                        page = menus[i].sub_menus[0].link.split("/").reverse()[0];
                        break;
                    }
                }
            }

            this.internal_page  = _utils.get_internal_page_from_url( page );
            this.operation      = _utils.get_operation_from_url( page, internal_page );
            this.record         = _utils.get_record_from_url( page, internal_page, operation );

            /*console.log("page -->" + this.page);
            console.log("internal_page -->" + this.internal_page);
            console.log("Operation -->" + this.operation);
            console.log("Record -->" + this.Record);*/

            switch (this.page) {
                case "signup":
                    _utils.getAndSetCountryStatus("create_user_form");
                break;
                
                case "insurance_company":
                    _ins_comp.viewAll();
                break;
                
                case "create_insurance_company":
                    _ins_comp.createForm();
                break;
                
                case "home_page":
                    _home_content.showHomePageContent();
                break;

                case "users":
                    switch (this.internal_page) {
                        case "create_user":
                            _users.createForm();
                        break;
                        default:
                            _users.viewAll();
                        break;
                    }
                break;

                case "operations":
                    switch (this.internal_page) {
                        case "create_operation":
                            _operations.createForm();
                        break;
                        default:
                            _operations.viewAll();
                        break;
                    }
                break;

                case "roles":
                    switch (this.internal_page) {
                        case "create_role":
                            _roles.createForm();
                        break;
                        default:
                            _roles.viewAll();
                        break;
                    }
                break;

                case "functions":
                    switch (this.internal_page) {
                        case "create_function":
                            _functions.createForm();
                        break;
                        default:
                            _functions.viewAll();
                        break;
                    }
                break;
                
                case "data_filters":
                    switch (this.internal_page) {
                        case "create_data_filter":
                            _dataFilters.createForm();
                        break;
                        default:
                            _dataFilters.viewAll();
                        break;
                    }
                break;
                
                case "permissions":
                    _permissions.getPageForType();
                    _permissions.getComponentInfo();
                break;
                
                case "issues":
                    switch (this.internal_page) {
                        case "create_issue":
                            _issues.createForm();
                        break;
                        default:
                            _issues.viewAll();
                        break;
                    }
                break;
                
                case "projects":
                    switch (this.internal_page) {
                        case "create_project":
                            _projects.createForm();
                        break;
                        case "projects":
                        default:
                            switch (this.operation) {
                                case "viewone":
                                    console.log("in viewone")
                                    _projects.viewOne( this.record );
                                break;
                                case "viewall":
                                default:
                                    _projects.viewAll();
                                break;
                            }
                        break;
                    }
                break;
                
                case "service_providers":
                    switch (this.internal_page) {
                        case "create_contractor":
                            _contractors.createForm();
                        break;
                        case "trades":
                            _contractor_trades.showTradeList();
                        break;
                        default:
                            switch (this.operation) {
                                case "viewone":
                                    _contractors.viewOne( this.record );
                                break;
                                case "viewall":
                                default:
                                    _contractors.viewAll();
                                break;
                                 //onclick="('<?php echo $contractors[$i]->id; ?>')"
                            }
                            
                        break;
                    }
                break;
               
                case "adjusters":
                    switch (this.internal_page) {
                        case "create_adjuster":
                        case "create_partner":
                           _partners.createForm();
                        break;
                        default:
                            _partners.viewAll();
                        break;
                    }
                break;
                
                case "claims":
                    switch (this.internal_page) {
                        case "create_claim":
                            _claims.createForm();
                        break;
                        default:
                            _claims.viewAll();
                        break;
                    }
                break;
                
                /*Personal Details*/
                case "home":
                case "view_my_details":
                     switch (this.internal_page) {
                        case "change_pass_form":
                            _userInfo.changePassForm();
                        break;
                        default:
                            switch(this.operation) {
                                case "edit":
                                    _users.editUser( this.record );
                                break;
                                case "view":
                                default:
                                    _userInfo.getUserData( this.record );
                                break;
                            }
                        break;
                    }
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
    if($('#slider1')) {
        $('#slider1').bxSlider({
          mode: 'fade',
          auto: true,
          pause: 5000,
          pager : false,
          controls: false,
          autoControls: false
        });
    }

    if($('.slider5')) {
        $('.slider5').bxSlider({
            slideWidth: 300,
            minSlides: 4,
            maxSlides: 4,
            moveSlides: 4,
            slideMargin: 10
        });
    }
});