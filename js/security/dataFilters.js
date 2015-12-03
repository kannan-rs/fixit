/**
     DataFilters Tab related functions
*/
var _dataFilters = (function () {
    'use strict';
    return {
        errorMessage: function () {
            return {
                dataFilterId: {
                    required:  _lang.english.errorMessage.dataFilterForm.dataFilterId,
                    maxlength:  _lang.english.errorMessage.dataFilterForm.dataFilterId,
                    minlength:  _lang.english.errorMessage.dataFilterForm.dataFilterId,
                    digits:  _lang.english.errorMessage.dataFilterForm.dataFilterId
                },
                dataFilterName: {
                    required: _lang.english.errorMessage.dataFilterForm.dataFilterName
                },
                dataFilterDescr: {
                    required: _lang.english.errorMessage.dataFilterForm.dataFilterDescr
                }
            };
        },

        validationRules: function () {
            return {
                dataFilterId: {
                    required: true,
                    maxlength: 5,
                    minlength: 5,
                    digits: true
                },
                dataFilterName: {
                    required: true
                },
                dataFilterDescr: {
                    required: true
                }
            };
        },

        createValidate: function () {
            var validator = $("#create_dataFilter_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                _dataFilters.createSubmit();
            }
        },

        updateValidate: function () {
            var validator = $("#update_dataFilter_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                _dataFilters.updateSubmit();
            }
        },

        viewAll: function () {
            $.ajax({
                method: "POST",
                url: "/security/dataFilters/viewAll",
                data: {},
                success: function (response) {
                    $("#security_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createForm: function () {
            $.ajax({
                method: "POST",
                url: "/security/dataFilters/createForm",
                data: {},
                success: function (response) {
                    $("#security_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createSubmit: function () {
            var dataFilter_id = $("#dataFilterId").val();
            var dataFilter_name = $("#dataFilterName").val();
            var dataFilter_desc = $("#dataFilterDescr").val();

            $.ajax({
                method: "POST",
                url: "/security/dataFilters/add",
                data: {
                    dataFilter_id: dataFilter_id,
                    dataFilter_name: dataFilter_name,
                    dataFilter_desc: dataFilter_desc
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _dataFilters.viewOne(response.insertedId);
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

        editDataFilter: function (dataFilter_sno) {
            $.ajax({
                method: "POST",
                url: "/security/dataFilters/editForm",
                data: {dataFilter_sno: dataFilter_sno},
                success: function (response) {
                    $("#security_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        updateSubmit: function () {
            var dataFilter_id = $("#dataFilterId").val();
            var dataFilter_name = $("#dataFilterName").val();
            var dataFilter_desc = $("#dataFilterDescr").val();
            var dataFilter_sno =  $("#dataFilter_sno").val();

            $.ajax({
                method: "POST",
                url: "/security/dataFilters/update",
                data: {
                    dataFilter_id: dataFilter_id,
                    dataFilter_name: dataFilter_name,
                    dataFilter_desc: dataFilter_desc,
                    dataFilter_sno: dataFilter_sno
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status.toLowerCase() === "success") {
                        _dataFilters.viewOne(response.updatedId);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        deleteRecord: function (dataFilter_sno) {
            $.ajax({
                method: "POST",
                url: "/security/dataFilters/deleteRecord",
                data: {
                    dataFilter_sno: dataFilter_sno
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status === "success") {
                        _dataFilters.viewAll();
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        viewOne: function (dataFilter_sno) {
            $.ajax({
                method: "POST",
                url: "/security/dataFilters/viewOne",
                data: {
                    dataFilter_sno: dataFilter_sno
                },
                success: function (response) {
                    $("#security_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        }
    };
})();