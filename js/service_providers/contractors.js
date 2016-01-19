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
            _utils.getAndSetMatchCity($("#city_jqDD").val(), "edit");
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
            url: "/service_providers/contractors/getTradesList",
            async: false,
            data: {
                contractorId     : _contractors.contractorId
            },
            success: function( response ) {
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
            htmlContent = "<div id=\"accordion\" class=\"accordion\">";
            for(var i = 0; i < tradeMappedList.parents.length; i++) {
                var trends = tradeMappedList.parents[i];
                htmlContent += "<h3><span class=\"inner_accordion\">"+trends.trade_name+"</span>";
                
                htmlContent += "<a class=\"step fi-deleteRow size-21 accordion-icon icon-right red delete\" ";
                htmlContent += "href=\"javascript:void(0);\"  ";
                htmlContent += "onclick=\"_contractors.deleteMainTradesForm(event, "+trends.trade_id_from_master_list+");\" ";
                htmlContent += "title=\"Delete Main Trade\"></a>";
                
                htmlContent += "<a class=\"step fi-page-edit size-21 accordion-icon icon-right\" ";
                htmlContent += "href=\"javascript:void(0);\"  ";
                htmlContent += "onclick=\"_contractors.editTradesForm(event, "+trends.trade_id_from_master_list+", '"+trends.trade_name+"');\" ";
                htmlContent += "title=\"Edit Main Trade\"></a>";

                /*htmlContent += "<a class=\"step fi-page-add size-21 accordion-icon icon-right\" ";
                htmlContent += "href=\"javascript:void(0);\"  ";
                htmlContent += "onclick=\"_contractors.addSubTradesForm(event, "+trends.trade_id+", '"+trends.trade_name+"');\" ";
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
                        htmlContent += "onclick=\"_contractors.deleteSubTradesForm(event, "+childs[j].trade_id_from_master_list+", "+childs[j].trade_parent+");\" ";
                        htmlContent += "title=\"Delete Main Trade\"></a>";
                        
                        /*htmlContent += "<a class=\"step fi-page-edit size-21 accordion-icon icon-right\" ";
                        htmlContent += "href=\"javascript:void(0);\"  ";
                        htmlContent += "onclick=\"_contractors.editSubTradesForm(event, "+childs[j].trade_id+", '"+childs[j].trade_name+"' , "+childs[j].trade_parent+");\" ";
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

        $("#accordion").accordion(
            {
                collapsible: true,
                icons: {header: "ui-icon-plus", activeHeader: "ui-icon-minus"},
                active: false
            }
       );
    };

    function showDiscountList() {
        $.ajax({
            method: "POST",
            url: "/service_providers/contractors/showDiscountList",
            data: {
                contractor_id     : _contractors.contractorId
            },
            success: function( response ) {
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

    function populateMainTradeInDiscount( ddId ) {
        var htmlContent = "<option value=\"0\">-- Select Main Trade --</option>";
        if(tradeMappedList.parents.length) {
            for(var i = 0; i < tradeMappedList.parents.length; i++) {
                var trade = tradeMappedList.parents[i];
                htmlContent += "<option value='"+trade.trade_id+"'>"+trade.trade_name+"</option>";
            }
        }

        if( !ddId || ddId == "") {
            ddId = "discount_for_main_trade";
        }
        $("#"+ddId ).html(htmlContent);
    }

    function _populateSubTradeInDiscount( mainTradeId, ddId ) {
        var htmlContent = "<option value=\"0\">-- Select Sub Trade --</option>";
        if(mainTradeId && mainTradeId != "0" && tradeMappedList.childs[mainTradeId].length) {
            for(var i = 0; i < tradeMappedList.childs[mainTradeId].length; i++) {
                var trade = tradeMappedList.childs[mainTradeId][i];
                htmlContent += "<option value='"+trade.trade_id+"'>"+trade.trade_name+"</option>";
            }
        }

        if( !ddId || ddId == "") {
            ddId = "discount_for_sub_trade";
        }

        $("#"+ddId).html(htmlContent);
    }

    /*function renderTestimonialView( list ) {
        
    }*/

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
        },

        showTradeList: function() {
            getTradeList( true );
        },

        addNewMainTrendsForm : function() {
           $.ajax({
                method: "POST",
                url: "/service_providers/contractors/createFormMainTrades",
                data: {
                    contractorId : _contractors.contractorId
                },
                success: function( response ) {
                    $("#popupForAll").html( response );
                    $(".sub-trade-tr").hide();
                    _projects.openDialog({"title" : "Add New Main Trades"});
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        createTradeValidate : function() {
            var validator = $( "#contractor_create_trade_form" ).validate(
                {
                    rules: {
                        trade_name : {
                            required : true
                        }
                    },
                    messages: {
                        trade_name : {
                            required : "Please provide Trades name"
                        }
                    }
                }
            ).form();

            if(validator && $("input[name=sub_trades]:checked").length) {
                _contractors.createTradeSubmit();
            }
        },

        createTradeSubmit: function() {
            var main_trade_id = $("#trade_name").val();
            var sub_trades_id = "";
            $.each($("input[name=sub_trades]:checked"), function() {
                sub_trades_id += sub_trades_id.length ? "," : "";
                sub_trades_id += $(this).attr('id');
            });

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/addTrades",
                data: {
                    main_trade_id       : main_trade_id,
                    sub_trades_id       : sub_trades_id,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractors.showTradeList();
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

        editTradesForm: function( event, mainTradeId, dispString ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }
            var self = this;

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/updateFormMainTrades",
                data: {
                    trade_id        : mainTradeId,
                    contractorId    : _contractors.contractorId,
                    displayString   : dispString
                },
                success: function( response ) {
                    $("#popupForAll").html( response );
                    _projects.openDialog({"title" : "Edit Main Trades \""+dispString+"\""});

                    self.addSubTradesForm(undefined, mainTradeId);
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        updateTradeValidate : function( main_trade_id ) {
            if(!main_trade_id) {
                return;
            }

            if( $("input[name=sub_trades]:checked").length ) {
                _contractors.updateTradeSubmit();
            }
        },

        updateTradeSubmit: function( main_trade_id ) {
            var main_trade_id    = $("#main_trade_id_db_value").val();

            var selected_sub_trades_id_array = [];
            var to_add_sub_trades_id    = "";
            var to_delete_sub_trade_id  = "";
            
            var existing_sub_list_arr = $("#existingSubList").val().split(",");
            
            $.each($("input[name=sub_trades]:checked"), function() {
                var value = $(this).attr('id');
                selected_sub_trades_id_array.push(value);

                if(existing_sub_list_arr.indexOf(value) == -1) {
                    to_add_sub_trades_id += to_add_sub_trades_id.length ? "," : "";
                    to_add_sub_trades_id += value;
                }
            });

            for(var i = 0, count = existing_sub_list_arr.length; i < count; i++) {
                if(selected_sub_trades_id_array.indexOf(existing_sub_list_arr[i]) == -1) {
                    to_delete_sub_trade_id += to_delete_sub_trade_id.length ? "," : "";
                    to_delete_sub_trade_id += existing_sub_list_arr[i];
                }
            }

            if(!to_add_sub_trades_id && !to_delete_sub_trade_id) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/updateTrades",
                data: {
                    main_trade_id               : main_trade_id,
                    to_add_sub_trades_id        : to_add_sub_trades_id,
                    to_delete_sub_trade_id      : to_delete_sub_trade_id,
                    contractor_id               : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractors.showTradeList();
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

        deleteMainTradesForm: function(event, trade_id) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

             var deleteConfim = confirm("Do you want to delete this Trade and its sub trades");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/deleteMainTrades",
                data: {
                    trade_id        : trade_id,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON( response );
                    if(response.status == "success") {
                        alert(response.message);
                        _contractors.showTradeList();
                    } else if( response.status == "error") {
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

        addSubTradesForm: function(event, main_trade_id) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/createSubTradesForm",
                data: {
                    main_trade_id       : main_trade_id,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    $("#sub-trade-container").html( response );
                    $(".sub-trade-tr").show();
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        createSubTradeValidate : function() {
            var validator = $( "#contractor_create_sub_trade_form" ).validate(
                {
                    rules: {
                        sub_trade_name : {
                            required : true
                        }
                    },
                    messages: {
                        sub_trade_name : {
                            required : "Please provide Sub Trades name"
                        }
                    }
                }
            ).form();

            if(validator) {
                _contractors.createSubTradeSubmit();
            }
        },

        createSubTradeSubmit: function() {
            var sub_trade_name  = $("#sub_trade_name").val();
            var main_trade_id   = $("#main_trade_id_db_value").val();

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/addSubTrades",
                data: {
                    main_trade_id       : main_trade_id,
                    sub_trade_name      : sub_trade_name,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractors.showTradeList();
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

        editSubTradesForm: function( event, subTradeId, dispString, mainTradeId ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/updateFormSubTrades",
                data: {
                    sub_trade_id    : subTradeId,
                    main_trade_id   : mainTradeId,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    $("#popupForAll").html( response );
                    _projects.openDialog({"title" : "Edit Sub Trades \""+dispString+"\""});
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        updateSubTradeValidate : function() {
            var validator = $( "#contractor_update_sub_trade_form" ).validate(
                {
                    rules: {
                        sub_trade_name : {
                            required : true
                        }
                    },
                    messages: {
                        sub_trade_name : {
                            required : "Please provide Trades name"
                        }
                    }
                }
            ).form();

            if(validator) {
                _contractors.updateSubTradeSubmit();
            }
        },

        updateSubTradeSubmit: function() {
            var sub_trade_name      = $("#sub_trade_name").val();
            var sub_trade_id        = $("#sub_trade_id_db_value").val();
            var main_trade_id       = $("#main_trade_id_db_value").val();

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/updateSubTrades",
                data: {
                    sub_trade_name      : sub_trade_name,
                    sub_trade_id        : sub_trade_id,
                    main_trade_id       : main_trade_id,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractors.showTradeList();
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

        deleteSubTradesForm: function(event, sub_trade_id, main_trade_id ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

             var deleteConfim = confirm("Do you want to delete this trade");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/deleteSubTrades",
                data: {
                    sub_trade_id    : sub_trade_id,
                    main_trade_id   : main_trade_id,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON( response );
                    if(response.status == "success") {
                        alert(response.message);
                        _contractors.showTradeList();
                    } else if( response.status == "error") {
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

        showDiscountInitialPage: function() {
            getTradeList();
            showDiscountList();
            populateMainTradeInDiscount();
        },

        populateSubTradeInDiscount: function( mainTradeId, ddId ) {
            _populateSubTradeInDiscount( mainTradeId, ddId );
        },

        addDiscountForm: function(event) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            var main_trade_id   = $("#discount_for_main_trade").val();
            var sub_trade_id    = $("#discount_for_sub_trade").val();
            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/createDiscountForm",
                data: {
                    main_trade_id       : main_trade_id,
                    sub_trade_id        : sub_trade_id,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    $("#popupForAll").html( response );
                    _projects.openDialog({"title" : "Add Discount Form"});
                    populateMainTradeInDiscount("input_discount_for_main_trade");
                    if(main_trade_id) {
                        $("#input_discount_for_main_trade").val(main_trade_id);
                        $("#input_discount_for_main_trade").trigger("change");
                    }
                   if(sub_trade_id) {
                        setTimeout(function(){
                            $("#input_discount_for_sub_trade").val(sub_trade_id);
                        } ,500);
                    }
                    _utils.setAsDateFields( {dateField : "discount_from_date"} );
                    _utils.setAsDateFields( {dateField : "discount_to_date"} );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        createDiscountValidate : function() {
            var validator = $( "#create_discount_contractor_form" ).validate(
                {
                    rules: {
                        discount_value : {
                            required : true
                        },
                        discount_name : {
                            required : true
                        }
                    },
                    messages: {
                        discount_value : {
                            required : "Please provide discount value"
                        },
                        discount_name : {
                            required: "Please provide discount name"
                        }
                    }
                }
            ).form();

            if(validator) {
                _contractors.createDiscountSubmit();
            }
        },

        createDiscountSubmit: function() {
            var main_trade_id       = $("#input_discount_for_main_trade").val();
            var sub_trade_id        = $("#input_discount_for_sub_trade").val();
            var discount_name       = $("#discount_name").val();
            var discount_descr      = $("#discount_descr").val();
            var discount_for_zip    = $("#discount_for_zip").val();
            var discount_type       = $('#create_discount_contractor_form input[name=discount_type]:checked').val();
            var discount_value      = $("#discount_value").val();
            var discount_from_date  = _utils.toMySqlDateFormat($("#discount_from_date").val());
            var discount_to_date    = _utils.toMySqlDateFormat($("#discount_to_date").val());

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/addDiscount",
                data: {
                    main_trade_id       : main_trade_id,
                    sub_trade_id        : sub_trade_id,
                    discount_name       : discount_name,
                    discount_descr      : discount_descr,
                    discount_for_zip    : discount_for_zip,
                    discount_type       : discount_type,
                    discount_value      : discount_value,
                    discount_from_date  : discount_from_date,
                    discount_to_date    : discount_to_date,

                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        showDiscountList();
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

        editDiscountForm: function( event, discountId ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            //var main_trade_id   = $("#discount_for_main_trade").val();
            //var sub_trade_id    = $("#discount_for_sub_trade").val();
            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/editDiscountForm",
                data: {
                    //main_trade_id       : main_trade_id,
                    //sub_trade_id        : sub_trade_id,
                    contractor_id       : _contractors.contractorId,
                    discount_id         : discountId
                },
                success: function( response ) {
                    $("#popupForAll").html( response );
                    _projects.openDialog({"title" : "Edit Discount Form"});
                    populateMainTradeInDiscount("input_discount_for_main_trade");
                    
                    var main_trade_id = $("#selectedMainTradeId").val();
                    if(main_trade_id && main_trade_id != "" && main_trade_id != "0") {
                        $("#input_discount_for_main_trade").val(main_trade_id);
                        $("#input_discount_for_main_trade").trigger("change");
                    }

                    var sub_trade_id = $("#selectedSubTradeId").val();
                   if(sub_trade_id && sub_trade_id != "" && sub_trade_id != "0") {
                        setTimeout(function(){
                            $("#input_discount_for_sub_trade").val(sub_trade_id);
                        } ,500);
                    }
                    _utils.setAsDateFields( {dateField : "discount_from_date"} );
                    _utils.setAsDateFields( {dateField : "discount_to_date"} );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        updateDiscountValidate: function() {
            var validator = $( "#update_discount_contractor_form" ).validate(
                {
                    rules: {
                        discount_value : {
                            required : true
                        },
                        discount_name : {
                            required : true
                        }
                    },
                    messages: {
                        discount_value : {
                            required : "Please provide discount value"
                        },
                        discount_name : {
                            required: "Please provide discount name"
                        }
                    }
                }
            ).form();

            if(validator) {
                _contractors.updateDiscountSubmit();
            }
        },

        updateDiscountSubmit: function() {
            var discount_id         = $("#dbDiscountId").val();
            var main_trade_id       = $("#input_discount_for_main_trade").val();
            var sub_trade_id        = $("#input_discount_for_sub_trade").val();
            var discount_name       = $("#discount_name").val();
            var discount_descr      = $("#discount_descr").val();
            var discount_for_zip    = $("#discount_for_zip").val();
            var discount_type       = $('#update_discount_contractor_form input[name=discount_type]:checked').val();
            var discount_value      = $("#discount_value").val();
            var discount_from_date  = _utils.toMySqlDateFormat($("#discount_from_date").val());
            var discount_to_date    = _utils.toMySqlDateFormat($("#discount_to_date").val());

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/updateDiscount",
                data: {
                    discount_id         : discount_id,
                    main_trade_id       : main_trade_id,
                    sub_trade_id        : sub_trade_id,
                    discount_name       : discount_name,
                    discount_descr      : discount_descr,
                    discount_for_zip    : discount_for_zip,
                    discount_type       : discount_type,
                    discount_value      : discount_value,
                    discount_from_date  : discount_from_date,
                    discount_to_date    : discount_to_date,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        showDiscountList();
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

        deleteDiscount :  function( event, discount_id ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

             var deleteConfim = confirm("Do you want to delete this discount");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/deleteDiscount",
                data: {
                    discount_id     : discount_id,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON( response );
                    if(response.status == "success") {
                        alert(response.message);
                         showDiscountList();
                    } else if( response.status == "error") {
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

        showTestimonial: function() {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/viewAllTestimonial",
                data: {
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    $("#testimonialList").html( response );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        addTestomonialForm: function( event ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/createTestimonialForm",
                data: {
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    $("#popupForAll").html( response );
                    _projects.openDialog({"title" : "Add testimonial Form"});
                    _utils.setAsDateFields( {dateField : "testimonial_date"} );
                    //_utils.setAsDateFields( {dateField : "discount_to_date"} );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        createTestimonialValidate : function() {
            var validator = $( "#create_testimonial_contractor_form" ).validate(
                {
                    rules: {
                        testimonial_summary : {
                            required: true
                        },
                        testimonial_rating : {
                            required: true
                        },
                        testimonial_customer_name : {
                            required: true
                        },
                        testimonial_date: {
                            required: true
                        }
                    },
                    messages: {
                        testimonial_summary : {
                            required : "Please provide testimonial summary"
                        },
                        testimonial_rating : {
                            required: "Please provide testimonial rating"
                        },
                        testimonial_customer_name : {
                            required: "Please provide testimonial customer name"
                        },
                        testimonial_date: {
                            required: "Please provide testimonial date"
                        }
                    }
                }
            ).form();

            if(validator) {
                _contractors.createTestimonialSubmit();
            }
        },

        createTestimonialSubmit: function() {
            var testimonial_summary         = $("#testimonial_summary").val();
            var testimonial_descr           = $("#testimonial_descr").val();
            var testimonial_rating         = $("#testimonial_rating").val();
            var testimonial_customer_name   = $("#testimonial_customer_name").val();
            var testimonial_date            = _utils.toMySqlDateFormat($('#testimonial_date').val());

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/addTestimonial",
                data: {
                    testimonial_summary         : testimonial_summary,
                    testimonial_descr           : testimonial_descr,
                    testimonial_rating         : testimonial_rating,
                    testimonial_customer_name   : testimonial_customer_name,
                    testimonial_date            : testimonial_date,
                    contractor_id               : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractors.showTestimonial();
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

        editTestimonialForm: function( event, testimonial_id ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/editTestimonialForm",
                data: {
                    contractor_id       : _contractors.contractorId,
                    testimonial_id      : testimonial_id
                },
                success: function( response ) {
                    $("#popupForAll").html( response );
                    _projects.openDialog({"title" : "Edit Testimonial Form"});

                    _utils.setAsDateFields( {dateField : "testimonial_date"} );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            }); 
        },

        updateTestimonialValidate: function() {
            var validator = $( "#update_testimonial_contractor_form" ).validate(
                {
                    rules: {
                        testimonial_summary : {
                            required: true
                        },
                        testimonial_rating : {
                            required: true
                        },
                        testimonial_customer_name : {
                            required: true
                        },
                        testimonial_date: {
                            required: true
                        }
                    },
                    messages: {
                        testimonial_summary : {
                            required : "Please provide testimonial summary"
                        },
                        testimonial_rating : {
                            required: "Please provide testimonial rating"
                        },
                        testimonial_customer_name : {
                            required: "Please provide testimonial customer name"
                        },
                        testimonial_date: {
                            required: "Please provide testimonial date"
                        }
                    }
                }
            ).form();

            if(validator) {
                _contractors.updateTestimonialSubmit();
            }
        },

        updateTestimonialSubmit: function() {
            var testimonial_id              = $("#dbTestimonialId").val();
            var testimonial_summary         = $("#testimonial_summary").val();
            var testimonial_descr           = $("#testimonial_descr").val();
            var testimonial_rating         = $("#testimonial_rating").val();
            var testimonial_customer_name   = $("#testimonial_customer_name").val();
            var testimonial_date            = _utils.toMySqlDateFormat($('#testimonial_date').val());

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/updateTestimonial",
                data: {
                    testimonial_id              : testimonial_id,
                    testimonial_summary         : testimonial_summary,
                    testimonial_descr           : testimonial_descr,
                    testimonial_rating         : testimonial_rating,
                    testimonial_customer_name   : testimonial_customer_name,
                    testimonial_date            : testimonial_date,
                    contractor_id               : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractors.showTestimonial();
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

        deleteTestimonial :  function( event, testimonial_id ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

             var deleteConfim = confirm("Do you want to delete this testimonial");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/deleteTestimonial",
                data: {
                    testimonial_id  : testimonial_id,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    response = $.parseJSON( response );
                    if(response.status == "success") {
                        alert(response.message);
                        _contractors.showTestimonial();
                    } else if( response.status == "error") {
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
        }
    }
})();