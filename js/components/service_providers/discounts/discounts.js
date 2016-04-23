var _contractor_discounts = (function () {

    var tradeMappedList = {
        parents  : [],
        childs  : {}
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

    function viewAll() {
        $.ajax({
            method: "POST",
            url: "/service_providers/discounts/viewAll",
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

    function populateMainTrade( ddId ) {
        var htmlContent = "<option value=\"0\">-- Select Main Trade --</option>";
        if(tradeMappedList.parents.length) {
            for(var i = 0; i < tradeMappedList.parents.length; i++) {
                var trade = tradeMappedList.parents[i];
                htmlContent += "<option value='"+trade.trade_id_from_master_list+"'>"+trade.trade_name+"</option>";
            }
        }

        if( !ddId || ddId == "") {
            ddId = "discount_for_main_trade";
        }
        $("#"+ddId ).html(htmlContent);
    }

    function _populateSubTrade( mainTradeId, ddId ) {
        var htmlContent = "<option value=\"0\">-- Select Sub Trade --</option>";
        if(mainTradeId && mainTradeId != "0" && tradeMappedList.childs[mainTradeId] && tradeMappedList.childs[mainTradeId].length) {
            for(var i = 0; i < tradeMappedList.childs[mainTradeId].length; i++) {
                var trade = tradeMappedList.childs[mainTradeId][i];
                htmlContent += "<option value='"+trade.trade_id_from_master_list+"'>"+trade.trade_name+"</option>";
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
        /*setPrefContact: function() {
            var prefContact    = $("#prefContactDb").length ? $("#prefContactDb").val().split(",") : "";

            $("input[name=prefContact]").each(function() {
                if(prefContact.indexOf(this.value) >= 0) {
                    $(this).prop("checked", true);
                }
            });
        },*/

        initialPage: function() {
            getTradeList();
            viewAll();
            populateMainTrade();
        },

        populateSubTrade: function( mainTradeId, ddId ) {
            _populateSubTrade( mainTradeId, ddId );
        },

        show_discount_by_filter: function() {
            var selected_main_trade = $("#discount_for_main_trade").val();
            var selected_sub_trade  = $("#discount_for_sub_trade").val();

            var selector = ".oneDiscount";

            $(".oneDiscount").hide();

            if(selected_main_trade != "0") {
                selector = "[data-main-trade-id="+selected_main_trade+"]";
                if(selected_sub_trade != "0") {
                    selector += "[data-sub-trade-id="+selected_sub_trade+"]";
                }
            }
            $(selector).show();

        },

        createForm: function(event) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            var main_trade_id   = $("#discount_for_main_trade").val();
            var sub_trade_id    = $("#discount_for_sub_trade").val();
            $.ajax({
                method: "POST",
                url: "/service_providers/discounts/createForm",
                data: {
                    main_trade_id       : main_trade_id,
                    sub_trade_id        : sub_trade_id,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#popupForAll").html( response );
                    _projects.openDialog({"title" : "Add Discount Form"});
                    populateMainTrade("input_discount_for_main_trade");
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

        createValidate : function() {
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
                _contractor_discounts.createSubmit();
            }
        },

        createSubmit: function() {
            var main_trade_id       = $("#input_discount_for_main_trade").val();
            var sub_trade_id        = $("#input_discount_for_sub_trade").val();
            var discount_name       = $("#discount_name").val();
            var discount_descr      = $("#discount_descr").val();
            var discount_for_zip    = $("#discount_for_zip").val();
            var discount_type       = $('#create_discount_contractor_form input[name=discount_type]:checked').val();
            var original_value      = $("#original_value").val();
            var discount_value      = $("#discount_value").val();
            var discount_from_date  = _utils.toMySqlDateFormat($("#discount_from_date").val());
            var discount_to_date    = _utils.toMySqlDateFormat($("#discount_to_date").val());

            $.ajax({
                method: "POST",
                url: "/service_providers/discounts/add",
                data: {
                    main_trade_id       : main_trade_id,
                    sub_trade_id        : sub_trade_id,
                    discount_name       : discount_name,
                    discount_descr      : discount_descr,
                    discount_for_zip    : discount_for_zip,
                    discount_type       : discount_type,
                    original_value      : original_value,
                    discount_value      : discount_value,
                    discount_from_date  : discount_from_date,
                    discount_to_date    : discount_to_date,

                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        viewAll();
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

        editForm: function( event, discountId ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            //var main_trade_id   = $("#discount_for_main_trade").val();
            //var sub_trade_id    = $("#discount_for_sub_trade").val();
            $.ajax({
                method: "POST",
                url: "/service_providers/discounts/editForm",
                data: {
                    //main_trade_id       : main_trade_id,
                    //sub_trade_id        : sub_trade_id,
                    contractor_id       : _contractors.contractorId,
                    discount_id         : discountId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#popupForAll").html( response );
                    _projects.openDialog({"title" : "Edit Discount Form"});
                    populateMainTrade("input_discount_for_main_trade");
                    
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

        updateValidate: function() {
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
                _contractor_discounts.updateSubmit();
            }
        },

        updateSubmit: function() {
            var discount_id         = $("#dbDiscountId").val();
            var main_trade_id       = $("#input_discount_for_main_trade").val();
            var sub_trade_id        = $("#input_discount_for_sub_trade").val();
            var discount_name       = $("#discount_name").val();
            var discount_descr      = $("#discount_descr").val();
            var discount_for_zip    = $("#discount_for_zip").val();
            var discount_type       = $('#update_discount_contractor_form input[name=discount_type]:checked').val();
            var original_value      = $("#original_value").val();
            var discount_value      = $("#discount_value").val();
            var discount_from_date  = _utils.toMySqlDateFormat($("#discount_from_date").val());
            var discount_to_date    = _utils.toMySqlDateFormat($("#discount_to_date").val());

            $.ajax({
                method: "POST",
                url: "/service_providers/discounts/update",
                data: {
                    discount_id         : discount_id,
                    main_trade_id       : main_trade_id,
                    sub_trade_id        : sub_trade_id,
                    discount_name       : discount_name,
                    discount_descr      : discount_descr,
                    discount_for_zip    : discount_for_zip,
                    discount_type       : discount_type,
                    original_value      : original_value,
                    discount_value      : discount_value,
                    discount_from_date  : discount_from_date,
                    discount_to_date    : discount_to_date,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        viewAll();
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

        delete :  function( event, discount_id ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

             var deleteConfim = confirm("Do you want to delete this discount");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/discounts/delete",
                data: {
                    discount_id     : discount_id,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON( response );
                    if(response.status == "success") {
                        alert(response.message);
                         viewAll();
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