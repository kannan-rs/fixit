/* Utils JS */
var _utils = (function () {
    'use strict';
    var searchCityStr = null;
    var searchCityList = null;
    //var cityListMap = {};
    var postalCodeByCity = null;
    return {
        state : [],
        postalCodeMap : {},
        moduleId : "",
        getAndSetCountryStatus: function (moduleId) {
            utilObj.moduleId = moduleId;
            $.ajax({
                method: "POST",
                url: "/utils/formUtils/getCountryStatus",
                data: {},
                success: function (response) {
                    response = $.parseJSON(response);
                    var country = [];
                    var i = 0;
                    if (response.status === "success") {
                        utilObj.state = response.state;

                        $("#" + moduleId + " #country").empty();
                        $('#' + moduleId + " #country").append($('<option>', {
                            'value': "",
                            "text": "--Select Country--"
                        }));
                        for (i = 0; i < utilObj.state.length; i += 1) {
                            if (country.indexOf(utilObj.state[i].country) < 0) {
                                country.push(utilObj.state[i].country);
                                $('#' + moduleId + " #country").append($('<option>', {
                                    'value': utilObj.state[i].country,
                                    "text": utilObj.state[i].country
                                }));
                            }

                        }
                    } else {
                        alert(response.message);
                    }
                    utilObj.setCountryStateOfDb(moduleId);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        getPostalCodeList: function (moduleId) {
            var i = 0;
            utilObj.moduleId = moduleId;
            $.ajax({
                method: "POST",
                url: "/utils/formUtils/getPostalCodeList",
                data: {},
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status === "success") {
                        utilObj.postalCodeMap = {};
                        utilObj.postalCode = response.postalCode;
                        $("#" + moduleId + " #zipcode_list").empty();
                        for (i = 0; i < utilObj.postalCode.length; i += 1) {
                            utilObj.postalCodeMap[utilObj.postalCode[i].zipcode] = utilObj.postalCode[i];
                            $('#' + moduleId + " #zipcode_list").append($('<option>', {
                                'value': utilObj.postalCode[i].zipcode,
                                "text": utilObj.postalCode[i].zipcode
                            }));
                        }
                    } else {
                        alert(response.message);
                    }
                    utilObj.setCountryStateOfDb(moduleId);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        getAndSetMatchCity: function (value) {
            var i = 0;
            if(value && value.length >2 && (!searchCityList || value.substr(0,3) !== searchCityStr.substr(0,3))) {
                searchCityStr = value;
                //utilObj.moduleId = moduleId;
                $.ajax({
                    method: "POST",
                    url: "/utils/formUtils/getMatchCityList",
                    data: {
                        cityStr: searchCityStr
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        if (response.status === "success") {
                            searchCityList = {};
                            searchCityList = response.cityList;
                            //$("#" + moduleId + " #city_list").empty();
                            $("#city_list").empty();
                            for (i = 0; i < searchCityList.length; i += 1) {
                                
                                /*if (!cityListMap[searchCityList[i].city]) {
                                    cityListMap[searchCityList[i].city] = new Array();
                                }

                                cityListMap[searchCityList[i].city].push(searchCityList[i]);*/
                                
                                //$('#' + moduleId + " #city_list").append($('<option>', {
                                
                                $("#city_list").append($('<option>', {
                                    'value': searchCityList[i].city,
                                    "text": searchCityList[i].city
                                }));
                            }
                        } else {
                            alert(response.message);
                        }
                        //utilObj.setCountryStateOfDb(moduleId);
                    },
                    error: function (error) {
                        error = error;
                    }
                }).fail(function (failedObj) {
                    fail_error = failedObj;
                });
            }
        },
        setPostalCodeDetails: function () {
            var postalCode = $("#" + utilObj.moduleId + " #zipCode").val();
            if (utilObj.postalCodeMap[postalCode]) {
                $("#" + utilObj.moduleId + " #city").val(utilObj.postalCodeMap[postalCode].city);
                $("#" + utilObj.moduleId + " #state").val(utilObj.postalCodeMap[postalCode].state_abbreviation);
            }
        },
        setAddressByCity: function() {
            var city = $("#city").val();
            var i = 0;

            if (city !== "") {
                //utilObj.moduleId = moduleId;
                $.ajax({
                    method: "POST",
                    url: "/utils/formUtils/getPostalDetailsByCity",
                    data: {
                        cityStr: city
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        if (response.status === "success") {
                            postalCodeByCity = {};
                            postalCodeByCity = response.postalDetails;
                            //$("#" + moduleId + " #city_list").empty();

                            $("#zipcode_list").empty();
                            for (i = 0; i < postalCodeByCity.length; i += 1) {
                                $("#zipcode_list").append($('<option>', {
                                    'value': postalCodeByCity[i].zipcode,
                                    "text": postalCodeByCity[i].zipcode
                                }));
                            }
                            if (postalCodeByCity.length == 1) {
                                $("#zipCode").val(postalCodeByCity[0].zipcode);
                                $("#state").val(postalCodeByCity[0].state_abbreviation)
                            }
                        } else {
                            alert(response.message);
                        }
                        //utilObj.setCountryStateOfDb(moduleId);
                    },
                    error: function (error) {
                        error = error;
                    }
                }).fail(function (failedObj) {
                    fail_error = failedObj;
                });   
            }
        },
        populateState: function (country, moduleId) {
            var i = 0;
            $('#' + moduleId + " #state").html("");

            $('#' + moduleId + " #state").append($('<option>', {
                'value': "",
                "text": "--Select State--"
            }));

            for (i = 0; i < utilObj.state.length; i += 1) {
                if (utilObj.state[i].country === country) {
                    $('#' + moduleId + " #state").append($('<option>', {
                        'value': utilObj.state[i].abbreviation,
                        "text": utilObj.state[i].name,
                        class: utilObj.state[i].country
                    }));
                }
            }
        },
        setCountryStateOfDb: function (moduleId) {
            var country = $("#countryDbVal").val();
            var state = $("#stateDbVal").val();

            if (!country) {
                country = $($("#" + moduleId + " #country").children()[1]).val();
            }

            $("#" + moduleId + " #country").val(country).trigger("change");

            if (state) {
                setTimeout(function () {$("#" + moduleId + " #state").val(state);}, 10);
            }
        },
        createContractorOptionsList: function (contractors) {
            var excludeList = !contractors.excludeList ? [] : contractors.excludeList;
            var css = {
                selectedList: {li: "ui-state-highlight", symbol: "ui-icon ui-icon-minus"},
                searchList: {li: "ui-state-default", symbol: "ui-icon ui-icon-plus"},
                ownerList: {li: "ui-state-default", symbol: ""}
            };
            var list = contractors.list;
            var type = contractors.type;
            var prefixId = contractors.prefixId;
            var selectId = contractors.hasOwnProperty("selectId") ? contractors.selectId : "";
            var valuePrefix = contractors.valuePrefix ? contractors.valuePrefix + "-" : "";
            var i = 0;
            var inputRadio = null;
            var selectedText = null;
            var li = null;

            for (i = 0; i < list.length; i += 1) {
                if (excludeList.indexOf(list[i].id) === -1) {

                    inputRadio = " ";
                    if (type === "ownerList") {
                        selectedText = (selectId && list[i].id === selectId) ? " checked = checked " : "";
                        inputRadio = "<input type=\"radio\" name=\"" + contractors.radioOptionName + "\" value=\"" + valuePrefix + list[i].id + "\" " + selectedText + "/>";
                    }

                    li = "<li class=\"" + css[type].li + "\" id=\"" + prefixId + list[i].id + "\" " + (type !== "ownerList" ? "draggable=\"true\" ondragstart=\"projectObj._projects.drag(event)\"" : "");
                    li += " data-contractorid = " + list[i].id;
                    li += ">";
                    li += "<div>"  + list[i].company + "</div>";
                    li += "<div class=\"company\">" + list[i].city + ", " + list[i].state + "</div>";
                    li += "<span class=\"" + css[type].symbol + " search-action\" ";
                    li += " data-contractorid = " + list[i].id;
                    li += " data-prefixid = " + prefixId;
                    li += ">";
                    li += inputRadio;
                    li += "</span>";
                    li += "</li>";
                    $('#' + contractors.appendTo).append(li);
                }
            }
        },
        createAdjusterOptionsList: function (adjuster) {
            var excludeList = !adjuster.excludeList ? [] : adjuster.excludeList;
            var css = {
                selectedList: {li: "ui-state-highlight", symbol: "ui-icon ui-icon-minus"},
                searchList: {li: "ui-state-default", symbol: "ui-icon ui-icon-plus"},
                ownerList: {li: "ui-state-default", symbol: ""}
            };
            var list = adjuster.list;
            var type = adjuster.type;
            var prefixId = adjuster.prefixId;
            var selectId = adjuster.hasOwnProperty("selectId") ? adjuster.selectId : "";
            var valuePrefix = adjuster.valuePrefix ? adjuster.valuePrefix + "-" : "";
            var i = 0;
            var inputRadio = null;
            var li = null;
            var selectedText = null;

            for (i = 0; i < list.length; i += 1) {
                if (excludeList.indexOf(list[i].id) === -1) {

                    inputRadio = " ";
                    if (type === "ownerList") {
                        selectedText = (selectId && list[i].id === selectId) ? " checked = checked " : "";
                        inputRadio = "<input type=\"radio\" name=\"" + adjuster.radioOptionName + "\" value=\"" + valuePrefix + list[i].id + "\" " + selectedText + "/>";
                    }

                    li = "<li class=\"" + css[type].li + "\" id=\"" + prefixId + list[i].id + "\" " + (type !== "ownerList" ? "draggable=\"true\" ondragstart=\"projectObj._projects.drag(event)\"" : "");
                    li += " data-adjusterid = " + list[i].id;
                    li += ">";
                    li += "<div>" + list[i].company_name + "</div>";
                    li += "<div class=\"company\">" + list[i].city + ", " + list[i].state + "</div>";
                    li += "<span class=\"" + css[type].symbol + " search-action\" ";
                    li += " data-adjusterid = " + list[i].id;
                    li += " data-prefixid = " + prefixId;
                    li += ">";
                    li += inputRadio;
                    li += "</span>";
                    li += "</li>";
                    $('#' + adjuster.appendTo).append(li);
                }
            }
        },

        getCustomerList: function () {
            var customer = $.ajax({
                method: "POST",
                url: "/utils/formUtils/getCustomerList",
                data: {},
                async: false
            }).responseText;
            return customer;
        },

        getAdjusterList: function () {
            var adjuster = $.ajax({
                method: "POST",
                url: "/utils/formUtils/getAdjusterList",
                data: {},
                async: false
            }).responseText;
            return adjuster;
        },
        setCustomerDataList: function () {
            var response = this.getCustomerList();
            var responseObj = $.parseJSON(response);
            var customer = [];
            if (responseObj.status === "success") {
                customer = responseObj.customer;
            }

            var searchList = {
                list: customer,
                excludeList: [],
                appendTo: "customerNameList",
                type: "searchList",
                functionName: "projectObj._projects.setCustomerId",
                searchBoxId: "searchCustomerName",
                dbEntryId: "customer_id"
            };

            this.setSearchList(searchList);
        },
        setAdjusterDataList: function () {
            var response = this.getAdjusterList();
            var responseObj = $.parseJSON(response);
            var adjuster = [];
            if (responseObj.status === "success") {
                adjuster = responseObj.adjuster;
            }
            var searchList = {
                list: adjuster,
                excludeList: [],
                appendTo: "adjusterNameList",
                type: "searchList",
                functionName: "projectObj._projects.setAdjusterId",
                searchBoxId: "searchAdjusterName",
                dbEntryId: "adjuster_id"
            };

            this.setSearchList(searchList);
        },
        setSearchList: function (searchList) {
            var excludeList = !searchList.excludeList ? [] : searchList.excludeList;
            var css = {
                searchList: {li: "ui-state-default"}
            };
            var list = searchList.list;
            var type = searchList.type;
            var appendTo = searchList.appendTo;
            var functionName = searchList.functionName;

            var dBVal = $("#" + searchList.dbEntryId).val();
            var i = 0;
            var clickParamsObj = null;
            var clickParams = null;
            var li = null;

            for (i = 0; i < list.length; i += 1) {
                if (excludeList.indexOf(list[i].id) === -1) {
                    clickParamsObj = {
                        searchBoxId: appendTo,
                        first_name: list[i].first_name,
                        last_name: list[i].last_name,
                        searchId: list[i].sno,
                        email: list[i].email
                    };
                    if (dBVal === list[i].sno) {
                        $("#" + searchList.searchBoxId).val(list[i].first_name + " " + list[i].last_name);
                    }
                    clickParams = JSON.stringify(clickParamsObj);
                    li = "<li class=\"" + css[type].li + "\" id=\"" + list[i].id + "\" onclick='" + functionName + "(event, this," + clickParams + ")'>";
                    li += "<div>" + list[i].first_name + " " + list[i].last_name + "</div>";
                    li += "<div class=\"second\">" + list[i].email + "</div>";
                    li += "</li>";
                    $('#' + appendTo).append(li);
                }
            }
        },
        setAsDateFields: function (options) {
            $("#" + options.dateField).datepicker({
                showAnim: "fadeIn",
                dateFormat: "mm/dd/y"
            });
        },
        /*
            options = {
                fromDateField     : string     <ID for Date Field of starting from field>,
                toDateField        : string     <ID for Date Field of ending with field>,
                numberOfMonths    : Int         <Number of month to show> / Default "3" [optional]
            }
        */
        setAsDateRangeFields: function (options) {
            var numberOfMonths = options.numberOfMonths || 3;
            $(function () {
                $("#" + options.fromDateField).datepicker({
                    defaultDate: " + 1w",
                    changeMonth: true,
                    numberOfMonths: numberOfMonths,
                    showAnim: "fadeIn",
                    dateFormat: "mm/dd/y",
                    onClose: function (selectedDate) {
                        $("#" + options.toDateField).datepicker("option", "minDate", selectedDate);
                    }
                });
                $("#" + options.toDateField).datepicker({
                    defaultDate: " + 1w",
                    changeMonth: true,
                    numberOfMonths: numberOfMonths,
                    showAnim: "fadeIn",
                    dateFormat: "mm/dd/y",

                    onClose: function (selectedDate) {
                        $("#" + options.fromDateField).datepicker("option", "maxDate", selectedDate);
                    }
                });
            });
        },
        /*
            Convert from "mm/dd/yy" to "yyyy-mm-dd"
        */
        toMySqlDateFormat: function (dateStr) {
            var dateStrArr = dateStr.split("/");
            var month = dateStrArr[0];
            var date = dateStrArr[1];
            var year = "20" + dateStrArr[2];
            return (year + "-" + month + "-" + date);
        },
        /*
            Convert from "yyyy-mm-dd" to "mm/dd/yy"
        */
        toDisplayDateFormat: function (dateStr) {
            var dateStrArr = dateStr.split("-");
            var year = dateStrArr[0].substr(2, 2);
            var month = dateStrArr[1];
            var date = dateStrArr[2];
            return (month + "/" + date + "/" + year);
        },
        /*
            Number display formatting. Adding "," to seperate numbers
            US         : 4,231,546.44
        */
        toDisplayNumberFormat: function (number) {
            return number.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        setStatus: function (statusDD, statusDbVal) {
            statusDbVal = typeof(statusDbVal) !== "undefined" ? statusDbVal : "";
            var status = $("#" + statusDbVal).val();
            status = (typeof(status) !== "undefined" && status !== "") ? status.toLocaleLowerCase() : "";
            if (typeof(status) !== "undefined" && status !== "" && $("#" + statusDD).length && $("#" + statusDD + " option[value='" + status + "']").length) {
                $("#" + statusDD).val(status);
            } else {
                $("#" + statusDD).val("active");
            }
        },
        setIssueStatus: function (statusDD, statusDbVal) {
            statusDbVal = typeof(statusDbVal) !== "undefined" ? statusDbVal : "";
            var status = $("#" + statusDbVal).val();
            status = (typeof(status) !== "undefined" && status !== "") ? status.toLocaleLowerCase() : "";
            if (typeof(status) !== "undefined" && status !== "" && $("#" + statusDD).length && $("#" + statusDD + " option[value='" + status + "']").length) {
                $("#" + statusDD).val(status);
            } else {
                $("#" + statusDD).val("open");
            }
        },
        setIssueAssignedTo: function (statusDD, statusDbVal) {
            statusDbVal = typeof(statusDbVal) !== "undefined" ? statusDbVal : "";
            var status = $("#" + statusDbVal).val();
            status = (typeof(status) !== "undefined" && status !== "") ? status.toLocaleLowerCase() : "";
            if (typeof(status) !== "undefined" && status !== "" && $("#" + statusDD).length && $("#" + statusDD + " option[value='" + status + "']").length) {
                $("#" + statusDD).val(status);
            } else {
                $("#" + statusDD).val("customer");
            }
        }
    };
})();

String.prototype.capitalizeFirstLetter = function () {
    "use strict";
    return this.charAt(0).toUpperCase() + this.slice(1);
};