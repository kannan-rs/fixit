/**
    Notes functions
*/
var _docs = (function () {
    "use strict";
    return {
        validationRules: function () {
            return {
                docName: {
                    required: true
                },
                docAttachment: {
                    required: true
                }
            };
        },

        errorMessage: function () {
            return {
                docName: {
                    required: _lang.english.errorMessage.projectDocsForm.docName
                },
                docAttachment: {
                    required: _lang.english.errorMessage.projectDocsForm.docAttachment
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
                url: "/projects/docs/projectDetails",
                data: {
                    projectId : projectId
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

        viewAll: function (projectId) {
            var fail_error = null;
            _projects.resetCounter("docs");
            //_projects.clearRest(["attachment_list", "new_attachment"]);
            this.createForm(projectId);

            this.docsListStartRecord = this.docsListStartRecord || 0;

            if (!this.docsRequestSent || this.docsRequestSent < this.docsListStartRecord) {
                $.ajax({
                    method: "POST",
                    url: "/projects/docs/viewAll",
                    data: {
                        projectId: projectId,
                        startRecord: _docs.docsListStartRecord
                    },
                    success: function (response) {
                        if(!_utils.is_logged_in( response )) { return false; }
                        if (response.length) {
                            if (_docs.docsListStartRecord === 0) {
                                $("#attachment_list").html(response);
                            } else {
                                $("#attachment_list").append(response);
                            }
                            _docs.docsRequestSent = _docs.docsListStartRecord;
                            _docs.docsListStartRecord += 10;
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

        createForm: function (projectId) {
            var fail_error = null;
            if ($("#create_project_doc_form").length) {
                $("input[type='text']").val("");
                $("textarea").val("");
                return;
            }
            $.ajax({
                method: "POST",
                url: "/projects/docs/createForm",
                data: {
                    projectId: projectId
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#new_attachment").html(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createValidate: function () {
            var validator = $("#create_project_doc_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if (validator.form()) {
                return true;
            } else {
                return false;
            }
        },

        deleteRecord: function (doc_id) {
            var fail_error = null;
            $.ajax({
                method: "POST",
                url: "/projects/docs/deleteRecord",
                data: {
                    docId: doc_id
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _docs.removeDoc(response.docId);
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

        removeDoc: function (docId) {
            $("#docId_" + docId).hide();
        }
    };
})();