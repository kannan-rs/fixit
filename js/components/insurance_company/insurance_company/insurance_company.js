var _ins_comp = (function () {

    var tradeMappedList = {
        parents  : [],
        childs  : {}
    };

    function formInitialSettings(forForm, options, response) {
        var openAs         = options && options.openAs ? options.openAs : "";
        var popupType     = options && options.popupType ? options.popupType : "";

        if(openAs == "popup") {
            $("#popupForAll"+popupType).html( response );
            var prefixStr = forForm == "create" ? "Add" : "Edit";
            _projects.openDialog({"title" : prefixStr+" Insurance Company"}, popupType);
        } else if(forForm == "create") {
            $("#ins_comp_content").html(response);
        }

        _utils.setStatus("status", "statusDb");
        _utils.getAndSetCountryStatus(forForm+"_ins_comp_form");

        if(forForm == "update") {
            //_ins_comp.setPrefContact();
            _utils.setAddressByCity();
            _utils.getAndSetMatchCity($("#city_jqDD").val(), "edit", '');
        }

        $(".default-user-search-result").hide();
    };

    function mapTradeData( tradeDbList ) {
        tradeMappedList = {
            parents  : [],
            childs  : {}
        };
        if(tradeDbList) {
            for(var i = 0; i < tradeDbList.length; i++) {
                var trade = tradeDbList[i];
                if(trade.trade_parent == "0") {
                    tradeMappedList.parents.push(trade);
                     tradeMappedList.childs[trade.trade_id_from_master_list] = [];
                }
            }
            //console.log(tradeMappedList);

            for(var i = 0; i < tradeDbList.length; i++) {
                var trade = tradeDbList[i];
                if(trade.trade_parent != "0" && tradeMappedList.childs[trade.trade_parent]) {
                    tradeMappedList.childs[trade.trade_parent].push(trade);
                }
            }
            //console.log(tradeMappedList);            
        }
    }

    return {
        errorMessage: function () {
            return {
                company : {
                    required : _lang.english.errorMessage.insurance_compnay_form.company 
                },
                addressLine1 : {
                    required : _lang.english.errorMessage.insurance_compnay_form.addressLine1
                },
                addressLine2 : {
                    required : _lang.english.errorMessage.insurance_compnay_form.addressLine2
                },
                city : {
                    required : _lang.english.errorMessage.insurance_compnay_form.city
                },
                country : {
                    required : _lang.english.errorMessage.insurance_compnay_form.country
                },
                state : {
                    required : _lang.english.errorMessage.insurance_compnay_form.state
                },
                zipCode : {
                    required     : _lang.english.errorMessage.insurance_compnay_form.zipCode,
                    minlength    : _lang.english.errorMessage.insurance_compnay_form.zipCode,
                    maxlength    : _lang.english.errorMessage.insurance_compnay_form.zipCode,
                    digits         : _lang.english.errorMessage.insurance_compnay_form.zipCode
                },
                emailId : {
                    required : _lang.english.errorMessage.insurance_compnay_form.emailId 
                },
                websiteURL : {
                    required : _lang.english.errorMessage.insurance_compnay_form.websiteURL 
                }
            };
        },

        validationRules: function() {
            return {
                zipCode : {
                    required: true,
                },
                contactPhoneNumber : {
                    digits : true    
                }
            };
        },

        createForm: function(event, options ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }
            
            var openAs         = options && options.openAs ? options.openAs : "";
            var popupType     = options && options.popupType ? options.popupType : "";
            var projectId     = options && options.projectId ? options.projectId : "";

            if(!openAs) {
                //_projects.clearRest();
                //_projects.toggleAccordiance("contractors", "new");
            }
            
            $.ajax({
                method: "POST",
                url: "/insurance_company/insurancecompany/createForm",
                data: {
                    openAs         : openAs,
                    popupType     : popupType
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    formInitialSettings("create", options, response);
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        createValidate:  function ( openAs, popupType ) {
            var cityError = false;
            var validator = $( "#create_ins_comp_form" ).validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation("", "create_ins_comp_form");
            if(cityError) {
                return false;
            }

            if(validator) {
                _ins_comp.createSubmit( openAs, popupType );
            }
        },

        createSubmit: function( openAs, popupType ) {
            var idPrefix                = "#create_ins_comp_form "
            var company                 = $(idPrefix+"#company").val();
            var emailId                 = $(idPrefix+"#emailId").val();
            var contact_phone_no        = $(idPrefix+"#contactPhoneNumber").val();
            var addressLine1            = $(idPrefix+"#addressLine1").val();
            var addressLine2            = $(idPrefix+"#addressLine2").val();
            var city                    = $(idPrefix+"#city").val();
            var state                   = $(idPrefix+"#state").val();
            var country                 = $(idPrefix+"#country").val();
            var zipCode                 = $(idPrefix+"#zipCode").val();
            var websiteURL              = $(idPrefix+"#websiteURL").val();
            var db_default_user_id      = $(idPrefix+"#db_default_user_id").val();


            $.ajax({
                method: "POST",
                url: "/insurance_company/insurancecompany/add",
                data: {
                    company                 : company,
                    addressLine1            : addressLine1,
                    addressLine2            : addressLine2,
                    city                    : city,
                    state                   : state,
                    country                 : country,
                    zipCode                 : zipCode,
                    emailId                 : emailId,
                    contactPhoneNumber      : contact_phone_no,
                    websiteURL              : websiteURL,
                    openAs                  : openAs,
                    popupType               : popupType,
                    db_default_user_id      : db_default_user_id
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        _ins_comp.viewOne(response.insertedId, openAs, popupType);
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

        viewOne: function( ins_comp_id, openAs, popupType ) {
            this.ins_comp_id     = ins_comp_id;
            popupType             = popupType ? popupType : "";
            if(!openAs || openAs != "popup") {
                _projects.clearRest();
            }
            
            $.ajax({
                method: "POST",
                url: "/insurance_company/insurancecompany/viewOne",
                data: {
                    ins_comp_id     : _ins_comp.ins_comp_id,
                    openAs             : openAs,
                    popupType         : popupType
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#ins_comp_content").html(response);

                    _utils.set_accordion('ins_comp_accordion');
                    _ins_comp.setPrefContact();
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        viewAll: function() {
            _projects.clearRest();
            _projects.toggleAccordiance("ins_comps", "viewAll");

            $.ajax({
                method: "POST",
                url: "/insurance_company/insurancecompany/viewAll",
                data: {},
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#ins_comp_content").html(response);
                    //_ins_comp.show_ins_comps_list();
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        editForm: function( options ) {
            var openAs         = options && options.openAs ? options.openAs : "";
            var popupType     = options && options.popupType ? options.popupType : "";

            $.ajax({
                method: "POST",
                url: "/insurance_company/insurancecompany/editForm",
                data: {
                    'ins_comp_id'   : _ins_comp.ins_comp_id,
                    'openAs'        : openAs,
                    'popupType'     : popupType
                    
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    formInitialSettings("update", options, response);
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        updateValidate: function() {
            var cityError = false;
            var validator = $( "#update_ins_comp_form" ).validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation("", "update_ins_comp_form");
            if(cityError) {
                return false;
            }

            if(validator) {
                _ins_comp.updateSubmit();
            }
        },

        updateSubmit: function() {
            var idPrefix                = "#update_ins_comp_form ";
            var ins_comp_id            = $(idPrefix+"#ins_comp_id").val();
            var company                 = $(idPrefix+"#company").val();
            var emailId                 = $(idPrefix+"#emailId").val();
            var contactPhoneNumber      = $(idPrefix+"#contactPhoneNumber").val();
            var addressLine1            = $(idPrefix+"#addressLine1").val();
            var addressLine2            = $(idPrefix+"#addressLine2").val();
            var city                    = $(idPrefix+"#city").val();
            var state                   = $(idPrefix+"#state").val();
            var country                 = $(idPrefix+"#country").val();
            var zipCode                 = $(idPrefix+"#zipCode").val();
            var websiteURL              = $(idPrefix+"#websiteURL").val();
            var db_default_user_id      = $(idPrefix+"#db_default_user_id").val();

           

            $.ajax({
                method: "POST",
                url: "/insurance_company/insurancecompany/update",
                data: {
                    ins_comp_id             : ins_comp_id,
                    company                 : company,
                    emailId                 : emailId,
                    contactPhoneNumber      : contactPhoneNumber,
                    addressLine1            : addressLine1,
                    addressLine2            : addressLine2,
                    city                    : city,
                    state                   : state,
                    country                 : country,
                    zipCode                 : zipCode,
                    websiteURL              : websiteURL,
                    db_default_user_id      : db_default_user_id
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        $(".ui-button").trigger("click");
                        alert(response.message);
                        _ins_comp.viewOne(response.updatedId);
                    } else if(response.status.toLowerCase() == "error") {
                        alert(response.message);
                    }
                },
                error: function( error ) {
                    alert(error);
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
                alert(fail_error);
            });
        },

        deleteRecord: function() {
            var deleteConfim = confirm("Do you want to delete this insurance company");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/insurance_company/insurancecompany/deleteRecord",
                data: {
                    ins_comp_id: _ins_comp.ins_comp_id
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        _ins_comp.viewAll();
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

        setPrefContact: function() {
            var prefContact    = $("#prefContactDb").length ? $("#prefContactDb").val().split(",") : "";

            $("input[name=prefContact]").each(function() {
                if(prefContact.indexOf(this.value) >= 0) {
                    $(this).prop("checked", true);
                }
            });
        },

        show_ins_comps_list: function ( event ) {
            var options = "active";

            if( event ) {
                options = event.target.getAttribute("data-option");
                if(options) {
                    $($(".contractors.internal-tab-as-links").children()).removeClass("active");
                    $(".contractors-table-list .row").hide();
                    $(event.target).addClass("active");
                } 
            } else {
                $($(".contractors.internal-tab-as-links").children()).removeClass("active");
                $(".contractors-table-list .row").hide();
                $($(".contractors.internal-tab-as-links").children()[0]).addClass("active");
            }

            if(options == "all") {
                $(".contractors-table-list .row").show();
            } else if (options != "") {
                $(".contractors-table-list .row."+options).show();
            }
        },

        searchUserByEmail: function (params) {
            var emailId     = params.emailId;
            if( !emailId || emailId.length < 3 ) {
                return;
            }

            var requestParams = {
                emailId         : emailId,
                role_disp_name  : 'INSURANCECO_ADMIN',
                assignment      : 'not assigned'
            }

            var response = _utils.getFromUsersList( requestParams );

            var responseObj = $.parseJSON(response);
            var customer = [];
            $("#ins_compUserList").html("");
            if (responseObj.status === "success") {
                
                customer = responseObj.customer;
                if(customer.length) {
                    var searchList = {
                        list            : customer,
                        excludeList     : [],
                        appendTo        : "ins_compUserList",
                        type            : "searchList",
                        functionName    : "_ins_comp.setSelectedDefaultUserId",
                        searchBoxId     : "searchForDefaultContractor",
                        dbEntryId       : "db_default_user_id",
                        dataIdentifier  : "customer",
                    };

                    _utils.createDropDownOptionsList(searchList);
                    $(".default-user-search-result").show();
                    $(".ins_compUserList").show();
                }
            } else {
                $(".default-user-search-result").hide();
                $(".contractorUserList").hide();
            }
        },

        setSelectedDefaultUserId: function (event, element, options) {
            $("#search_for_default_user").val(options.first_name + " " + options.last_name);
            $("#db_default_user_id").val(options.searchId);
            $(".default-user-search-result").hide();
            $(".ins_compUserList").hide();
        }
    }
})();