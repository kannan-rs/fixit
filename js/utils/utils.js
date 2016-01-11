/* Utils JS */
var _utils = (function () {
    //'use strict';
    var searchCityStr = null;
    var searchCityList = null;
    //var postalCodeByCity = null;
    var _states = null;
    var _countries = null;
    var _customers = [];

    function mapCustomerObjectById( customer ) {
        for( cust in customer) {
            _customers[customer[cust].sno] = customer[cust];
        }
    };

    return {
        //state : [],
        //postalCodeMap : {},
        /*
            To Identify Each form Seperately, This need to be the ID of form for which current operation need to take place
        */
        moduleId : '',
        /*
            Function    : getAndSetCountryStatus
            Description : get country and state from database and set it in localstorage
        */
        getAndSetCountryStatus: function (moduleId) {
            var self = this;
            $( '#'+moduleId+' #city' ).combobox();
            _utils.moduleId = moduleId;

            _states = _states || this.getStatesFromLS();
            _countries = _countries || this.getCountriesFromLS();

            if(!_states || !_countries) {
                $.ajax({
                    method: 'POST',
                    url: '/utils/formUtils/getCountryStatus',
                    data: {},
                     async: false,
                    success: function (response) {
                        response = $.parseJSON(response);
                        var country = [];
                        var i = 0;
                        if (response.status === 'success') {
                            self.setStateToLS(response.state);
                            self.setCountryToLS();
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function (error) {
                        error = error;
                    }
                }).fail(function (failedObj) {
                    fail_error = failedObj;
                });
            }
            self.populateCountryOption(moduleId);
            _utils.setCountryStateOfDb(moduleId);
        },
        setStateToLS : function( states ) {
            if (Storage) {
                localStorage.setItem('states', JSON.stringify(states));
            }
            _states = states;
        },
        setCountryToLS : function () {
            var i;
            if(_states) {
                _countries = [];
                for (i = 0; i < _states.length; i += 1) {
                    if (_countries.indexOf(_states[i].country) < 0) {
                        _countries.push(_states[i].country);
                    }
                }
            }
            if(Storage) {
                 localStorage.setItem('countries', JSON.stringify(_countries));
            }
        },
        getStatesFromLS: function() {
            var states = null;
            if(Storage) {
                states = JSON.parse(localStorage.getItem('states'));
            }
            return states;
        },
        getCountriesFromLS: function() {
            var countries = null;
            if(Storage) {
                countries = JSON.parse(localStorage.getItem('countries'));
            }
            return countries;
        },
        populateCountryOption : function( moduleId ) {
            var i;
            $('#' + moduleId + ' #country').empty();
            $('#' + moduleId + ' #country').append($('<option>', {
                'value': '',
                'text': '--Select Country--'
            }));
            for (i = 0; i < _countries.length; i += 1) {
                if(_countries[i].toLocaleLowerCase() == 'usa') {
                    $('#' + moduleId + ' #country').append($('<option>', {
                        'value': _countries[i],
                        'text': _countries[i]
                    }));
                }
            }
        },
        getAndSetMatchCity: function (value, from) {
            var i = 0;
            var city = null;
            var self = this;
            if(value && value.length > 2 && (!searchCityList || value.substr(0,3) !== searchCityStr.substr(0,3))) {
                searchCityStr = value.substr(0,3);
                searchCityList = this.getCityFromLS(searchCityStr);
                //_utils.moduleId = moduleId;

                if(!searchCityList) {
                    //this.populateCityOption(searchCityList);
                //} else {
                    $.ajax({
                        method: 'POST',
                        url: '/utils/formUtils/getMatchCityList',
                        data: {
                            cityStr: searchCityStr
                        },
                        success: function (response) {
                            response = $.parseJSON(response);
                            if (response.status === 'success') {
                                searchCityList = {};
                                searchCityList = response.cityList;
                                self.setCityToLS(searchCityStr, searchCityList);
                                
                                self.populateCityOption(searchCityList);
                            } else {
                                alert(response.message);
                            }
                            //_utils.setCountryStateOfDb(moduleId);
                        },
                        error: function (error) {
                            error = error;
                        }
                    }).fail(function (failedObj) {
                        fail_error = failedObj;
                    });
                }
                this.populateCityOption(searchCityList, from);
            }
        },
        populateCityOption: function(searchCityList ,from) {
            var i;
            var uniqueCity = [];
            //$('#' + moduleId + ' #city_list').empty();

            $('#city').empty();
            if(searchCityList) {
                for (i = 0; i < searchCityList.length; i += 1) {
                    //$('#' + moduleId + ' #city_list').append($('<option>', {
                    if(uniqueCity.indexOf(searchCityList[i].city) < 0) {
                        uniqueCity.push(searchCityList[i].city);
                        $('#city').append($('<option>', {
                            'value': searchCityList[i].city,
                            'text': searchCityList[i].city
                        }));
                    }
                }
            }
            
            if(!from || from != 'edit') {
                $("a[title='Show All Items']").trigger('click'); 
                $('#city_jqDD').focus();
            }
        },
        getCityFromLS: function(cityStr) {
            var cityList = null;
            if (Storage && cityStr) {
                cityStr = cityStr.toLocaleLowerCase();
                var city = JSON.parse(localStorage.getItem('city'));
                cityList = city && city[cityStr.substr(0,3)] ? city[cityStr.substr(0,3)] : null;
            }
             return cityList;
        },
        setCityToLS: function(cityStr, cityList) {
            if (Storage && cityStr) {
                cityStr = cityStr.toLocaleLowerCase();
                var city = JSON.parse(localStorage.getItem('city')) || {};
                city[cityStr.substr(0,3)] = city ?  cityList : null;
                localStorage.setItem('city', JSON.stringify(city));
            }
        },
        /*setPostalCodeDetails: function () {
            var postalCode = $('#' + _utils.moduleId + ' #zipCode').val();
            if (_utils.postalCodeMap[postalCode]) {
                $('#' + _utils.moduleId + ' #city').val(_utils.postalCodeMap[postalCode].city);
                $('#' + _utils.moduleId + ' #state').val(_utils.postalCodeMap[postalCode].state_abbreviation);
            }
        },*/
        setAddressByCity: function() {
            var city = $('#city').val();
            var postalList = null;
            var i = 0;

            if (city !== '') {
                postalList = this.getPostalDetailsByCity( city );
            }

            this.populateStateOption({'postalList' : postalList, 'country' : $('#country').val()});
            this.populateZipCodeOption({'postalList' : postalList, 'country' : $('#country').val()});
        },
        getPostalDetailsByCity: function( city ) {
            var i;
            var cityList = null;
            var subCityList = null;
            if(city) {
                city = city.toLocaleLowerCase();
                cityList = this.getCityFromLS(city);
                if( cityList ) {
                    subCityList = [];
                    for( i = 0; i < cityList.length; i++) {
                        if(cityList[i].city.toLocaleLowerCase() == city) {
                            subCityList.push(cityList[i]);
                        }
                    }
                }
            }
            return subCityList;
        },
        getZipCodeFromCity : function ( postalList ) {
            var i = 0;
            var zipList = null;
            if(postalList && $.isArray(postalList)) {
                zipList = [];
                for( i=0; i < postalList.length; i++) {
                    zipList.push( postalList[i].zipcode);
                }
            }
            return zipList ? $.unique(zipList) : [];
        },
        getStateAbrFromCity : function ( postalList ) {
            var i = 0;
            var stateAbr = null;
            if(postalList && $.isArray(postalList)) {
                stateAbr = [];
                for( i=0; i < postalList.length; i++) {
                    stateAbr.push( postalList[i].state_abbreviation);
                }
            }
            return stateAbr ? $.unique(stateAbr) : [];
        },
        populateStateOption: function ( options ) {
            var i = 0;
            var country = options.country;
            var moduleId = options.moduleId;
            var postalList = options.postalList;
            var stateAbrList = this.getStateAbrFromCity( postalList );

            /*$('#' + moduleId + ' #state').html('');
            $('#' + moduleId + ' #state').append($('<option>', {
                'value': '',
                'text': '--Select State--'
            }));*/
            $('#state').html('');
            $('#state').append($('<option>', {
                'value': '',
                'text': '--Select State--'
            }));

            for (i = 0; i < _states.length; i += 1) {
                if (_states[i].country === country && (!stateAbrList || stateAbrList.indexOf(_states[i].abbreviation) != -1)) {
                    //$('#' + moduleId + ' #state').append($('<option>', {
                    $('#state').append($('<option>', {
                        'value': _states[i].abbreviation,
                        'text': _states[i].name,
                        class: country
                    }));
                }
            }

            if($.isArray(stateAbrList) && stateAbrList.length == 1) {
                $('#state').val(stateAbrList[0]);
                $('#state').focusout();
            }
        },
        populateZipCodeOption: function ( options ) {
            var i = 0;
            var country = options.country;
            var moduleId = options.moduleId;
            var postalList = options.postalList;
            var zipList = this.getZipCodeFromCity( postalList );

            $('#zipCode').html('');
            $('#zipCode').append($('<option>', {
                'value': '',
                'text': '--Select Zipcode--'
            }));

            if ($.isArray(zipList)) {
                for (i = 0; i < zipList.length; i += 1) {
                    $('#zipCode').append($('<option>', {
                        'value': zipList[i],
                        'text': zipList[i]
                    }));
                }
                
                if (zipList.length == 1) {
                    $('#zipCode').val(zipList[0]);
                    $('#zipCode').focusout();
                }
            }
        },
        setCountryStateOfDb: function (moduleId) {
            var country = $('#countryDbVal').val();
            var state = $('#stateDbVal').val();

            if (!country) {
                country = $($('#' + moduleId + ' #country').children()[1]).val();
            }

            $('#' + moduleId + ' #country').val(country).trigger('change');

            if (state) {
                setTimeout(function () {$('#' + moduleId + ' #state').val(state);}, 10);
            }
        },

        /*
            Create Option list dropdown for Customer / Adjuster / Contractor
            Based on options sent to this functions
                1. Create LI based on the options sent to this function
                2. Show [+] / [-] options based on options
                3. Allow Drag and Drop based on option
                4. Click [+] to add, if [+] is shown
                5. Click [-] to remove, if [-] is shown
                6. Click to select and add its value to the text box and add it ID to the hidden value for further usage

                options {
                    list            : <List of customer / Contractor / Adjuster>
                    type            : ownerList or Not
                    prefixId        : <LI> ID Prefix
                    dataIdentifier  : What type of data it is (Customer / Contractor / Adjuster )
                    selectId        : For Edit form, Show already selected Owner
                    valuePrefix     : Value Prefix for Radio button, to make it unique
                    dispStrKey      : Database Key for customer/adjuster table, used to show as display string in <li> top (Contractor/Adjuster)
                    radioOptionName : NAME of radio button, when type="ownerList"
                    appendTo        : ID of <UL> where all the created <li> using list loop to be added
                    functionName    : FUNCTION NAME - Function to be called when the user clicked on <li> created to show user name and mail ID
                    excludeList     : <list of customer / contractor / adjuster > from the list provided
                    dbEntryId       : For Edit form, already selected user from database
                    searchBoxId     : ID of input box in which user is providing input for search
                }
        */
        createDropDownOptionsList: function (options) {
            var excludeList = !options.excludeList ? [] : options.excludeList;
            var css = {
                selectedList: {li: "ui-state-highlight", symbol: "ui-icon ui-icon-minus"},
                searchList: {li: "ui-state-default", symbol: "ui-icon ui-icon-plus"},
                ownerList: {li: "ui-state-default", symbol: ""}
            };
            var list = options.list || [] ;
            var type = options.type || "";
            var prefixId = options.prefixId || "";
            var dataIdentifier = options.dataIdentifier || "";
            var selectId = options.selectId || "";
            var valuePrefix = options.valuePrefix ? options.valuePrefix + "-" : "";
            var dispStrKey = options.dispStrKey || "";
            var radioOptionName = options.radioOptionName || "";
            var appendTo = options.appendTo || "";
            var functionName = options.functionName || "";
            var i = 0;
            var inputRadio = null;
            var selectedText = null;
            var li = null;

            var dBVal = $("#" + options.dbEntryId).val();
            var clickParamsObj = null;
            var clickParams = null;

            for (i = 0; i < list.length; i += 1) {
                if (excludeList.indexOf(list[i].id) === -1) {

                    inputRadio = " ";
                    if (type === "ownerList") {
                        selectedText = (selectId && list[i].id === selectId) ? " checked = checked " : "";
                        inputRadio = "<input type=\"radio\" name=\"" + radioOptionName + "\" value=\"" + valuePrefix + list[i].id + "\" " + selectedText + "/>";
                    }

                    
                    if(dataIdentifier == "customer") {
                        clickParamsObj = {
                            searchBoxId: appendTo,
                            first_name: list[i].first_name || "",
                            last_name: list[i].last_name  || "",
                            searchId: list[i].sno,
                            email: list[i].email
                        };
                        if (dBVal === list[i].sno) {
                            $("#" + options.searchBoxId).val(list[i].first_name + " " + list[i].last_name);
                        }
                        clickParams = JSON.stringify(clickParamsObj);
                    }

                     
                    if(dataIdentifier == "customer") {
                        li = "<li class=\"" + css[type].li + "\" id=\"" + list[i].id + "\" onclick='" + functionName + "(event, this," + clickParams + ")'>";
                        li += "<div>" + list[i].first_name + " " + list[i].last_name + "</div>";
                        li += "<div class=\"second\">" + list[i].email + "</div>";
                    } else {
                        li = "<li class=\"" + css[type].li + "\" id=\"" + prefixId + list[i].id + "\" " + 
                            (type !== "ownerList" ? "draggable=\"true\" ondragstart=\"_projects.drag(event)\"" : "");
                        li += " data-"+dataIdentifier+"id = " + list[i].id;
                        li += ">";
                        li += "<div>" + list[i][dispStrKey] + "</div>";
                        li += "<div class=\"company\">" + list[i].city + ", " + list[i].state + "</div>";
                        li += "<span class=\"" + css[type].symbol + " search-action\" ";
                        li += " data-"+dataIdentifier+"id = " + list[i].id;
                        li += " data-prefixid = " + prefixId;
                        li += ">";
                        li += inputRadio;
                        li += "</span>";
                    }
                    li += "</li>";
                    $('#' + appendTo).append(li);
                }
            }
        },

        getCustomerList: function ( data ) {
            data = data && data instanceof Object ? data : {};
            var customer = $.ajax({
                method: "POST",
                url: "/utils/formUtils/getCustomerList",
                data: data,
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
            mapCustomerObjectById(customer);

            var searchList = {
                list: customer,
                excludeList: [],
                appendTo: "customerNameList",
                type: "searchList",
                functionName: "_utils.setCustomerId",
                searchBoxId: "searchCustomerName",
                dbEntryId: "customer_id",
                dataIdentifier    : "customer",
            };

            this.createDropDownOptionsList(searchList);
        },
        setCustomerId: function (event, element, options) {
            $("#searchCustomerName").val(options.first_name + " " + options.last_name);
            $("#customer_id").val(options.searchId);
        },

        showCustomerListInDropDown: function () {
            var customer = $("#searchCustomerName").val();
            var i;
            $(".customer-search-result").show();
            $("#customerNameList").show();

            for (i = 0; i < $("#customerNameList").children().length; i += 1) {
                if ($($("#customerNameList").children()[i]).text().indexOf(customer) > -1) {
                    $($("#customerNameList").children()[i]).show();
                } else {
                    $($("#customerNameList").children()[i]).hide();
                }
            }
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
                functionName: "_projects.setAdjusterId",
                searchBoxId: "searchAdjusterName",
                dbEntryId: "adjuster_id",
                dispStrKey: "company_name",
                dataIdentifier    : "adjuster"
            };

            this.createDropDownOptionsList(searchList);
        },
        setAsDateFields: function (options) {
            $("#" + options.dateField).datepicker({
                showAnim: "fadeIn",
                dateFormat: DATE_INPUT_FORMAT
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
            var numberOfMonths = options.numberOfMonths || 2;
            $(function () {
                $("#" + options.fromDateField).datepicker({
                    defaultDate: " + 1w",
                    changeMonth: true,
                    numberOfMonths: numberOfMonths,
                    showAnim: "fadeIn",
                    dateFormat: DATE_INPUT_FORMAT,
                    onClose: function (selectedDate) {
                        $("#" + options.toDateField).datepicker("option", "minDate", selectedDate);
                    }
                });
                $("#" + options.toDateField).datepicker({
                    defaultDate: " + 1w",
                    changeMonth: true,
                    numberOfMonths: numberOfMonths,
                    showAnim: "fadeIn",
                    dateFormat: DATE_INPUT_FORMAT,

                    onClose: function (selectedDate) {
                        $("#" + options.fromDateField).datepicker("option", "maxDate", selectedDate);
                    }
                });
            });
        },
        /*
            Convert from "mm/dd/yyyy" to "yyyy-mm-dd"
        */
        toMySqlDateFormat: function (dateStr) {
            var dateStrArr  = dateStr.split("/");
            var month       = dateStrArr[0];
            var date        = dateStrArr[1];
            var year        = dateStrArr[2];
            return (year + "-" + month + "-" + date);
        },
        /*
            Convert from "yyyy-mm-dd" to "mm/dd/yyyy"
        */
        toDisplayDateFormat: function (dateStr) {
            var dateStrArr = dateStr.split("-");
            var year = dateStrArr[0];
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
        },
        cityFormValidation: function() {
            var error = false;
            if(!$("#city").val() && $("#city").attr("required")) {
                $($("#city_jqDD").addClass("form-error").next()).addClass("form-error");
                $("#cityError").addClass("form-error").css({"display" : "block"}).text("Please provide a valid city. Enter first three characters of city to search and select city");
                error = true;
            }
            return error;
        },
        setAddressEditVal: function() {

        },
        validateCustomerByName: function(customer_id, customer_name) {
            if(!_customers[customer_id] || customer_name != _customers[customer_id].first_name+" "+_customers[customer_id].last_name) {
                return false;
            }
            return true;
        }
    };
})();

String.prototype.capitalizeFirstLetter = function () {
    "use strict";
    return this.charAt(0).toUpperCase() + this.slice(1);
};