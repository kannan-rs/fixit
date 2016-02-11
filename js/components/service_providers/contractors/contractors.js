var _contractors = (function () {

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
            _projects.openDialog({"title" : prefixStr+" Service Provider"}, popupType);
        } else if(forForm == "create") {
            $("#contractor_content").html(response);
        }

        _utils.setStatus("status", "statusDb");
        _utils.getAndSetCountryStatus(forForm+"_contractor_form");

        if(forForm == "update") {
            _contractors.setPrefContact();
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

    function getTradeList( renderTrade ) {
        $.ajax({
            method: "POST",
            url: "/service_providers/trades/getTradesList",
            async: false,
            data: {
                contractorId     : _contractors.contractorId
            },
            success: function( response ) {
                if(!_utils.is_logged_in( response )) { return false; }
                response = $.parseJSON(response);
                if( response.status == "success") {
                    mapTradeData(response.tradesList);
                    if(renderTrade) {
                        displayTradesList(_contractors.contractorId);
                    }
                } else {
                    alert("Error while fetching Trades and Sub trades");
                }
            },
            error: function( error ) {
                error = error;
            }
        })
        .fail(function ( failedObj ) {
            fail_error = failedObj;
        });
    };

    function displayTradesList( contractorId ) {
        var tradesEle = $("#tradesList");

        var htmlContent = "No Tradess or Sub Tradess Found";
        //generateInternalLink("createTrade");
        if(tradeMappedList.parents.length) {
            htmlContent = "<div id=\"trade_accordion\" class=\"accordion\">";
            for(var i = 0; i < tradeMappedList.parents.length; i++) {
                var trends = tradeMappedList.parents[i];
                htmlContent += "<h3><span class=\"inner_accordion\">"+trends.trade_name+"</span>";
                
                htmlContent += "<a class=\"step fi-deleteRow size-21 accordion-icon icon-right red delete\" ";
                htmlContent += "href=\"javascript:void(0);\"  ";
                htmlContent += "onclick=\"_contractor_trades.deleteMainTradesForm(event, "+trends.trade_id_from_master_list+");\" ";
                htmlContent += "title=\"Delete Main Trade\"></a>";
                
                htmlContent += "<a class=\"step fi-page-edit size-21 accordion-icon icon-right\" ";
                htmlContent += "href=\"javascript:void(0);\"  ";
                htmlContent += "onclick=\"_contractor_trades.editTradesForm(event, "+trends.trade_id_from_master_list+", '"+trends.trade_name+"');\" ";
                htmlContent += "title=\"Edit Main Trade\"></a>";

                /*htmlContent += "<a class=\"step fi-page-add size-21 accordion-icon icon-right\" ";
                htmlContent += "href=\"javascript:void(0);\"  ";
                htmlContent += "onclick=\"_contractor_trades.addSubTradesForm(event, "+trends.trade_id+", '"+trends.trade_name+"');\" ";
                htmlContent += "title=\"Add Sub Trade\"></a>";*/

                htmlContent += "</h3>";

                htmlContent += "<table cellspacing=\"0\" class=\"viewOne\">";
                
                var childs = tradeMappedList.childs[trends.trade_id_from_master_list];
                if(childs && childs.length) {
                    for(var j = 0; j < childs.length; j++) {
                        htmlContent += "<tr>";
                        htmlContent += "<td class='cell'>";
                        htmlContent += "<span>"+childs[j].trade_name+"</span>";
                        
                        htmlContent += "<a class=\"step fi-deleteRow size-21 accordion-icon icon-right red delete\" ";
                        htmlContent += "href=\"javascript:void(0);\"  ";
                        htmlContent += "onclick=\"_contractor_trades.deleteSubTradesForm(event, "+childs[j].trade_id_from_master_list+", "+childs[j].trade_parent+");\" ";
                        htmlContent += "title=\"Delete Main Trade\"></a>";
                        
                        /*htmlContent += "<a class=\"step fi-page-edit size-21 accordion-icon icon-right\" ";
                        htmlContent += "href=\"javascript:void(0);\"  ";
                        htmlContent += "onclick=\"_contractor_trades.editSubTradesForm(event, "+childs[j].trade_id+", '"+childs[j].trade_name+"' , "+childs[j].trade_parent+");\" ";
                        htmlContent += "title=\"Edit Main Trade\"></a>";*/
                        
                        htmlContent += "</td>";
                        htmlContent += "</tr>";
                    }
                } else {
                    htmlContent += "<tr>";
                    htmlContent += "<td class='cell'> No sub trades available</td>";
                    htmlContent += "</tr>";
                }
                htmlContent += "</table>";

            }
            htmlContent += "</div>";

        }
        $(tradesEle).html(htmlContent);

        $("#tradesList .viewOne tr").bind( "mouseenter mouseleave", function() {
            $( this ).toggleClass( "active" );
        });

        _utils.set_accordion('trade_accordion');
    };

    function showDiscountList() {
        $.ajax({
            method: "POST",
            url: "/service_providers/discounts/showDiscountList",
            data: {
                contractor_id     : _contractors.contractorId
            },
            success: function( response ) {
                if(!_utils.is_logged_in( response )) { return false; }
                $("#discountList").html( response );
            },
            error: function( error ) {
                error = error;
            }
        })
        .fail(function ( failedObj ) {
            fail_error = failedObj;
        });
    }

    return {
        errorMessage: function () {
            return {
                name : {
                    required : _lang.english.errorMessage.contractorForm.name 
                },
                company : {
                    required : _lang.english.errorMessage.contractorForm.company 
                },
                type : {
                    required : _lang.english.errorMessage.contractorForm.type 
                },
                license : {
                    required : _lang.english.errorMessage.contractorForm.license 
                },
                bbb : {
                    required : _lang.english.errorMessage.contractorForm.bbb 
                },
                status : {
                    required : _lang.english.errorMessage.contractorForm.status 
                },
                addressLine1 : {
                    required : _lang.english.errorMessage.contractorForm.addressLine1
                },
                addressLine2 : {
                    required : _lang.english.errorMessage.contractorForm.addressLine2
                },
                city : {
                    required : _lang.english.errorMessage.contractorForm.city
                },
                country : {
                    required : _lang.english.errorMessage.contractorForm.country
                },
                state : {
                    required : _lang.english.errorMessage.contractorForm.state
                },
                zipCode : {
                    required     : _lang.english.errorMessage.contractorForm.zipCode,
                    minlength    : _lang.english.errorMessage.contractorForm.zipCode,
                    maxlength    : _lang.english.errorMessage.contractorForm.zipCode,
                    digits         : _lang.english.errorMessage.contractorForm.zipCode
                },
                emailId : {
                    required : _lang.english.errorMessage.contractorForm.emailId 
                },
                contactPhoneNumber : {
                    required     : _lang.english.errorMessage.contractorForm.contactPhoneNumber,
                    digits        : _lang.english.errorMessage.contractorForm.contactPhoneNumber,
                },
                mobileNumber : {
                    required     : _lang.english.errorMessage.contractorForm.mobileNumber, 
                    digits        : _lang.english.errorMessage.contractorForm.mobileNumber
                },
                prefContactEmailId : {
                    required : _lang.english.errorMessage.contractorForm.prefContactEmailId 
                },
                prefContactofficeNumber : {
                    required : _lang.english.errorMessage.contractorForm.prefContactofficeNumber 
                },
                prefContactMobileNumber : {
                    required : _lang.english.errorMessage.contractorForm.prefContactMobileNumber 
                },
                websiteURL : {
                    required : _lang.english.errorMessage.contractorForm.websiteURL 
                },
                serviceZip : {
                    required : _lang.english.errorMessage.contractorForm.serviceZip 
                }
            };
        },

        validationRules: function() {
            return {
                zipCode : {
                    required: true,
                    /*minlength: 5,
                    maxlength: 5,
                    digits : true*/
                },
                contactPhoneNumber : {
                    digits : true    
                },
                mobileNumber : {
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
                _projects.clearRest();
                _projects.toggleAccordiance("contractors", "new");
            }
            
            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/createForm",
                data: {
                    openAs         : openAs,
                    popupType     : popupType,
                    projectId     : projectId
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
            var validator = $( "#create_contractor_form" ).validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation();
            if(cityError) {
                return false;
            }

            if(validator) {
                _contractors.createSubmit( openAs, popupType );
            }
        },

        createSubmit: function( openAs, popupType ) {
            var idPrefix                 = "#create_contractor_form "
            var name                     = $(idPrefix+"#name").val();
            var company                 = $(idPrefix+"#company").val();
            var type                     = $(idPrefix+"#type").val();
            var license                 = $(idPrefix+"#license").val();
            //var bbb                     = $(idPrefix+"#bbb").val() || "";
            var status                  = $(idPrefix+"#status").val() || "active";
            var addressLine1             = $(idPrefix+"#addressLine1").val();
            var addressLine2             = $(idPrefix+"#addressLine2").val();
            var city                     = $(idPrefix+"#city").val();
            var state                     = $(idPrefix+"#state").val();
            var country                 = $(idPrefix+"#country").val();
            var zipCode                 = $(idPrefix+"#zipCode").val();
            var emailId                 = $(idPrefix+"#emailId").val();
            var contactPhoneNumber         = $(idPrefix+"#contactPhoneNumber").val();
            var mobileNumber             = $(idPrefix+"#mobileNumber").val();
            var prefContact             = "";
            var websiteURL                 = $(idPrefix+"#websiteURL").val();
            var serviceZip                = $(idPrefix+"#serviceZip").val();
            var db_default_user_id      = $(idPrefix+"#db_default_user_id").val();

            $(idPrefix+"input[name=prefContact]:checked").each(
                function() {
                    prefContact += prefContact != "" ? (","+this.value) : this.value;
                }
            );

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/add",
                data: {
                    name                     : name,
                    company                 : company,
                    type                     : type,
                    license                 : license,
                    //bbb                     : bbb,
                    status                     : status,
                    addressLine1             : addressLine1,
                    addressLine2             : addressLine2,
                    city                     : city,
                    state                     : state,
                    country                 : country,
                    zipCode                 : zipCode,
                    emailId                 : emailId,
                    contactPhoneNumber         : contactPhoneNumber,
                    mobileNumber             : mobileNumber,
                    prefContact             : prefContact,
                    websiteURL                 : websiteURL,
                    serviceZip                 : serviceZip,
                    openAs                     : openAs,
                    popupType                 : popupType,
                    db_default_user_id      : db_default_user_id
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        _contractors.viewOne(response.insertedId, openAs, popupType);
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

        viewOne: function( contractorId, openAs, popupType ) {
            this.contractorId     = contractorId;
            popupType             = popupType ? popupType : "";
            if(!openAs || openAs != "popup") {
                _projects.clearRest();
                _projects.toggleAccordiance("contractors", "viewOne");
            }
            
            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/viewOne",
                data: {
                    contractorId     : _contractors.contractorId,
                    openAs             : openAs,
                    popupType         : popupType
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    if( openAs && openAs == "popup") {
                        $("#popupForAll"+popupType).html( response );
                        _projects.openDialog({"title" : "Service Provider Details"}, popupType);
                        _projects.updateContractorSelectionList();
                        _projects.setContractorDetails();
                    } else {
                        $("#contractor_content").html(response);
                        //$(function() {
                            $( "#contractor_tabs" ).tabs();
                        //});
                    }

                    _utils.set_accordion('service_provider_accordion');
                    _contractors.setPrefContact();
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
            _projects.toggleAccordiance("contractors", "viewAll");

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/viewAll",
                data: {},
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#contractor_content").html(response);
                    _contractors.showContractorsList();
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
                url: "/service_providers/contractors/editForm",
                data: {
                    'contractorId' : _contractors.contractorId,
                    'openAs'         : openAs,
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
            var validator = $( "#update_contractor_form" ).validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation();
            if(cityError) {
                return false;
            }

            if(validator) {
                _contractors.updateSubmit();
            }
        },

        updateSubmit: function() {
            var idPrefix                = "#update_contractor_form ";
            var contractorId            = $(idPrefix+"#contractorId").val();
            //var name                  = $(idPrefix+"#name").val();
            var company                 = $(idPrefix+"#company").val();
            var type                    = $(idPrefix+"#type").val();
            var license                 = $(idPrefix+"#license").val();
            //var bbb                     = $(idPrefix+"#bbb").val();
            var status                  = $(idPrefix+"#status").val() || "active";
            var addressLine1            = $(idPrefix+"#addressLine1").val();
            var addressLine2            = $(idPrefix+"#addressLine2").val();
            var city                    = $(idPrefix+"#city").val();
            var state                   = $(idPrefix+"#state").val();
            var country                 = $(idPrefix+"#country").val();
            var zipCode                 = $(idPrefix+"#zipCode").val();
            var emailId                 = $(idPrefix+"#emailId").val();
            var contactPhoneNumber      = $(idPrefix+"#contactPhoneNumber").val();
            var mobileNumber            = $(idPrefix+"#mobileNumber").val();
            var prefContact             = "";
            var websiteURL              = $(idPrefix+"#websiteURL").val();
            var serviceZip              = $(idPrefix+"#serviceZip").val();
            var db_default_user_id      = $(idPrefix+"#db_default_user_id").val();

            $(idPrefix+"input[name=prefContact]:checked").each(
                function() {
                    prefContact += prefContact != "" ? (","+this.value) : this.value;
                }
            );

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/update",
                data: {
                    contractorId             : contractorId,
                    //name                     : name,
                    company                 : company,
                    type                     : type,
                    license                 : license,
                    //bbb                     : bbb,
                    status                     : status,
                    addressLine1             : addressLine1,
                    addressLine2             : addressLine2,
                    city                     : city,
                    state                     : state,
                    country                 : country,
                    zipCode                 : zipCode,
                    emailId                 : emailId,
                    contactPhoneNumber         : contactPhoneNumber,
                    mobileNumber             : mobileNumber,
                    prefContact             : prefContact,
                    websiteURL                 : websiteURL,
                    serviceZip                 : serviceZip,
                    db_default_user_id      : db_default_user_id
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        $(".ui-button").trigger("click");
                        alert(response.message);
                        _contractors.viewOne(response.updatedId);
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
            var deleteConfim = confirm("Do you want to delete this service provider company");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/deleteRecord",
                data: {
                    contractorId: _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        _contractors.viewAll();
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

        showContractorsList: function ( event ) {
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
                emailId     : emailId,
                belongsTo   : 'contractor|empty',
                assignment  : 'not assigned'
            }

            var response = _utils.getCustomerList( requestParams );

            var responseObj = $.parseJSON(response);
            var customer = [];
            $("#contractorUserList").html("");
            if (responseObj.status === "success") {
                
                customer = responseObj.customer;
                if(customer.length) {
                    var searchList = {
                        list: customer,
                        excludeList: [],
                        appendTo: "contractorUserList",
                        type: "searchList",
                        functionName: "_contractors.setSelectedDefaultUserId",
                        searchBoxId: "searchForDefaultContractor",
                        dbEntryId: "db_default_user_id",
                        dataIdentifier    : "customer",
                    };

                    _utils.createDropDownOptionsList(searchList);
                    $(".default-user-search-result").show();
                    $(".contractorUserList").show();
                }
            } else {
                $(".default-user-search-result").hide();
                $(".contractorUserList").hide();
            }
        },

        setSelectedDefaultUserId: function (event, element, options) {
            $("#searchForDefaultContractor").val(options.first_name + " " + options.last_name);
            $("#db_default_user_id").val(options.searchId);
            $(".default-user-search-result").hide();
            $(".contractorUserList").hide();
        }
    }
})();