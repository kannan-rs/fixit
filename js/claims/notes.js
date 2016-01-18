/**
    Projects functions
*/
var _claim_notes = (function () {
    "use strict";

    var _claim_notes_id;
    var noteListStartRecord = 0;
    var noteListCount       = "All";

    function errorMessage() {
        return {
            noteContent: {
                required: _lang.english.errorMessage.claimNotesForm.notesDescription
            }
        };
    };

    function validationRules() {
        return {
            noteContent: {
                required: true
            }
        };
    };

    function createSubmit() {
        var claim_id = $("#claim_id").val();
        var noteContent = $("#noteContent").val();

        $.ajax({
            method: "POST",
            url: "/claims/notes/add",
            data: {
                claim_id: claim_id,
                noteContent: noteContent
            },
            success: function (response) {
                response = $.parseJSON(response);
                if (response.status.toLowerCase() === "success") {
                    alert(response.message);
                    _claim_notes.viewAll(response.claim_id);
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
                url: "/claims/notes/createForm",
                data: {
                    openAs      : openAs,
                    popupType   : popupType,
                    claim_id    : _claims._claim_id
                },
                success: function (response) {
                    if (openAs === "popup") {
                        $("#popupForAll" + popupType).html(response);
                        _projects.openDialog({title: "Add Claim Notes"}, popupType);
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
            var validator = $("#create_claim_note_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            });

            if (validator.form()) {
                createSubmit();
            }
        },

        viewAll: function (claim_id, note_id) {
            noteListStartRecord = noteListStartRecord || 0;
            noteListCount = noteListCount || "All";

            $.ajax({
                method: "POST",
                url: "/claims/notes/viewAll",
                data: {
                    claim_id: claim_id,
                    note_id: note_id,
                    startRecord: noteListStartRecord,
                    count: _notes.noteListCount
                },
                success: function (response) {
                    if (response.length) {
                        $("#note_content").html(response);
                        var noteCount = $("#notesCountForClaim").val();
                        noteCount = (noteCount && noteCount !== "" || noteCount > 0) ? " (" + noteCount + ")" : '';
                        $("#notesCountForClaimDisplay").html(noteCount);
                   }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        deleteRecord: function (note_id) {
            var deleteConfim = confirm("Do you want to delete this notes");

            if (!deleteConfim) {
                return;
            }

            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/claims/notes/deleteRecord",
                data: {
                    note_id: note_id
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        _claim_notes.viewAll(_claims._claim_id);
                        setTimeout(function () {alert(response.message);}, 100);
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
    };
})();