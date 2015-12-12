var _partners = (function () {
    "use strict";
    return {
        errorMessage: function () {
            return {
                name: {
                    required: _lang.english.errorMessage.partnerForm.name
                },
                company: {
                    required: _lang.english.errorMessage.partnerForm.company
                },
                type: {
                    required: _lang.english.errorMessage.partnerForm.type
                },
                license: {
                    required: _lang.english.errorMessage.partnerForm.license
                },
                status: {
                    required: _lang.english.errorMessage.partnerForm.status
                },
                addressLine1: {
                    required: _lang.english.errorMessage.partnerForm.addressLine1
                },
                addressLine2: {
                    required: _lang.english.errorMessage.partnerForm.addressLine2
                },
                city: {
                    required: _lang.english.errorMessage.partnerForm.city
                },
                country: {
                    required: _lang.english.errorMessage.partnerForm.country
                },
                state: {
                    required: _lang.english.errorMessage.partnerForm.state
                },
                zipCode: {
                    required: _lang.english.errorMessage.partnerForm.zipCode,
                    minlength: _lang.english.errorMessage.partnerForm.zipCode,
                    maxlength: _lang.english.errorMessage.partnerForm.zipCode,
                    digits: _lang.english.errorMessage.partnerForm.zipCode
                },
                wNumber: {
                    required: _lang.english.errorMessage.partnerForm.wNumber,
                    digits: _lang.english.errorMessage.partnerForm.wNumber
                },
                wEmailId: {
                    required: _lang.english.errorMessage.partnerForm.wEmailId
                },
                pNumber: {
                    required: _lang.english.errorMessage.partnerForm.pNumber,
                    digits: _lang.english.errorMessage.partnerForm.pNumber
                },
                pEmailId: {
                    required: _lang.english.errorMessage.partnerForm.pEmailId
                },
                prefwNumber: {
                    required: _lang.english.errorMessage.partnerForm.prefwNumber
                },
                prefwEmailId: {
                    required: _lang.english.errorMessage.partnerForm.prefwEmailId
                },
                prefmNumber: {
                    required: _lang.english.errorMessage.partnerForm.prefmNumber
                },
                websiteURL: {
                    required: _lang.english.errorMessage.partnerForm.websiteURL
                }
            };
        },

        validationRules: function () {
            return {
                zipCode: {
                    required: true,
                   /* minlength: 5,
                    maxlength: 5,
                    digits: true*/
                },
                wNumber: {
                    digits: true
                },
                pNumber: {
                    digits: true
                }
            };
        },

        createForm: function (event, options) {
            var fail_error = null;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            var openAs = (options && options.openAs) ? options.openAs : "";
            var popupType = (options && options.popupType) ? options.popupType : "";
            var projectId = (options && options.projectId) ? options.projectId : "";

            if (!openAs) {
                _projects.clearRest();
                _projects.toggleAccordiance("partners", "new");
            }

            $.ajax({
                method: "POST",
                url: "/projects/partners/createForm",
                data: {
                    openAs: openAs,
                    popupType: popupType,
                    projectId: projectId
                },
                success: function (response) {
                    if (openAs === "popup") {
                        $("#popupForAll" + popupType).html(response);
                        _projects.openDialog({title: "Add Partner"}, popupType);
                    } else {
                        $("#partner_content").html(response);
                    }
                    //_projects.setMandatoryFields();
                    _utils.setStatus("status", "statusDb");
                    _utils.getAndSetCountryStatus("create_partner_form");
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createValidate: function (openAs, popupType) {
            var cityError = false;
            var validator = $("#create_partner_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation();
            if (cityError) {
                return false;
            }

            if (validator) {
                _partners.createSubmit(openAs, popupType);
            }
        },

        createSubmit: function (openAs, popupType) {
            var fail_error = null;
            var idPrefix = "#create_partner_form ";
            //var name = $(idPrefix + "#name").val();
            var company = $(idPrefix + "#company").val();
            var type = $(idPrefix + "#type").val();
            var license = $(idPrefix + "#license").val();
            var status = $(idPrefix + "#status").val();
            var addressLine1 = $(idPrefix + "#addressLine1").val();
            var addressLine2 = $(idPrefix + "#addressLine2").val();
            var city = $(idPrefix + "#city").val();
            var state = $(idPrefix + "#state").val();
            var country = $(idPrefix + "#country").val();
            var zipCode = $(idPrefix + "#zipCode").val();
            var wNumber = $(idPrefix + "#wNumber").val();
            var wEmailId = $(idPrefix + "#wEmailId").val();
            var pNumber = $(idPrefix + "#pNumber").val();
            var pEmailId = $(idPrefix + "#pEmailId").val();
            var prefContact = "";
            var websiteURL = $(idPrefix + "#websiteURL").val();

            $(idPrefix + "input[name=prefContact]:checked").each(
                function () {
                    prefContact += prefContact !== "" ? ("," + this.value) : this.value;
                }
           );

            $.ajax({
                method: "POST",
                url: "/projects/partners/add",
                data: {
                    //name: name,
                    company: company,
                    type: type,
                    license: license,
                    status: status,
                    addressLine1: addressLine1,
                    addressLine2: addressLine2,
                    city: city,
                    state: state,
                    country: country,
                    zipCode: zipCode,
                    wNumber: wNumber,
                    wEmailId: wEmailId,
                    pNumber: pNumber,
                    pEmailId: pEmailId,
                    prefContact: prefContact,
                    websiteURL: websiteURL,
                    openAs: openAs,
                    popupType: popupType
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _partners.viewOne(response.insertedId, openAs, popupType);
                    } else if (response.status.toLowerCase() === "error") {
                        alert(response.message);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        viewOne: function (partnerId, openAs, popupType) {
            var fail_error = null;
            this.partnerId = partnerId;
            popupType = popupType || "";
            if (!openAs || openAs !== "popup") {
                _projects.clearRest();
                _projects.toggleAccordiance("partners", "viewOne");
            }

            $.ajax({
                method: "POST",
                url: "/projects/partners/viewOne",
                data: {
                    partnerId: _partners.partnerId,
                    openAs: openAs,
                    popupType: popupType
                },
                success: function (response) {
                    if (openAs && openAs === "popup") {
                        $("#popupForAll" + popupType).html(response);
                        _projects.openDialog({title: "Partner Details"}, popupType);
                        _projects.updatePartnerSelectionList();
                        _projects.setPartnerDetails();
                    } else {
                        $("#partner_content").html(response);
                    }
                    _partners.setPrefContact();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        viewAll: function () {
            var fail_error = null;
            _projects.clearRest();
            _projects.toggleAccordiance("partners", "viewAll");

            $.ajax({
                method: "POST",
                url: "/projects/partners/viewAll",
                data: {},
                success: function (response) {
                    $("#partner_content").html(response);
                    _partners.showPartnersList();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        editForm: function (options) {
            var fail_error = null;
            var openAs = (options && options.openAs) ? options.openAs: "";
            var popupType = (options && options.popupType) ? options.popupType: "";

            $.ajax({
                method: "POST",
                url: "/projects/partners/editForm",
                data: {
                    partnerId : _partners.partnerId,
                    openAs: openAs,
                    popupType: popupType
                },
                success: function (response) {
                    $("#popupForAll" + popupType).html(response);
                    _projects.openDialog({title: "Edit Partner"}, popupType);
                    _partners.setPrefContact();
                    _utils.setStatus("status", "statusDb");
                    _utils.getAndSetCountryStatus("update_partner_form");
                    _utils.setAddressByCity();
                    _utils.getAndSetMatchCity($("#city_jqDD").val(), "edit");

                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        updateValidate: function () {
            var cityError = false;
            var validator = $("#update_partner_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation();
            if (cityError) {
                return false;
            }

            if (validator) {
                _partners.updateSubmit();
            }
        },

        updateSubmit: function () {
            var fail_error = null;
            var idPrefix = "#update_partner_form ";
            var partnerId = $(idPrefix + "#partnerId").val();
            //var name = $(idPrefix + "#name").val();
            var company = $(idPrefix + "#company").val();
            var type = $(idPrefix + "#type").val();
            var license = $(idPrefix + "#license").val();
            var status = $(idPrefix + "#status").val();
            var addressLine1 = $(idPrefix + "#addressLine1").val();
            var addressLine2 = $(idPrefix + "#addressLine2").val();
            var city = $(idPrefix + "#city").val();
            var state = $(idPrefix + "#state").val();
            var country = $(idPrefix + "#country").val();
            var zipCode = $(idPrefix + "#zipCode").val();
            var wNumber = $(idPrefix + "#wNumber").val();
            var wEmailId = $(idPrefix + "#wEmailId").val();
            var pNumber = $(idPrefix + "#pNumber").val();
            var pEmailId = $(idPrefix + "#pEmailId").val();
            var prefContact = "";
            var websiteURL = $(idPrefix + "#websiteURL").val();

            $(idPrefix + "input[name=prefContact]:checked").each(
                function () {
                    prefContact += prefContact !== "" ? ("," + this.value) : this.value;
                }
           );

            $.ajax({
                method: "POST",
                url: "/projects/partners/update",
                data: {
                    partnerId: partnerId,
                    //name: name,
                    company: company,
                    type: type,
                    license: license,
                    status: status,
                    addressLine1: addressLine1,
                    addressLine2: addressLine2,
                    city: city,
                    state: state,
                    country: country,
                    zipCode: zipCode,
                    wNumber: wNumber,
                    wEmailId: wEmailId,
                    pNumber: pNumber,
                    pEmailId: pEmailId,
                    prefContact: prefContact,
                    websiteURL: websiteURL
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        $(".ui-button").trigger("click");
                        alert(response.message);
                        _partners.viewOne(response.updatedId);
                    } else if (response.status.toLowerCase() === "error") {
                        alert(response.message);
                    }
                },
                error: function (error) {
                    alert(error);
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
                alert(fail_error);
            });
        },

        deleteRecord: function () {
            var deleteConfim = confirm("Do you want to delete this insurance company");
            if (!deleteConfim) {
                return;
            }
            var fail_error = null;
            $.ajax({
                method: "POST",
                url: "/projects/partners/deleteRecord",
                data: {
                    partnerId: _partners.partnerId
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _partners.viewAll();
                    } else if (response.status.toLowerCase() === "error") {
                        alert(response.message);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        setPrefContact: function () {
            var prefContact = $("#prefContactDb").length ? $("#prefContactDb").val().split(",") : "";

            $("input[name=prefContact]").each(function () {
                if (prefContact.indexOf(this.value) >= 0) {
                    $(this).prop("checked", true);
                }
            });
        },

        showPartnersList: function (event) {
            var options = "active";

            if (event) {
                options = event.target.getAttribute("data-option");
                if (options) {
                    $($(".partners.internal-tab-as-links").children()).removeClass("active");
                    $(".partners-table-list .row").hide();
                    $(event.target).addClass("active");
                }
            } else {
                $($(".partners.internal-tab-as-links").children()).removeClass("active");
                $(".partners-table-list .row").hide();
                $($(".partners.internal-tab-as-links").children()[0]).addClass("active");
            }

            if (options === "all") {
                $(".partners-table-list .row").show();
            } else if (options !== "") {
                $(".partners-table-list .row." + options).show();
            }
        }
    };
})();