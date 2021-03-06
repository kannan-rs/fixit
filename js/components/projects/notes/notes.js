/**
    Notes functions
*/
var _notes = (function () {
    "use strict";
    return {
        validationRules: function () {
            return {
                noteContent: {
                    required: true
                }
            };
        },

        errorMessage: function () {
            return {
                noteContent: {
                    required: _lang.english.errorMessage.projectNotes.noteContent
                }
            };
        },

        projectDetails: function (projectId) {
            var fail_error = null;
            if ($(".projectDetails").length) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/projects/notes/projectDetails",
                data: {
                    projectId: projectId
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#project_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        viewAll: function (projectId, taskId, noteId) {
            _projects.resetCounter("notes");
            //_projects.clearRest(["note_content", "new_note_content"]);
            this.createForm(projectId, taskId);

            this.noteListStartRecord = this.noteListStartRecord || 0;
            this.noteListCount = this.noteListCount || "All";

            taskId = taskId || 0;

            if (!this.noteRequestSent || this.noteRequestSent < this.noteListStartRecord) {
                $.ajax({
                    method: "POST",
                    url: "/projects/notes/viewAll",
                    data: {
                        projectId: projectId,
                        taskId: taskId,
                        noteId: noteId,
                        startRecord: _notes.noteListStartRecord,
                        count: _notes.noteListCount
                    },
                    success: function (response) {
                        if(!_utils.is_logged_in( response )) { return false; }
                        if (response.length) {
                            $("#notesCount").remove();
                            if (_notes.noteListStartRecord === 0) {
                                $("#note_content").html(response);
                            } else {
                                $("#note_list_table").append(response);
                            }

                            _notes.noteRequestSent = _notes.noteListStartRecord;
                            _notes.noteListStartRecord += 5;
                        }
                    },
                    error: function (error) {
                        error = error;
                    }
                }).fail(function (failedObj) {
                    fail_error = failedObj;
                });
            }
        },

        createForm: function (projectId, taskId) {
            if ($("#create_project_note_form").length) {
                $("input[type='text']").val("");
                $("textarea").val("");
                return;
            }
            $.ajax({
                method: "POST",
                url: "/projects/notes/createForm",
                data: {
                    projectId: projectId,
                    taskId: taskId
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#new_note_content").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createValidate: function (viewFor) {
            var validator = $("#create_project_note_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                if (!viewFor) {
                    _notes.createSubmit();
                } else {
                    _projects.noteCreateSubmit();
                }
            }
        },

        createSubmit: function () {
            var projectId = $("#projectId").val();
            var taskId = $("#taskId").val();
            var noteName = $("#noteName").val();
            var noteContent = $("#noteContent").val();

            $.ajax({
                method: "POST",
                url: "/projects/notes/add",
                data: {
                    projectId: projectId,
                    taskId: taskId,
                    noteName: noteName,
                    noteContent: noteContent
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _notes.viewAll(response.projectId, response.taskId, response.insertedId);
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