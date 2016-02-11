var _remainingbudget = (function () {
    "use strict";
    return {
        validationRules: function () {
            return {
                amount: {
                    number: true
                }
            };
        },

        errorMessage: function () {
            return {
                amount: {
                    required: _lang.english.errorMessage.budgetForm.amount,
                    number: _lang.english.errorMessage.budgetForm.amount
                },
                descr: {
                    required: _lang.english.errorMessage.budgetForm.descr
                },
                date: {
                    required: _lang.english.errorMessage.budgetForm.date
                }
            };
        },

        getListWithForm: function (event, options ) {
            var fail_error  = null;
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            var openAs = (options && options.openAs) ? options.openAs: "";
            var popupType = (options && options.popupType) ? options.popupType: "";
            var budgetId = (options && options.budgetId) ? options.budgetId: "";

            $.ajax({
                method: "POST",
                url: "/projects/remainingbudget/getListWithForm",
                data: {
                    openAs: openAs,
                    popupType: popupType,
                    projectId: _projects.projectId,
                    budgetId: budgetId
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    if (openAs === "popup") {
                        $("#popupForAll" + popupType).html(response);
                        _projects.openDialog({title: "Paid From Budget"}, popupType);
                        _utils.setAsDateFields({dateField: "date"});
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        validate: function (openAs, popupType) {
            var validator = $("#create_budget_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                _remainingbudget.addUpdate(openAs, popupType);
            }
        },

        addUpdate: function (openAs, popupType) {
            var fail_error = null;
            var idPrefix = "#create_budget_form ";
            var budgetId = $(idPrefix + "#budgetId").val();
            var date = _utils.toMySqlDateFormat($(idPrefix + "#date").val());
            var descr = $(idPrefix + "#descr").val();
            var amount = $(idPrefix + "#amount").val();
            var urlSuffix = budgetId === "" ? "add": "update";

            var url = "/projects/remainingbudget/" + urlSuffix;

            $.ajax({
                method: "POST",
                url: url,
                data: {
                    date: date,
                    descr: descr,
                    amount: amount,
                    projectId: _projects.projectId,
                    budgetId: budgetId
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _remainingbudget.getListWithForm(undefined, {openAs: openAs, popupType: popupType});
                        _projects.viewOnlyBudget();
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

        editRecordForm: function (budgetId) {
            _remainingbudget.getListWithForm(undefined, {openAs: "popup", popupType: "2", budgetId: budgetId});
        },

        deleteRecord: function (budgetId) {
            var fail_error = null;
            $.ajax({
                method: "POST",
                url: "/projects/remainingbudget/deleteRecord",
                data: {
                    remainingbudgetId: budgetId
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    alert(response.message);
                    if (response.status.toLowerCase() === "success") {
                        _remainingbudget.getListWithForm(undefined, {openAs: "popup", popupType: "2"});
                        _projects.viewOnlyBudget();
                    }
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