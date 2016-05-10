/**
    Users functions
*/
var _users = (function () {
    "use strict";

    var rolesDb = null;
    //var rolesBySno = [];
    
    function initialSettings() {
        cleanInputForm();
    };

    function cleanInputForm () {
        $(".contractor-search").hide();
        $(".contractor-result").hide();
        $("#contractorList").hide();
        $("#selectedContractorDb").hide();

        $(".adjuster-search").hide();
        $(".adjuster-result").hide();
        $("#adjusterList").hide();
        $("#selectedAdjusterDB").hide();

        $(".ins_comp-search").hide();
        $(".ins_comp-result").hide();
        $("#ins_compList").hide();
        $("#selectedInsCompDB").hide();

       /* if($("#selectedContractorDb").text() != "") {
            $("#selectedContractorDb").show();
        }

        if($("#selectedAdjusterDB").text() != "") {
            $("#selectedAdjusterDB").show();
        }*/
    }

    /*function setViewBasics() {    
        $(".role_id").each(
            function( index, element ){
               var roleId = $(this).text();
               var roleText = rolesBySno[roleId] ? rolesBySno[roleId].role_name : "Customer";
               $(this).text(roleText);
            }
        );
    }*/

    /*function getRole() {
        var responseArr = null;
        $.ajax({
            method: "POST",
            url: "/security/roles/getRoleList",
            async : false,
            data: {},
            success: function( response ) {
                if(!_utils.is_logged_in( response )) { return false; }
                responseArr = JSON.parse(response);
                if(responseArr.status == "success") {
                    rolesDb = responseArr.roles;
                    mapRoleToId();
                } else {
                    alert(responseArr.message);
                }
            },
            error: function( error ) {
                error = error;
            }
        })
        .fail(function ( failedObj ) {
            fail_error = failedObj;
        });
    };*/

    /*function mapRoleToId() {
        var i;
        for (i = 0; i < rolesDb.length; i++) {
            rolesBySno[rolesDb[i].sno] = rolesDb[i];
        }
    };*/

    return {
        validationRules: function() {
            return {
                password: {
                    required: true
                },
                confirmPassword: {
                    required: true,
                    equalTo: "#password"
                },
                zipCode : {
                    required: true,
                    //minlength: 5,
                    //maxlength: 5,
                    digits : true
                },
                privilege: {
                    required: {
                        depends: function(element) {
                            if('' == $('#privilege').val()){
                                $('#privilege').val('');
                            }
                            return true;
                        }
                    }
                },
                contactPhoneNumber : {
                    digits : true
                },
                mobileNumber : {
                    digits : true
                },
                altNumber : {
                    digits : true
                }
            };
        },
        errorMessage: function() {
            return {
                firstName : {
                    required : _lang.english.errorMessage.signupForm.firstName
                },
                lastName : {
                    required : _lang.english.errorMessage.signupForm.lastName
                },
                password : {
                    required : _lang.english.errorMessage.signupForm.password
                },
                confirmPassword : {
                    required : _lang.english.errorMessage.signupForm.confirmPassword,
                    equalTo : _lang.english.errorMessage.signupForm.confirmPassword
                },
                passwordHint : {
                    required : _lang.english.errorMessage.signupForm.passwordHint
                },
                belongsTo : {
                    required : _lang.english.errorMessage.signupForm.belongsTo
                },
                contractorZipCode : {
                    required : _lang.english.errorMessage.signupForm.contractorZipCode
                },
                partnerCompanyName : {
                    required : _lang.english.errorMessage.signupForm.partnerCompanyName
                },
                userStatus : {
                    required : _lang.english.errorMessage.signupForm.userStatus
                },
                activeStartDate : {
                    required : _lang.english.errorMessage.signupForm.activeStartDate
                },
                activeEndDate : {
                    required : _lang.english.errorMessage.signupForm.activeEndDate
                },
                emailId : {
                    required : _lang.english.errorMessage.signupForm.emailId
                },
                contactPhoneNumber : {
                    required : _lang.english.errorMessage.signupForm.contactPhoneNumber
                },
                mobileNumber : {
                    required : _lang.english.errorMessage.signupForm.mobileNumber
                },
                primaryMobileNumber : {
                    required : _lang.english.errorMessage.signupForm.primaryMobileNumber
                },
                altNumber : {
                    required : _lang.english.errorMessage.signupForm.altNumber
                },
                addressLine1 : {
                    required : _lang.english.errorMessage.signupForm.addressLine1
                },
                addressLine2 : {
                    required : _lang.english.errorMessage.signupForm.addressLine2
                },
                city : {
                    required : _lang.english.errorMessage.signupForm.city
                },
                country : {
                    required : _lang.english.errorMessage.signupForm.country
                },
                state : {
                    required : _lang.english.errorMessage.signupForm.state
                },
                zipCode : {
                    required : _lang.english.errorMessage.signupForm.zipCode,
                    minlength : _lang.english.errorMessage.signupForm.zipCode,
                    maxlength : _lang.english.errorMessage.signupForm.zipCode,
                    digits : _lang.english.errorMessage.signupForm.zipCode
                },
                prefContactEmailId : {
                    required : _lang.english.errorMessage.signupForm.prefContactEmailId
                },
                prefContactContactPhoneNumber : {
                    required : _lang.english.errorMessage.signupForm.prefContactContactPhoneNumber
                },
                prefContactMobileNumber : {
                    required : _lang.english.errorMessage.signupForm.prefContactMobileNumber
                },
                prefContactAltNumber : {
                    required : _lang.english.errorMessage.signupForm.prefContactAltNumber
                },
                referredBy : {
                    required : _lang.english.errorMessage.signupForm.referredBy
                },
                referredBycontractorZipCode : {
                    required : _lang.english.errorMessage.signupForm.referredBycontractorZipCode
                },
                referredBypartnerCompanyName : {
                    required : _lang.english.errorMessage.signupForm.referredBypartnerCompanyName
                },
                privilege: {
                    required: "Please select any privilege"
                }
            };
        },
        createValidate: function ( openAs, popupType, belongsTo ) {
            var isTcError = false;
            var cityError = false;
            var validator = $("#create_user_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            if(!logged_in_user_details.role_id && !$("#termsCondition").prop("checked")) {
                $("#tcError").show();
                isTcError = true;
            }

            cityError = _utils.cityFormValidation();

            var dateOptions = {
                date_1      : "activeStartDate",
                date_2      : "activeEndDate",
                operation   : "<=",
                idPrefix    : "#update_user_form "
            }

            var startDatePresent = $("#activeStartDate").val() || "";
            var endDatePresent = $("#activeStartDate").val() || "";

            var dateRangeCheck = true;
            if( startDatePresent && endDatePresent ) {
                dateRangeCheck = _utils.dateCompare(dateOptions);
            }

            if(isTcError || cityError || !dateRangeCheck) {
                return false;
            }

            if( validator ) {
                _users.createSubmit( openAs, popupType, belongsTo );
            }
        },
        updateValidate: function() {
            var cityError = false;
            var validator = $("#update_user_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation();

            var dateOptions = {
                date_1      : "activeStartDate",
                date_2      : "activeEndDate",
                operation   : "<=",
                idPrefix    : "#update_user_form "
            }

            var startDatePresent = $("#activeStartDate").val() || "";
            var endDatePresent = $("#activeStartDate").val() || "";

            var dateRangeCheck = true;
            if( startDatePresent && endDatePresent ) {
                dateRangeCheck = _utils.dateCompare(dateOptions);
            }

            if(cityError || !dateRangeCheck) {
                return false;
            }

            if(validator) {
                _users.updateSubmit();
            }
        },
        viewAll: function( options ) {
            options            = options || {};
            var userId         = options.userId;
            var status         = options.status;
            var responseType   = options.responseType;

            $.ajax({
                method: "POST",
                url: "/security/users/viewAll",
                data: {
                    userId             : userId,
                    status             : status,
                    responseType     : responseType
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#security_content").html(response);
                    //setViewBasics();
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        createForm: function( options ) {
            var openAs         = options && options.openAs ? options.openAs : "";
            var popupType      = options && options.popupType ? options.popupType : "";
            var belongsTo      = options && options.belongsTo ? options.belongsTo : "";
            var requestFrom    = options && options.requestFrom ? options.requestFrom : "";

            $.ajax({
                method: "POST",
                url: "/security/users/createForm",
                data: {
                    openAs          : openAs,
                    belongsTo       : belongsTo,
                    popupType       : popupType,
                    requestFrom     : requestFrom
                },
                success: function( response ) {
                    //if(!_utils.is_logged_in( response )) { return false; }
                    if(openAs == "") {
                        $("#security_content").html(response);
                    } else {
                        $("#popupForAll"+popupType).html( response );
                        if(belongsTo == "adjuster") {
                            _projects.openDialog({"title" : "Add Adjuster"}, popupType);
                        } else if(belongsTo == "customer") {
                            _projects.openDialog({"title" : "Add Customer"}, popupType);
                        }
                    }

                    initialSettings();
                    
                    _utils.setStatus("userStatus", "userStatusDb");
                    _users.showreferredByOption();
                    _utils.getAndSetCountryStatus("create_user_form");
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        createSubmit: function( openAs, popupType, belongsToForPopup ) {
            var idPrefix            = "#create_user_form ";
            var privilege           = $(idPrefix+"#privilege").val();
            var role_text           = $("#privilege option:selected").text();
            var firstName           = $(idPrefix+"#firstName").val();
            var lastName            = $(idPrefix+"#lastName").val();
            var password            = $(idPrefix+"#password").val();
            var passwordHint        = $(idPrefix+"#passwordHint").val();
            var belongsTo           = $(idPrefix+"#belongsTo").val();
            var referredBy          = $(idPrefix+"#referredBy").val();
            var belongsToId         = "";
            var referredById        = "";
            var userStatus          = $(idPrefix+"#userStatus").val();
            var emailId             = $(idPrefix+"#emailId").val();
            var contactPhoneNumber  = $(idPrefix+"#contactPhoneNumber").val();
            var mobileNumber        = $(idPrefix+"#mobileNumber").val();
            var altNumber           = $(idPrefix+"#altNumber").val();
            var primaryContact      = $(idPrefix+"input[name=primaryContact]:checked").val();
            var prefContact         = "";
            var addressLine1        = $(idPrefix+"#addressLine1").val();
            var addressLine2        = $(idPrefix+"#addressLine2").val();
            var city                = $(idPrefix+"#city").val();
            var state               = $(idPrefix+"#state").val();
            var country             = $(idPrefix+"#country").val();
            var zipCode             = $(idPrefix+"#zipCode").val();
            var tc                  = $(idPrefix+"#termsCondition").prop("checked");

            $(idPrefix+"input[name=prefContact]:checked").each(
                function() {
                    prefContact += prefContact != "" ? (","+this.value) : this.value;
                }
            );

            /*
                Belongs to options
            */
            var ownerSelected       = $(idPrefix+"input[type='radio'][name='optionSelectedContractor']:checked");
            var adjusterSelected    = $(idPrefix+"input[type='radio'][name='optionSelectedAdjuster']:checked");
            var ins_comp_selected   = $(idPrefix+"input[type='radio'][name='optionSelectedInsComp']:checked");

            if (role_text.toLowerCase().indexOf("service provider") >= 0 && ownerSelected.length > 0) {
                belongsToId     = ownerSelected.val();
            } else if(role_text.toLowerCase().indexOf("partner") >= 0 && adjusterSelected.length > 0) {
                belongsToId     = adjusterSelected.val();
            } else if(role_text.toLowerCase().indexOf("insuranceco") >= 0 && ins_comp_selected.length > 0) {
                belongsToId     = ins_comp_selected.val();
            }

            /*
                Referred to options
            */
            var referredByownerSelected = $(idPrefix+"input[type='radio'][name='referredByoptionSelectedOwner']:checked");
            var referredByadjusterSelected = $(idPrefix+"input[type='radio'][name='referredByoptionSelectedAdjuster']:checked");

            if (referredBy == "contractor" && referredByownerSelected.length > 0) {
                referredById     = referredByownerSelected.val();
            } else if(referredBy == "adjuster" && referredByadjusterSelected.length > 0) {
                referredById     = referredByadjusterSelected.val();
            }


            $.ajax({
                method: "POST",
                url: "/security/users/add",
                data: {
                    privilege           : privilege,
                    firstName           : firstName,
                    lastName            : lastName,
                    password            : password,
                    passwordHint        : passwordHint,
                    belongsTo           : belongsTo,
                    referredBy          : referredBy,
                    belongsToId         : belongsToId,
                    referredById        : referredById,
                    userStatus          : userStatus,
                    emailId             : emailId,
                    contactPhoneNumber  : contactPhoneNumber,
                    mobileNumber        : mobileNumber,
                    altNumber           : altNumber,
                    primaryContact      : primaryContact,
                    prefContact         : prefContact,
                    addressLine1        : addressLine1,
                    addressLine2        : addressLine2,
                    city                : city,
                    state               : state,
                    country             : country,
                    zipCode             : zipCode,
                    tc                  : tc
                },
                success: function( response ) {
                    //if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        //alert(response.message);
                        if(logged_in_user_details.is_logged_in && _utils.get_current_module() != "signup") {
                            var params = {
                                userId                 : response.insertedId,
                                openAs                 : openAs,
                                popupType             : popupType,
                                belongsTo             : belongsToForPopup,
                                status                 : "success",
                                responseType         : "add"
                            }
                            _users.viewOne( params );
                        } else if (_utils.get_current_module() == "signup") {
                            $(".content").html( response["createConfirmPage"] );
                        }
                    } else if(response.status.toLowerCase() == "error") {
                        alert(response.message);
                    }
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        editUser: function(userId) {
            var dateOptions;
            $.ajax({
                method: "POST",
                url: "/security/users/editForm",
                data: {'userId' : userId},
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }

                    if(_utils.get_current_module() == "home") {
                        $("#index_content").html(response);
                    } else {
                        $("#security_content").html(response);
                    }

                    initialSettings();
                    _users.setPrivilege();
                    _users.showBelongsToOption();

                    _users.setPrimaryContact(); // To set primary contact selection
                    _users.setPrivilege();
                    _utils.setStatus("userStatus", "userStatusDb");
                    _users.setreferredBy();

                    _users.showreferredByOption();
                    _utils.getAndSetCountryStatus("update_user_form");;
                    _utils.setAddressByCity();
                    _utils.getAndSetMatchCity($("#city_jqDD").val(), "edit",'');

                    _utils.setAsDateFields({dateField: "activeStartDate"});
                    _utils.setAsDateFields({dateField: "activeEndDate"});
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        updateSubmit: function(userId) {
            var idPrefix            = "#update_user_form ";
            var userId              = $(idPrefix+"#userId").val();
            var privilege           = $(idPrefix+"#privilege").length ? $("#privilege").val() : "";
            var role_text           = privilege ? $("#privilege option:selected").text() : "";
            var sno                 = $(idPrefix+"#user_details_sno").val();
            var firstName           = $(idPrefix+"#firstName").val();
            var lastName            = $(idPrefix+"#lastName").val();
            var belongsTo           = $(idPrefix+"#belongsTo").val();
            var activeStartDate     = $(idPrefix+"#activeStartDate").length ? _utils.toMySqlDateFormat($("#activeStartDate").val()) : "";
            var activeEndDate       = $(idPrefix+"#activeEndDate").length ? _utils.toMySqlDateFormat($("#activeEndDate").val()) : "";
            var belongsToId         = "";
            var userStatus          = $(idPrefix+"#userStatus").val();
            var contactPhoneNumber  = $(idPrefix+"#contactPhoneNumber").val();
            var mobileNumber        = $(idPrefix+"#mobileNumber").val();
            var altNumber           = $(idPrefix+"#altNumber").val();
            var primaryContact      = $(idPrefix+"input[name=primaryContact]:checked").val();
            var prefContact         = "";
            var addressLine1        = $(idPrefix+"#addressLine1").val();
            var addressLine2        = $(idPrefix+"#addressLine2").val();
            var city                = $(idPrefix+"#city").val();
            var state               = $(idPrefix+"#state").val();
            var country             = $(idPrefix+"#country").val();
            var zipCode             = $(idPrefix+"#zipCode").val();
            var referredBy          = $(idPrefix+"#referredBy").val();
            var referredById        = "";

            var ownerSelected       = $(idPrefix+"input[type='radio'][name='optionSelectedContractor']:checked");
            var adjusterSelected    = $(idPrefix+"input[type='radio'][name='optionSelectedAdjuster']:checked");
            var ins_comp_selected   = $(idPrefix+"input[type='radio'][name='optionSelectedInsComp']:checked");

            if (role_text.toLowerCase().indexOf("service provider") >= 0 ) {
                belongsToId = ownerSelected.length > 0 ? ownerSelected.val() : "";
                belongsToId = belongsToId != "" ? belongsToId : $("#belongsToIdDb").val();
            } else if(role_text.toLowerCase().indexOf("partner") >= 0 ) {
                belongsToId     = adjusterSelected.length > 0 ? adjusterSelected.val() : "";
                belongsToId = belongsToId != "" ? belongsToId : $("#belongsToIdDb").val();
            } else if(role_text.toLowerCase().indexOf("insuranceco") >= 0 && ins_comp_selected.length > 0) {
                belongsToId     = ins_comp_selected.val();
            }

            var referredByOwnerSelected = $(idPrefix+"input[type='radio'][name='referredByoptionSelectedOwner']:checked");
            var referredByAdjusterSelected = $(idPrefix+"input[type='radio'][name='referredByoptionSelectedAdjuster']:checked");

            if (referredBy == "contractor" && referredByOwnerSelected.length > 0) {
                referredById     = referredByOwnerSelected.val();
            } else if(referredBy == "adjuster" && referredByAdjusterSelected.length > 0) {
                referredById     = referredByAdjusterSelected.val();
            }

            if(referredBy == "contractor" || referredBy == "adjuster") {
                referredById = referredById != "" ? referredById : $("#referredByIdDb").val();
            }


            $(idPrefix+"input[name=prefContact]:checked").each(
                function() {
                    prefContact += prefContact != "" ? (","+this.value) : this.value;
                }
            );

            $.ajax({
                method: "POST",
                url: "/security/users/update",
                data: {
                    userId             : userId,
                    privilege          : privilege,
                    sno                : sno,
                    firstName          : firstName,
                    lastName           : lastName,
                    belongsTo          : belongsTo,
                    belongsToId        : belongsToId,
                    activeStartDate    : activeStartDate,
                    activeEndDate      : activeEndDate,
                    userStatus         : userStatus,
                    contactPhoneNumber : contactPhoneNumber,
                    mobileNumber       : mobileNumber,
                    altNumber          : altNumber,
                    primaryContact     : primaryContact,
                    prefContact        : prefContact,
                    addressLine1       : addressLine1,
                    addressLine2       : addressLine2,
                    city               : city,
                    state              : state,
                    country            : country,
                    zipCode            : zipCode,
                    referredBy         : referredBy,
                    referredById       : referredById
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        var params = {
                            userId                 : userId,
                            status                 : "success",
                            responseType         : "update"
                        }
                        _users.viewOne( params );
                    } else {
                        alert(response["message"]);
                    }
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        deleteRecord: function(userId, emailId) {
            var deleteConfim = confirm("Do you want to delete this user");

            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/security/users/deleteRecord",
                data: {
                    userId: userId,
                    emailId : emailId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response["status"] == "success") {
                        var params = {
                            userId                 : userId,
                            status                 : "success",
                            responseType         : "delete"
                        }
                        _users.viewAll( params );
                    } else {
                        alert(response["message"]);
                    }
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        viewOne: function( options ) {
            options             = options || {};
            var userId             = options.userId;
            var openAs             = options.openAs;
            var popupType         = options.popupType;
            var belongsTo         = options.belongsTo;
            var status             = options.status;
            var responseType     = options.responseType;

            $.ajax({
                method: "POST",
                url: "/security/users/viewOne",
                data: {
                    userId             : userId,
                    viewFrom         : _utils.get_current_module(),
                    status             : status,
                    responseType     : responseType
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    if( openAs && openAs == "popup") {
                        $("#popupForAll"+popupType).html( response );
                        var title = "";
                        title = belongsTo == "adjuster" ? "Adjuster" : title;
                        title = belongsTo == "customer" ? "Customer" : title;

                        _projects.openDialog({"title" : title+" Details"}, popupType);
                    } else {
                        if(_utils.get_current_module() == "home") {
                            $("#index_content").html(response);
                        } else {
                            $("#security_content").html(response);
                        }
                    }

                    //setViewBasics();
                    _users.setPrimaryContact();
                    //_users.setPrefContact();
                    _users.hideUnsetPrimaryContact();
                    //_users.hideUnsetPrefContact();
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        /* Utils Function */
        /*
        | Set the default selection of the primary contact "Radio button"
        */
        setPrimaryContact: function() {
            var primaryContact     = $("#dbPrimaryContact").val();

            $("input[name=primaryContact]").each(function() {
                if(this.value == primaryContact) {
                    $(this).prop('checked', true);
                }
            });

        },
        /*setPrefContact: function() {
            var prefContact    = $("#dbPrefContact").val().split(",");

            $("input[name=prefContact]").each(function() {
                if(prefContact.indexOf(this.value) >= 0) {
                    $(this).prop("checked", true);
                }
            });
        },*/
        /*setBelongsTo: function() {
            var belongsToDb = $("#belongsToDb").val();
            if(belongsToDb != "" && $("#belongsTo").length) {
                $("#belongsTo").val(belongsToDb);
            }
        },*/
        setreferredBy: function() {
            var referredByDb = $("#referredByDb").val();
            if(referredByDb != "" && $("#referredByDb").length) {
                $("#referredBy").val(referredByDb);
            }
        },
        setPrivilege: function() {
            if($("#privilege_db_val").length) {
                //var roleId = rolesBySno[$("#privilege_db_val").val()] ? rolesBySno[$("#privilege_db_val").val()].sno : "0";
                //$("#privilege").val(roleId);
                $("#privilege").val($("#privilege_db_val").val());
            }
        },
        hideUnsetPrimaryContact: function() {
            if($("#user_details_form").length) {
                var primaryContact = $("#dbPrimaryContact").val();

                $("input[name=primaryContact]").each(function() {
                    if(!$(this).prop('checked')) {
                        var value = this.value;
                        var type = $(this).prop("type");
                        $("#"+value+type).hide();
                    }
                });
            }
        },
        /*hideUnsetPrefContact: function() {
            if($("#user_details_form").length) {
                var prefContact    = $("#dbPrefContact").val().split(",");

                $("input[name=prefContact]").each(function() {
                    if(!$(this).prop('checked')) {
                        var value = this.value;
                        var type = $(this).prop("type");
                        $("#"+value+type).hide();
                        $("#"+value+type+"label").hide();
                    }
                });
            }
        },*/
        showBelongsToOption: function( ) {
            cleanInputForm();

            var role_text = $("#privilege option:selected").text();

            if( role_text.toLowerCase().indexOf("service provider") >= 0) {
                $(".contractor-search").show();
                $("#selectedContractorDb").show();

                if($("#contractorList li").length) {
                    $(".contractor-result").show();
                    $("#contractorList").show();
                } else {
                    $(".contractor-result").hide();
                    $("#contractorList").hide();
                }
            } else if ( role_text.toLowerCase().indexOf("partner") >= 0 ) {
                $(".adjuster-search").show();
                $("#selectedAdjusterDB").show();

                if($("#adjusterList li").length) {
                    $("#adjusterList").show();
                    $(".adjuster-result").show();
                } else {
                    $("#adjusterList").hide();
                    $(".adjuster-result").hide();
                }
            } else if ( role_text.toLowerCase().indexOf("insuranceco") >= 0 ) {
                $(".ins_comp-search").show();
                $("#selectedInsCompDB").show();

                if($("#ins_compList li").length) {
                    $("#ins_compList").show();
                    $(".ins_comp-result").show();
                } else {
                    $("#ins_compList").hide();
                    $(".ins_comp-result").hide();
                }
            }
        },
        showreferredByOption: function( ) {
            var referredBy = $("#referredBy").val();

            // Hide all search container by default
            $(".referredBycontractor-search").hide();
            $("#referredBycontractorList").hide();
            $(".referredBycontractor-result").hide();
            $("#referredByselectedContractorDb").hide();

            $(".referredByadjuster-search").hide();
            $("#referredByadjusterList").hide();
            $(".referredByadjuster-result").hide();
            $("#referredByselectedAdjusterDB").hide();

            if( referredBy == "contractor") {
                $(".referredBycontractor-search").show();
                $("#referredByselectedContractorDb").show();

                if($("#referredBycontractorList li").length) {
                    $(".referredBycontractor-result").show();
                    $("#referredBycontractorList").show();
                } else {
                    $(".referredBycontractor-result").hide();
                    $("#referredBycontractorList").hide();
                }
            } else if(referredBy == "adjuster") {
                $(".referredByadjuster-search").show();
                $("#referredByselectedAdjusterDB").show();

                if($("#referredByadjusterList li").length) {
                    $(".referredByadjuster-result").show();
                    $("#referredByadjusterList").show();
                } else {
                    $(".referredByadjuster-result").hide();
                    $("#referredByadjusterList").hide();
                }
            }
        },
        getContractorListUsingZip: function( prefix ) {
            var self = this;
            var contractors = null;

            var selected_role_id    = $("#privilege").val();
            var selected_role_text  = $("#privilege option:selected").text();
            var zip_to_search       = $("#"+prefix+"contractorZipCode").val();

            $('#'+prefix+'contractorList').children().remove();
            if($("#"+prefix+"contractorZipCode").val().trim() == "") {
                return;
            }
            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/getList",
                data: {
                    zip             : zip_to_search,
                    role_id         : selected_role_id,
                    role_disp_name  : selected_role_text
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status == "success") {
                        contractors = { 
                            list            : response["contractors"],
                            dispStrKey      : "company",
                            prefix          : prefix,
                            idPrefix        : "contractor",
                            name            : "optionSelectedContractor",
                            id              : "id",
                            "field_name"    : "name"
                        };
                        self.generateLiSingleSelectionDD( contractors );
                    } else {
                        alert(response.message);
                    }
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },
        getAdjusterByCompanyName: function( prefix ) {
            var partners = null;

            var selected_role_id    = $("#privilege").val();
            var selected_role_text  = $("#privilege option:selected").text();
            var company_name        = $("#"+prefix+"partnerCompanyName").val().trim();

            $('#'+prefix+'adjusterList').children().remove();
            if($("#"+prefix+"partnerCompanyName").val().trim() == "") return;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/adjusters/partners/getPartnerByCompanyName",
                data: {
                    companyName     : company_name,
                    role_id         : selected_role_id,
                    role_disp_name  : selected_role_text
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status == "success") {
                        partners = {
                            list            : response["partners"],
                            "dispStrKey"    : "name",
                            prefix          : prefix,
                            idPrefix        : "adjuster",
                            name            : "optionSelectedAdjuster",
                            id              : "id",
                            "field_name"    : "name"

                        };
                        self.generateLiSingleSelectionDD( partners );
                    } else {
                        alert(response.message);
                    }
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        get_ins_comp_by_name : function( prefix ) {
            var partners = null;

            var selected_role_id    = $("#privilege").val();
            var selected_role_text  = $("#privilege option:selected").text();
            var company_name        = $("#"+prefix+"ins_comp_name").val().trim();

            $('#'+prefix+'ins_compList').children().remove();
            if($("#"+prefix+"ins_comp_name").val().trim() == "") return;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/insurance_company/insurancecompany/get_ins_comp_by_name",
                data: {
                    companyName     : company_name,
                    role_id         : selected_role_id,
                    role_disp_name  : selected_role_text
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status == "success") {
                        partners = {
                            list            : response["ins_comps"],
                            "dispStrKey"    : "ins_comp_name",
                            prefix          : prefix,
                            idPrefix        : "ins_comp",
                            name            : "optionSelectedInsComp",
                            id              : "ins_comp_id",
                            "field_name"    : "ins_comp_name"

                        };
                        self.generateLiSingleSelectionDD( partners );
                    } else {
                        alert(response.message);
                    }
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        generateLiSingleSelectionDD: function (options) {
            var list        = options.list;
            var dispStrKey  = options.dispStrKey;
            var id          = options.id;
            var field_name  = options.field_name;
            var prefix      = options.prefix;
            var idPrefix    = options.idPrefix;
            var name        = options.name;

            for(var i =0 ; i < list.length; i++) {
                var li = "<li class=\"ui-state-highlight\" id=\""+list[i][id]+"\">";
                    li += "<div>"+list[i][field_name]+"</div>";
                    li += "<div class=\"company\">"+list[i][dispStrKey]+"</div>";
                    li += "<span class=\"search-action\"><input type=\"radio\" name=\""+prefix+name+"\" value=\""+list[i][id]+"\" /></span>";
                    li += "</li>";
                $('#'+prefix+idPrefix+'List').append(li);
            }

            $("."+prefix+idPrefix+"-result").show();
            $("#"+prefix+idPrefix+"List").show();
        },
        tcChecked: function() {
            if($("#termsCondition").prop("checked")) {
                $("#tcError").hide();
            }
        }
    }
})();