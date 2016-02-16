var _contractor_trades = (function () {
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

	return {
		showTradeList: function() {
            getTradeList( true );
        },

        addNewMainTrendsForm : function() {
           $.ajax({
                method: "POST",
                url: "/service_providers/trades/createFormMainTrades",
                data: {
                    contractorId : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
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
                _contractor_trades.createTradeSubmit();
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
                url: "/service_providers/trades/addTrades",
                data: {
                    main_trade_id       : main_trade_id,
                    sub_trades_id       : sub_trades_id,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractor_trades.showTradeList();
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
                url: "/service_providers/trades/updateFormMainTrades",
                data: {
                    trade_id        : mainTradeId,
                    contractorId    : _contractors.contractorId,
                    displayString   : dispString
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
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
                _contractor_trades.updateTradeSubmit();
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
                url: "/service_providers/trades/updateTrades",
                data: {
                    main_trade_id               : main_trade_id,
                    to_add_sub_trades_id        : to_add_sub_trades_id,
                    to_delete_sub_trade_id      : to_delete_sub_trade_id,
                    contractor_id               : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractor_trades.showTradeList();
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
                url: "/service_providers/trades/deleteMainTrades",
                data: {
                    trade_id        : trade_id,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON( response );
                    if(response.status == "success") {
                        alert(response.message);
                        _contractor_trades.showTradeList();
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
                url: "/service_providers/trades/createSubTradesForm",
                data: {
                    main_trade_id       : main_trade_id,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
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

        /*createSubTradeValidate : function() {
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
                _contractor_trades.createSubTradeSubmit();
            }
        },*/

        /*createSubTradeSubmit: function() {
            var sub_trade_name  = $("#sub_trade_name").val();
            var main_trade_id   = $("#main_trade_id_db_value").val();

            $.ajax({
                method: "POST",
                url: "/service_providers/trades/addSubTrades",
                data: {
                    main_trade_id       : main_trade_id,
                    sub_trade_name      : sub_trade_name,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractor_trades.showTradeList();
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
        },*/

        editSubTradesForm: function( event, subTradeId, dispString, mainTradeId ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/trades/updateFormSubTrades",
                data: {
                    sub_trade_id    : subTradeId,
                    main_trade_id   : mainTradeId,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
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

        /*updateSubTradeValidate : function() {
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
                _contractor_trades.updateSubTradeSubmit();
            }
        },*/

        /*updateSubTradeSubmit: function() {
            var sub_trade_name      = $("#sub_trade_name").val();
            var sub_trade_id        = $("#sub_trade_id_db_value").val();
            var main_trade_id       = $("#main_trade_id_db_value").val();

            $.ajax({
                method: "POST",
                url: "/service_providers/trades/updateSubTrades",
                data: {
                    sub_trade_name      : sub_trade_name,
                    sub_trade_id        : sub_trade_id,
                    main_trade_id       : main_trade_id,
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractor_trades.showTradeList();
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
        },*/

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
                url: "/service_providers/trades/deleteSubTrades",
                data: {
                    sub_trade_id    : sub_trade_id,
                    main_trade_id   : main_trade_id,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON( response );
                    if(response.status == "success") {
                        alert(response.message);
                        _contractor_trades.showTradeList();
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