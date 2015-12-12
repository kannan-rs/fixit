var _contractors = (function () {
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
                url: "/projects/contractors/createForm",
                data: {
                    openAs         : openAs,
                    popupType     : popupType,
                    projectId     : projectId
                },
                success: function( response ) {
                    if(openAs == "popup") {
                        $("#popupForAll"+popupType).html( response );
                        _projects.openDialog({"title" : "Add Contractor"}, popupType);
                    } else{
                        $("#contractor_content").html(response);
                    }
                    //_projects.setMandatoryFields();
                    _utils.setStatus("status", "statusDb");
                    _utils.getAndSetCountryStatus("create_contractor_form");
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
            var bbb                     = $(idPrefix+"#bbb").val();
            var status                     = $(idPrefix+"#status").val();
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

            $(idPrefix+"input[name=prefContact]:checked").each(
                function() {
                    prefContact += prefContact != "" ? (","+this.value) : this.value;
                }
            );

            $.ajax({
                method: "POST",
                url: "/projects/contractors/add",
                data: {
                    name                     : name,
                    company                 : company,
                    type                     : type,
                    license                 : license,
                    bbb                     : bbb,
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
                    popupType                 : popupType
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
                url: "/projects/contractors/viewOne",
                data: {
                    contractorId     : _contractors.contractorId,
                    openAs             : openAs,
                    popupType         : popupType
                },
                success: function( response ) {
                    if( openAs && openAs == "popup") {
                        $("#popupForAll"+popupType).html( response );
                        _projects.openDialog({"title" : "Contractor Details"}, popupType);
                        _projects.updateContractorSelectionList();
                        _projects.setContractorDetails();
                    } else {
                        $("#contractor_content").html(response);
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
                url: "/projects/contractors/viewAll",
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
                url: "/projects/contractors/editForm",
                data: {
                    'contractorId' : _contractors.contractorId,
                    'openAs'         : openAs,
                    'popupType'     : popupType
                    
                },
                success: function( response ) {
                    $("#popupForAll"+popupType).html(response);
                    _projects.openDialog({"title" : "Edit Contractor"}, popupType);
                    _contractors.setPrefContact();
                    _utils.setStatus("status", "statusDb");
                    _utils.getAndSetCountryStatus("update_contractor_form");
                    _utils.setAddressByCity();
                    _utils.getAndSetMatchCity($("#city_jqDD").val(), "edit");

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
            var idPrefix                 = "#update_contractor_form ";
            var contractorId            = $(idPrefix+"#contractorId").val();
            //var name                     = $(idPrefix+"#name").val();
            var company                 = $(idPrefix+"#company").val();
            var type                     = $(idPrefix+"#type").val();
            var license                 = $(idPrefix+"#license").val();
            var bbb                     = $(idPrefix+"#bbb").val();
            var status                     = $(idPrefix+"#status").val();
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
            var serviceZip                 = $(idPrefix+"#serviceZip").val();

            $(idPrefix+"input[name=prefContact]:checked").each(
                function() {
                    prefContact += prefContact != "" ? (","+this.value) : this.value;
                }
            );

            $.ajax({
                method: "POST",
                url: "/projects/contractors/update",
                data: {
                    contractorId             : contractorId,
                    //name                     : name,
                    company                 : company,
                    type                     : type,
                    license                 : license,
                    bbb                     : bbb,
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
                    serviceZip                 : serviceZip
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
                url: "/projects/contractors/deleteRecord",
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
            console.log(params);
        }
    }
})();