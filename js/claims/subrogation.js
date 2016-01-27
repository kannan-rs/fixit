var _claim_subrogation = (function() {
	"use strict";

	function errorMessage() {
        return {
            climant_name : {
            	required: _lang.english.errorMessage.claimSubrogationForm.climant_name
            },
            addressLine1: {
                required: _lang.english.errorMessage.claimSubrogationForm.addressLine1
            },
            addressLine2: {
                required: _lang.english.errorMessage.claimSubrogationForm.addressLine2
            },
            city: {
                required: _lang.english.errorMessage.claimSubrogationForm.city
            },
            country: {
                required: _lang.english.errorMessage.claimSubrogationForm.country
            },
            state: {
                required: _lang.english.errorMessage.claimSubrogationForm.state
            },
            zipCode: {
                required: _lang.english.errorMessage.claimSubrogationForm.zipCode
            },
            status: {
                required:_lang.english.errorMessage.claimSubrogationForm.status
            },
            description: {
                required:_lang.english.errorMessage.claimSubrogationForm.description
            },
        };
    };

    function validationRules() {

    };

    function createSubmit() {
		var climant_name		= $("#climant_name").val();
		var addressLine1		= $("#addressLine1").val();
		var addressLine2		= $("#addressLine2").val();
		var city				= $("#city").val();
		var country				= $("#country").val();
		var state				= $("#state").val();
		var zipCode				= $("#zipCode").val();
		var status				= $("#status").val();
		var description			= $("#description").val();

        $.ajax({
            method: "POST",
            url: "/claims/subrogation/add",
            data: {
            	claim_id : _claims._claim_id,
				climant_name : climant_name,
				addressLine1 : addressLine1,
				addressLine2 : addressLine2,
				city : city,
				country : country,
				state : state,
				zipCode : zipCode,
				status : status,
				description : description
            },
            success: function (response) {
                response = $.parseJSON(response);
                if (response.status.toLowerCase() === "success") {
                    alert(response.message);
                    $("#popupForAll").dialog("close");
                    _claim_subrogation.viewOne(response.insertedId);
                    _claim_subrogation.viewAll();
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
    };

    function updateSubmit() {
        var climant_name        = $("#climant_name").val();
        var addressLine1        = $("#addressLine1").val();
        var addressLine2        = $("#addressLine2").val();
        var city                = $("#city").val();
        var country             = $("#country").val();
        var state               = $("#state").val();
        var zipCode             = $("#zipCode").val();
        var status              = $("#status").val();
        var description         = $("#description").val();

        $.ajax({
            method: "POST",
            url: "/claims/subrogation/update",
            data: {
                claim_id : _claims._claim_id,
                subrogation_id : _claim_subrogation._subrogation_id,
                climant_name : climant_name,
                addressLine1 : addressLine1,
                addressLine2 : addressLine2,
                city : city,
                country : country,
                state : state,
                zipCode : zipCode,
                status : status,
                description : description
            },
            success: function (response) {
                response = $.parseJSON(response);
                if (response.status.toLowerCase() === "success") {
                    alert(response.message);
                    $("#popupForAll").dialog("close");
                    _claim_subrogation.viewOne(_claim_subrogation._subrogation_id);
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
    };

    function presetInputForm( form ) {
        $(".customer-search-result").hide();
        _utils.setCustomerDataList();
        _utils.getAndSetCountryStatus(form+"_subrogation_form", "property");
        $("#subrogation_accordion").accordion(
            {
                collapsible: true,
                icons: {header: "ui-icon-plus", activeHeader: "ui-icon-minus"},
                active: false,

            }
        );
        _claim_subrogation.viewOnlyExpandAll();

        $("#is_property_address_same").bind( {
            click : function( event ) {
                $("#subrogation_property_address").show();
                if($("#is_property_address_same:checked").val()) {
                    $("#subrogation_property_address").hide();
                }
            }
        });

        if($("#is_property_address_same:checked").val()) {
            $("#subrogation_property_address").hide();
        }

        // Set to DB status if available
        var db_status_value = $("#"+form+"_subrogation_form #db_status_value").val();
        if(db_status_value) {
            $("#"+form+"_subrogation_form #status").val(db_status_value);
        }
    };

    function presetViewOne() {
        //_claim_subrogation_notes.viewAll(_claim_subrogation._subrogation_id);
        //_claim_subrogation_docs.viewAll(_claim_subrogation._subrogation_id);
    };

    function presetViewAll() {

    };
	return {
		viewAll : function() {
			var fail_error = null;
            
            $.ajax({
                method: "POST",
                url: "/claims/subrogation/viewAll",
                data: {
                	claim_id 	: _claims._claim_id
                },
                success: function (response) {
                    $("#tabs_claim_subrogation").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
		},
		createForm : function( options ) {
			var fail_error = null;

			var openAs = options && options.openAs ? options.openAs : "";
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/subrogation/createForm",
                data: {
                	openAs		: openAs,
                	claim_id 	: _claims._claim_id
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    _projects.openDialog({title: "Create Subrogation"});
                    presetInputForm( "create" );
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
		},
		createValidate: function() {
            var validator = $("#create_subrogation_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            }).form();

            validator = validator && _utils.cityFormValidation() ? false : validator;

            if (validator) {
               createSubmit();
            }
		},
		editForm : function( options ) {
            var fail_error = null;

            var openAs = options && options.openAs ? options.openAs : "";
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/subrogation/editForm",
                data: {
                    openAs          : openAs,
                    claim_id        : _claims._claim_id,
                    subrogation_id  : _claim_subrogation._subrogation_id
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    _projects.openDialog({title: "Update Subrogation"});
                    presetInputForm( "update" );
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
		},
		updateValidate: function() {
            var validator = $("#update_subrogation_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            }).form();

            validator = validator && _utils.cityFormValidation() ? false : validator;

            if (validator) {
               updateSubmit();
            }
		},
		viewOne : function( subrogation_id ) {
			this._subrogation_id = subrogation_id;
    		var fail_error = null;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/subrogation/viewOne",
                data: {
                	claim_id 		: _claims._claim_id,
                    subrogation_id : _claim_subrogation._subrogation_id
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    _projects.openDialog({title: "Subrogation Details"});
                    $("#subrogation_accordion").accordion(
                        {
                            collapsible: true,
                            icons: {header: "ui-icon-plus", activeHeader: "ui-icon-minus"},
                            active: false
                        }
                   );
                    presetViewOne();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
		},
		deleteRecord: function() {

		},
        viewOnlyExpandAll: function () {
            var icons = $( "#subrogation_accordion" ).accordion( "option", "icons" );
            //$('.open').click(function () {
            $('#subrogation_accordion > .ui-accordion-header').removeClass('ui-corner-all').addClass('ui-accordion-header-active ui-state-active ui-corner-top').attr({
                'aria-selected': 'true',
                'tabindex': '0'
            });
            $('#subrogation_accordion > .ui-accordion-header > .ui-accordion-header-icon').removeClass(icons.header).addClass(icons.activeHeader);
            $('#subrogation_accordion > .ui-accordion-content').addClass('ui-accordion-content-active').attr({
                'aria-expanded': 'true',
                'aria-hidden': 'false'
            }).show();
            //});
        },
        viewOnlyCollapseAll: function() {
            var icons = $( "#subrogation_accordion" ).accordion( "option", "icons" );
            //$('.close').click(function () {
            $('#subrogation_accordion > .ui-accordion-header').removeClass('ui-accordion-header-active ui-state-active ui-corner-top').addClass('ui-corner-all').attr({
                'aria-selected': 'false',
                'tabindex': '-1'
            });
            $('#subrogation_accordion > .ui-accordion-header > .ui-accordion-header-icon').removeClass(icons.activeHeader).addClass(icons.header);
            $('#subrogation_accordion > .ui-accordion-content').removeClass('ui-accordion-content-active').attr({
                'aria-expanded': 'false',
                'aria-hidden': 'true'
            }).hide();
            //});
        }
	};
})();