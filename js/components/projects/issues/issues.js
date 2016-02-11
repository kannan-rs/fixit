var _issues = (function() {
    "use strict";
    return {
        validationRules: function() {
            return {
                issueName : {
                    required : true
                },
                issueDescr : {
                    required : true
                },
                assignedToUserType : {
                    required : false
                },
                issueAssignedToCustomer : {
                    required : true
                },
                issueContractorResult : {
                    required : false
                },
                issueAdjusterResult : {
                    required : false
                },
                issueFromdate : {
                    required : true
                },
                issueStatus : {
                    required : true
                },
                issueNotes : {
                    required : true
                },
                contactPhoneNumber : {
                    digits : true    
                },
                mobileNumber : {
                    digits : true    
                }
            };
        },

        errorMessage: function() {
            return {
                issueName : {
                    required : _lang.english.errorMessage.issueForm.issueName
                },
                issueDescr : {
                    required : _lang.english.errorMessage.issueForm.issueDescr
                },
                assignedToUserType : {
                    required : _lang.english.errorMessage.issueForm.assignedToUserType
                },
                issueAssignedToCustomer : {
                    required : _lang.english.errorMessage.issueForm.issueAssignedToCustomer
                },
                issueContractorResult : {
                    required : _lang.english.errorMessage.issueForm.issueContractorResult
                },
                issueAdjusterResult : {
                    required : _lang.english.errorMessage.issueForm.issueAdjusterResult
                },
                issueFromdate : {
                    required : _lang.english.errorMessage.issueForm.issueFromdate
                },
                issueStatus : {
                    required : _lang.english.errorMessage.issueForm.issueStatus
                },
                issueNotes : {
                    required : _lang.english.errorMessage.issueForm.issueNotes
                },
                contactPhoneNumber : {
                    digits : _lang.english.errorMessage.issueForm.contactPhoneNumber
                },
                mobileNumber : {
                    digits : _lang.english.errorMessage.issueForm.mobileNumber
                }
            };
        },

        createForm: function(event, options) {

             if(typeof(event) != 'undefined') {
                event.stopPropagation();
            }
            
            var openAs         = options && options.openAs ? options.openAs : "";
            var popupType     = options && options.popupType ? options.popupType : "";
            var projectId     = options && options.projectId ? options.projectId : "";
            var taskId         = options && options.taskId ? options.taskId : "";

            if(!openAs) {
                _projects.clearRest();
                _projects.toggleAccordiance("issues", "new");
            }
            
            $.ajax({
                method: "POST",
                url: "/projects/issues/createForm",
                data: {
                    openAs         : openAs,
                    popupType     : popupType,
                    projectId     : projectId,
                    taskId         : taskId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    if(openAs == "popup") {
                        $("#popupForAll"+popupType).html( response );
                        _projects.openDialog({"title" : "Add Issue"}, popupType);
                    } else {
                        $("#issue_content").html(response);
                    }

                    _utils.setAsDateFields({"dateField": "issueFromdate"})
                    _utils.setIssueStatus("issueStatus", "issueStatusDb");
                    _utils.setIssueAssignedTo("assignedToUserType", "assignedToUserTypeDB");
                    _issues.showAssignedToOptions();
                    _issues.getAndListAssignees( projectId );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        createValidate:  function ( openAs, popupType ) {
            var validator = $( "#create_issue_form" ).validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if(validator.form()) {
                _issues.createSubmit( openAs, popupType );
            }
        },

        createSubmit: function( openAs, popupType ) {
            var idPrefix                 = "#create_issue_form "
            var issueName                 = $(idPrefix+"#issueName").val();
            var issueDescr                 = $(idPrefix+"#issueDescr").val();
            var assignedToUserType        = $(idPrefix+"#assignedToUserType").val();
            var issueFromdate             = _utils.toMySqlDateFormat($(idPrefix+"#issueFromdate").val());
            var issueStatus             = $(idPrefix+"#issueStatus").val();
            var issueNotes                 = $(idPrefix+"#issueNotes").val();
            var issueProjectId             = $(idPrefix+"#issueProjectId").val();
            var issueTaskId             = $(idPrefix+"#issueTaskId").val();

            var assignedToContractorId     = "";
            var assignedToAdjusterId     = "";
            var assignedToCustomerId     = this.issueAssignedToCustomerId;

            $(idPrefix+"input[name=issueRadioContractorSelected]:checked").each(
                function() {
                    assignedToContractorId = this.value;
                }
            );

            $(idPrefix+"input[name=issueRadioAdjusterSelected]:checked").each(
                function() {
                    assignedToAdjusterId = this.value;
                }
            );

            $.ajax({
                method: "POST",
                url: "/projects/issues/add",
                data: {
                    issueName                 : issueName,
                    issueDescr                 : issueDescr,
                    issueProjectId             : issueProjectId,
                    issueTaskId             : issueTaskId,
                    assignedToUserType        : assignedToUserType,
                    issueFromdate             : issueFromdate,
                    issueStatus             : issueStatus,
                    issueNotes                 : issueNotes,
                    assignedToContractorId    : assignedToContractorId,
                    assignedToAdjusterId     : assignedToAdjusterId,
                    assignedToCustomerId     : assignedToCustomerId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    _issues.viewAll({projectId : issueProjectId, taskId : issueTaskId});
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        _issues.viewOne(response.insertedId, openAs, popupType);
                    } else if(response.status.toLowerCase() == "error") {
                        alert(response.message);
                    }
                    

                    if(_projects.projectId) {
                        _projects.viewOne( issueProjectId, {"triggeredBy" : "issues", "taskId": issueTaskId} );
                    } else {
                        _projects.viewAll();    
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

        viewOne: function( issueId, openAs, popupType ) {
            //this.issueId     = issueId;
            popupType         = session.page == "projects" ? "2" : "";
            openAs             = session.page == "projects" ? "popup" : "";
            
            $.ajax({
                method: "POST",
                url: "/projects/issues/viewOne",
                data: {
                    issueId         : issueId,
                    openAs             : openAs,
                    popupType         : popupType
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    if( openAs && openAs == "popup") {
                        $("#popupForAll"+popupType).html( response );
                        _projects.openDialog({"title" : "Issue Details"}, popupType);
                    } else {
                        $("#issue_content").html(response);
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

        viewAll: function( options ) {
            //var openAs         = options && options.openAs ? options.openAs : "";
            //var popupType     = options && options.popupType ? options.popupType : "";
            /*var projectId     = options && options.projectId ? options.projectId : ( this.projectId ? this.projectId : "" );
            var taskId         = options && options.taskId ? options.taskId : ( this.taskId ? this.taskId : "" );*/
            var projectId     = options && options.projectId ? options.projectId : "" ;
            var taskId         = options && options.taskId ? options.taskId : "" ;
            var popupType     = "";
            var openAs         = session.page == "projects" ? "popup" : "";

            /*this.projectId     = projectId;
            this.taskId     = taskId;*/

            if(!projectId && !taskId) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/projects/issues/viewAll",
                data: {
                    openAs         : openAs,
                    popupType     : popupType,
                    projectId     : projectId,
                    taskId         : taskId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    if(openAs == "popup") {
                        $("#popupForAll"+popupType).html( response );
                        _projects.openDialog({"title" : "Issues"}, popupType);
                    } else {
                        $("#issue_content").html(response);
                    }
                    _issues.showIssuesList();
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        editForm: function( options ) {
            var openAs         = options && options.openAs ? options.openAs : "";
            var popupType     = options && options.popupType ? options.popupType : "";
            var issueId     = options && options.issueId ? options.issueId : "";
            var projectId     = options && options.projectId ? options.projectId : "";
            var taskId         = options && options.taskId ? options.taskId : "";

            $.ajax({
                method: "POST",
                url: "/projects/issues/editForm",
                data: {
                    'issueId'         : issueId,
                    'openAs'         : openAs,
                    'popupType'     : popupType,
                    'projectId'     : projectId,
                    'taskId'         : taskId
                    
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    if(openAs == "popup") {
                        $("#popupForAll"+popupType).html(response);
                        _projects.openDialog({"title" : "Edit Issue"}, popupType);
                    } else {
                        $("#issue_content").html(response);
                    }
                    
                    _utils.setAsDateFields({"dateField": "issueFromdate"})
                    _utils.setIssueStatus("issueStatus", "issueStatusDb");
                    _utils.setIssueAssignedTo("assignedToUserType", "assignedToUserTypeDB");
                    _issues.showAssignedToOptions();
                    _issues.getAndListAssignees( projectId );
                },
                error: function( error ) {
                    error = error;
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
            });
        },

        updateValidate: function( openAs, popupType ) {
            var validator = $( "#update_issue_form" ).validate({
                rules         : this.validationRules(),
                messages     : this.errorMessage()
            });

            if(validator.form()) {
                _issues.updateSubmit( openAs, popupType );
            }
        },

        updateSubmit: function( openAs, popupType ) {
            var idPrefix                 = "#update_issue_form ";
            var issueId                 = $(idPrefix+"#issueId").val();
            var issueProjectId             = $(idPrefix+"#issueProjectId").val();
            var issueTaskId                 = $(idPrefix+"#issueTaskId").val();
            var issueName                 = $(idPrefix+"#issueName").val();
            var issueDescr                 = $(idPrefix+"#issueDescr").val();
            var assignedToUserTypeDB    = $(idPrefix+"#assignedToUserTypeDB").val();
            var assignedToUserType        = $(idPrefix+"#assignedToUserType").val();
            var assignedToUserDB         = $(idPrefix+"#assignedToUserDB").val();
            var issueFromdate             = _utils.toMySqlDateFormat($(idPrefix+"#issueFromdate").val());
            var issueStatus             = $(idPrefix+"#issueStatus").val();
            var issueNotes                 = $(idPrefix+"#issueNotes").val();

            var assignedToContractorId     = "";
            var assignedToAdjusterId     = "";
            var assignedToCustomerId     = this.issueAssignedToCustomerId;

            $(idPrefix+"input[name=issueRadioContractorSelected]:checked").each(
                function() {
                    assignedToContractorId = this.value;
                }
            );

            $(idPrefix+"input[name=issueRadioAdjusterSelected]:checked").each(
                function() {
                    assignedToAdjusterId = this.value;
                }
            );

            $.ajax({
                method: "POST",
                url: "/projects/issues/update",
                data: {
                    issueId                 : issueId,
                    issueProjectId             : issueProjectId,
                    issueTaskId             : issueTaskId,
                    issueName                 : issueName,
                    issueDescr                 : issueDescr,
                    assignedToUserType        : assignedToUserType,
                    issueFromdate             : issueFromdate,
                    issueStatus             : issueStatus,
                    issueNotes                 : issueNotes,
                    assignedToContractorId    : assignedToContractorId,
                    assignedToAdjusterId     : assignedToAdjusterId,
                    assignedToCustomerId     : assignedToCustomerId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        _issues.viewOne(response.updatedId, openAs, popupType);
                    } else if(response.status.toLowerCase() == "error") {
                        alert(response.message);
                    }
                    _issues.viewAll({projectId : issueProjectId, taskId : issueTaskId});
                    
                    if(_projects.projectId) {
                        _projects.viewOne( issueProjectId );
                    } else {
                        _projects.viewAll();    
                    } 
                },
                error: function( error ) {
                    alert(error);
                }
            })
            .fail(function ( failedObj ) {
                fail_error = failedObj;
                alert(fail_error);
            });
        },

        deleteRecord: function() {
            $.ajax({
                method: "POST",
                url: "/projects/issues/deleteRecord",
                data: {
                    issueId: _issues.issueId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        alert(response.message);
                        _issues.viewAll();
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

        showAssignedToOptions: function() {
            var assignetTo    = $("#assignedToUserType").val();

            $("#assignedToUserCustomer").hide();
            $("#assignedToUserContractor").hide();
            $("#assignedToUserAdjuster").hide();

            if(assignetTo && assignetTo != "") {
                var suffix = assignetTo.capitalizeFirstLetter();
                $("#assignedToUser"+suffix).show();
            }
        },

        getAndListAssignees: function( projectId ) {
            $.ajax({
                method: "POST",
                url: "/projects/projects/getAssignees",
                data: {
                    projectId: projectId
                },
                success: function( response ) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    response = $.parseJSON(response);
                    if(response.status.toLowerCase() == "success") {
                        _issues.setAssignees( response );
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

        setAssignees: function ( response ) {
            var assignedToUserTypeDB     = $("#assignedToUserTypeDB").val();
            var assignedToUserDB         = $("#assignedToUserDB").val();

            var contractors = {
                "list"                 : response["contractorDetails"],
                "appendTo"            : "issueContractorResult",
                "type"                : "ownerList",
                "prefixId"             : "issueContractor",
                "radioOptionName"     : "issueRadioContractorSelected",
                "dataIdentifier"    : "contractor",
                "dispStrKey"        : "company"
            }

            if(assignedToUserDB != "" && assignedToUserTypeDB == "contractor") {
                contractors.selectId = assignedToUserDB;
            }

            _utils.createDropDownOptionsList(contractors);

            var adjusters = {
                "list"                 : response["adjusterDetails"],
                "appendTo"            : "issueAdjusterResult",
                "type"                : "ownerList",
                "prefixId"             : "issueAdjuster",
                "radioOptionName"     : "issueRadioAdjusterSelected",
                "dataIdentifier"    : "adjuster",
                "dispStrKey"        : "company_name"
            }

            if(assignedToUserDB != "" && assignedToUserTypeDB == "adjuster") {
                adjusters.selectId = assignedToUserDB;
            }

            _utils.createDropDownOptionsList(adjusters);

            if(response["customerDetails"]) {
                $("#issueAssignedToCustomer").val(response["customerDetails"][0]["first_name"]+" "+response["customerDetails"][0]["last_name"]);
                this.issueAssignedToCustomerId = response["customerDetails"]["user_sno"];
            }
        },


        showIssuesList: function ( event ) {
            var options = "open";

            if( event ) {
                options = event.target.getAttribute("data-option");
                if(options) {
                    $($(".issues.internal-tab-as-links").children()).removeClass("active");
                    $(".issues-table-list .row").hide();
                    $(event.target).addClass("active");
                } 
            } else {
                $($(".issues.internal-tab-as-links").children()).removeClass("active");
                $(".issues-table-list .row").hide();
                $($(".issues.internal-tab-as-links").children()[0]).addClass("active")
            }

            if(options == "all") {
                $(".issues-table-list .row").show();
            } else if (options != "") {
                $(".issues-table-list .row."+options).show();
            }
        },
    }
})();