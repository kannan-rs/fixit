/**
    Projects functions
*/
var _claim_dairy_updates = (function () {
    "use strict";

    var _claim_daily_update_id;
    var dairy_updateListStartRecord = 0;
    var dairy_updateListCount       = "All";

    function errorMessage() {
        return {
            dailyUpdateContent: {
                required: _lang.english.errorMessage.claimDailyUpdateForm.dairy_updatesDescription
            }
        };
    };

    function validationRules() {
        return {
            dairy_updateContent: {
                required: true
            }
        };
    };

    function createSubmit() {
        var claim_id = $("#claim_id").val();
        var dailyUpdateContent = $("#dailyUpdateContent").val();

        $.ajax({
            method: "POST",
            url: "/claims/dairy_updates/add",
            data: {
                claim_id: claim_id,
                daily_update_content: dailyUpdateContent
            },
            success: function (response) {
                response = $.parseJSON(response);
                if (response.status.toLowerCase() === "success") {
                    alert(response.message);
                    _claim_dairy_updates.viewAll(response.claim_id);
                    $("#popupForAll").dialog("close");
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
    }

    return {
        createForm : function(event, options) {
            var fail_error  = null;
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            var openAs = (options && options.openAs) ? options.openAs: "";
            var popupType = (options && options.popupType) ? options.popupType: "";

            $.ajax({
                method: "POST",
                url: "/claims/dairy_updates/createForm",
                data: {
                    openAs      : openAs,
                    popupType   : popupType,
                    claim_id    : _claims._claim_id
                },
                success: function (response) {
                    if (openAs === "popup") {
                        $("#popupForAll" + popupType).html(response);
                        _projects.openDialog({title: "Add Claim Dairy Updates"}, popupType);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createValidate: function () {
            var validator = $("#create_claim_dairy_update_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            });

            if (validator.form()) {
                createSubmit();
            }
        },

        viewAll: function (claim_id, dairy_update_id) {
            dairy_updateListStartRecord = dairy_updateListStartRecord || 0;
            dairy_updateListCount = dairy_updateListCount || "All";

            $.ajax({
                method: "POST",
                url: "/claims/dairy_updates/viewAll",
                data: {
                    claim_id: claim_id,
                    dairy_update_id: dairy_update_id,
                    startRecord: dairy_updateListStartRecord,
                    count: _claim_dairy_updates.dairy_updateListCount
                },
                success: function (response) {
                    if (response.length) {
                        $("#dairy_update_content").html(response);
                        var dairy_updateCount = $("#dairyUpdatesCountForClaim").val();
                        dairy_updateCount = (dairy_updateCount && dairy_updateCount !== "" || dairy_updateCount > 0) ? " (" + dairy_updateCount + ")" : '';
                        $("#dairyUpdatesCountForClaimDisplay").html(dairy_updateCount);
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