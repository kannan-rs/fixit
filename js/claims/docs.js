var _claim_docs = (function(){
	"use strict";

    var docsListStartRecord = 0;
    var docsRequestSent     = 0;

    function validationRules() {
        return {
            docName: {
                required: true
            },
            docAttachment: {
                required: true
            }
        };
    };

    function errorMessage() {
        return {
            docName: {
                required: _lang.english.errorMessage.projectDocsForm.docName
            },
            docAttachment: {
                required: _lang.english.errorMessage.projectDocsForm.docAttachment
            }
        };
    };

     function removeDoc(docId) {
        $("#docId_" + docId).hide();
    };

    function presetViewAll() {
        var count   = $("#doc_count").val();
        if(count) {
            $("#claimDocsCountDisplay").text("("+count+")");
        }
    }

	return {

        createValidate: function () {
            var validator = $("#create_claim_doc_form").validate({
                rules: validationRules(),
                messages: errorMessage()
            });

            if (validator.form()) {
                return true;
            } else {
                return false;
            }
        },

        viewAll: function (claim_id) {
            var fail_error = null;

            $.ajax({
                method: "POST",
                url: "/claims/docs/viewAll",
                data: {
                    claim_id: _claims._claim_id,
                    startRecord: docsListStartRecord
                },
                success: function (response) {
                    if (response.length) {
                        $("#attachment_content").html(response);
                        presetViewAll();
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        deleteRecord: function (doc_id) {
            var fail_error = null;
            $.ajax({
                method: "POST",
                url: "/claims/docs/deleteRecord",
                data: {
                    doc_id: doc_id
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        _claim_docs.viewAll();
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

		addDocumentForm : function(event) {
			var fail_error = null;
            var self = this;
            
            if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }

            $.ajax({
                method: "POST",
                url: "/claims/docs/createForm",
                data: {
                    claim_id: _claims._claim_id
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    _projects.openDialog({title: "Add Claim Document"});
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
		}
	}
})();