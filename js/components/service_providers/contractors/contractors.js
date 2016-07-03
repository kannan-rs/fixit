var _contractors = (function () {

    var tradeMappedList = {
        parents  : [],
        childs  : {}
    };

    var selected_city_list = {};

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
    };

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
                company : {
                    required : true
                },
                zipCode : {
                    //required: true,
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
            var self = this;
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
                    openAs        : openAs,
                    popupType     : popupType,
                    projectId     : projectId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    formInitialSettings("create", options, response);
                    self.setServiceProviderCitySearch();
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        inputValidate:  function ( options ) {
            var cityError = false;
            var validator = $( "#"+options.formPrefix+"_contractor_form" ).validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation( "", options.formPrefix+"_contractor_form" );
            if(cityError) {
                return false;
            }

            if(validator) {
                _contractors.createUpdateSubmit( options );
                return true;
                
            }

            return false;
        },

        createUpdateSubmit : function ( options ) {
            var openAs     = options.openAs;
            var popupType  = options.popupType;
            var submitURL  = options.submitURL;
            var formPrefix = options.formPrefix;

            var fileUpload = document.getElementById("contractorLogo");
    
            if (typeof (fileUpload.files) != "undefined") {
                var file = fileUpload.files[0];
                if( file ) {
                    var type = file.type.split("/")[1];
                    var allowed_type = ["jpg", "jpeg", "bmp", "gif", "png"];

                    if( allowed_type.indexOf(type) != -1 )  {
                        var size = parseFloat(file.size / 1024).toFixed(2);
                        if(size > 100 ) {
                            alert("Image size is '"+ size + "KB'. Allowed size is less that 200KB for logo image.");
                            return false;
                        }
                    }
                    else {
                        alert( "Allowed file types are '"+ allowed_type.join(", ")+"'. Please choose a file from mentioned file format");
                        return false;
                    }
                }
            }

            var city_id_list = [];
            var city_name_list = [];
            for( var city_id in selected_city_list ) {
                city_id_list.push( city_id );
                city_name_list.push( selected_city_list[city_id].name );
            }

            $("#service_cities").val(city_id_list.join(","));
            $("#service_cities_name").val(city_name_list.join(","));
            
            $.ajax({
                url: "/service_providers/contractors/"+submitURL,
                type: "POST",
                data:  new FormData($("#"+formPrefix+"_contractor_form")[0]), //new FormData(this),
                contentType: false,         
                cache: false,
                processData:false,
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    
                    response = $.parseJSON(response);

                    
                    if(response.status.toLowerCase() == "success") {
                        if( formPrefix == "update") {
                            $(".ui-button").trigger("click");
                            alert(response.message);
                            _contractors.viewOne(response.updatedId);
                        } else {
                             _contractors.viewOne(response.insertedId, openAs, popupType);
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
            var self = this;
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
                    self.setServiceProviderCitySearch();
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        setServiceProviderCitySearch : function () {
            var self = this;
            prePopulate = [];

            var city_id_list = $("#service_cities").val().split(",");
            var city_name_list = $("#service_cities_name").val().split(",");

            for( var c_ix = 0; c_ix < city_id_list.length; c_ix++) {
                if( city_id_list[c_ix] != "") {
                    prePopulate.push({ id : city_id_list[c_ix], name : city_name_list[c_ix]});
                    selected_city_list[city_id_list[c_ix]] = { id : city_id_list[c_ix], name : city_name_list[c_ix]};
                }
            }

            $("#service_cities").tokenInput("/utils/formUtils/getCities",
                { 
                    theme: "facebook",
                    minChars : 3,
                    hintText : "Type in 3 character of city",
                    prePopulate : prePopulate,
                    onAdd : function( value) {
                        self.service_area_city_add( value );
                    },
                    onDelete : function( value) {
                        self.service_area_city_remove( value );
                    }
                }
            );
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

        deleteContractorLogo: function() {
            var deleteConfim = confirm("Do you want to delete this service provider company logo");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/contractors/deleteContractorLogo",
                data: {
                    contractorId: _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        _contractors.viewOne( _contractors.contractorId );
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
                emailId         : emailId,
                role_disp_name  : 'SERVICE_PROVIDER_ADMIN',
                assignment      : 'not assigned'
            }

            var response = _utils.getFromUsersList( requestParams );

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
                alert(responseObj.message);
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

        addOrUpdateImage : function() {

        },

        service_area_city_add : function( selected_city ) {
            if( !selected_city_list[selected_city.id] ) {
                selected_city_list[selected_city.id] = selected_city;
            }
        },

        service_area_city_remove : function ( selected_city ) {
            if( selected_city_list[selected_city.id] ) {
                delete(selected_city_list[selected_city.id]);
            }
        }
    }
})();