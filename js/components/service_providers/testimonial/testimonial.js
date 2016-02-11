var _contractor_testimonial = (function () {
	return {
		viewAll: function() {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/Testimonial/viewAll",
                data: {
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
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

        createForm: function( event ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/Testimonial/createForm",
                data: {
                    contractor_id       : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
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

        createValidate : function() {
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
                _contractor_testimonial.createSubmit();
            }
        },

        createSubmit: function() {
            var testimonial_summary         = $("#testimonial_summary").val();
            var testimonial_descr           = $("#testimonial_descr").val();
            var testimonial_rating         = $("#testimonial_rating").val();
            var testimonial_customer_name   = $("#testimonial_customer_name").val();
            var testimonial_date            = _utils.toMySqlDateFormat($('#testimonial_date').val());

            $.ajax({
                method: "POST",
                url: "/service_providers/Testimonial/add",
                data: {
                    testimonial_summary         : testimonial_summary,
                    testimonial_descr           : testimonial_descr,
                    testimonial_rating         : testimonial_rating,
                    testimonial_customer_name   : testimonial_customer_name,
                    testimonial_date            : testimonial_date,
                    contractor_id               : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractor_testimonial.viewAll();
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

        editForm: function( event, testimonial_id ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/Testimonial/editForm",
                data: {
                    contractor_id       : _contractors.contractorId,
                    testimonial_id      : testimonial_id
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
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

        updateValidate: function() {
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
                _contractor_testimonial.updateSubmit();
            }
        },

        updateSubmit: function() {
            var testimonial_id              = $("#dbTestimonialId").val();
            var testimonial_summary         = $("#testimonial_summary").val();
            var testimonial_descr           = $("#testimonial_descr").val();
            var testimonial_rating         = $("#testimonial_rating").val();
            var testimonial_customer_name   = $("#testimonial_customer_name").val();
            var testimonial_date            = _utils.toMySqlDateFormat($('#testimonial_date').val());

            $.ajax({
                method: "POST",
                url: "/service_providers/Testimonial/update",
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
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        $("#popupForAll").dialog("close");
                        _contractor_testimonial.viewAll();
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

        delete :  function( event, testimonial_id ) {
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

             var deleteConfim = confirm("Do you want to delete this testimonial");
            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/service_providers/Testimonial/delete",
                data: {
                    testimonial_id  : testimonial_id,
                    contractor_id   : _contractors.contractorId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON( response );
                    if(response.status == "success") {
                        alert(response.message);
                        _contractor_testimonial.viewAll();
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