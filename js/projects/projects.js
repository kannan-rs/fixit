/**
    Projects functions
*/
var _projects = (function () {
    "use strict";
    var selectedContractor = [];
    var selectedAdjuster = [];

    var viewOneAccordianList = [
        "description",
        "date",
        "address",
        "budget",
        "contractors",
        "partners",
        "tasks",
        "notes",
        "documents",
        "insurence"
    ];

    return {
        /**
            Create Project Validation
        */
        clearRest: function(excludeList) {
            //var containers = ["project_content", "task_content", "note_content", "attachment_content", "popupForAll", "contractor_content"];
            var containers = ["project_content", "popupForAll", "contractor_content"];

            for(var i=0; i < containers.length; i++) {
                if(!excludeList || !$.isArray(excludeList) || excludeList.indexOf(containers[i]) == -1) {
                    $("#"+containers[i]).html("");
                }
            }
        },

        resetCounter: function( module ) {
            switch (module) {
                case "docs":
                    this.resetNoteCounter();
                break;
                case "notes":
                    this.resetDocsCounter();
                break;
                default:
                break;
            }
        },

        resetNoteCounter: function() {
            _notes.noteRequestSent             = 0;
            _notes.noteListStartRecord         = 0;
        },

        resetDocsCounter: function() {
            _docs.docsListStartRecord            = 0;
            _docs.docsRequestSent                = 0;
        },

        toggleAccordiance: function(page, module) {
            $("#project_section_accordion").hide();
            if(page == "project" && module == "viewOne") {
                $("#project_section_accordion").show();
            }
        },

        errorMessage: function () {
            return {
                projectTitle: {
                    required: _lang.english.errorMessage.projectForm.projectTitle
                },
                description: {
                    required: _lang.english.errorMessage.projectForm.description
                },
                project_type: {
                    required: _lang.english.errorMessage.projectForm.project_type
                },
                project_status: {
                    required: _lang.english.errorMessage.projectForm.project_status
                },
                start_date: {
                    required: _lang.english.errorMessage.projectForm.start_date
                },
                end_date: {
                    required: _lang.english.errorMessage.projectForm.end_date
                },
                addressLine1: {
                    required: _lang.english.errorMessage.projectForm.addressLine1
                },
                addressLine2: {
                    required: _lang.english.errorMessage.projectForm.addressLine2
                },
                city: {
                    required: _lang.english.errorMessage.projectForm.city
                },
                country: {
                    required: _lang.english.errorMessage.projectForm.country
                },
                state: {
                    required: _lang.english.errorMessage.projectForm.state
                },
                zipCode: {
                    required: _lang.english.errorMessage.projectForm.zipCode
                },
                contractorSearchSelected: {
                    required: _lang.english.errorMessage.projectForm.contractorSearchSelected
                },
                contractorZipCode: {
                    required: _lang.english.errorMessage.projectForm.contractorZipCode
                },
                contractorSearchResult: {
                    required: _lang.english.errorMessage.projectForm.contractorSearchResult
                },
                project_budget: {
                    required: _lang.english.errorMessage.projectForm.project_budget
                },
                lend_amount: {
                    required: _lang.english.errorMessage.projectForm.lend_amount
                },
                project_lender: {
                    required: _lang.english.errorMessage.projectForm.project_lender
                },
                deductible: {
                    required: _lang.english.errorMessage.projectForm.deductible
                },
                property_owner_id: {
                    required: _lang.english.errorMessage.projectForm.property_owner_id
                },
                searchCustomerName: {
                    required: _lang.english.errorMessage.projectForm.searchCustomerName
                },
                customerNameList: {
                    required: _lang.english.errorMessage.projectForm.customerNameList
                },
                adjusterSearchSelected: {
                    required: _lang.english.errorMessage.projectForm.adjusterSearchSelected
                },
                searchAdjusterName: {
                    required: _lang.english.errorMessage.projectForm.searchAdjusterName
                },
                adjusterSearchResult: {
                    required: _lang.english.errorMessage.projectForm.adjusterSearchResult
                },
                associated_claim_num: {
                    required: _lang.english.errorMessage.projectForm.associated_claim_num
                }
            };
        },

        validationRules: function () {
            return {
                zipCode: {
                    required: true,
                   /* minlength: 5,
                    maxlength: 5,
                    digits: true*/
                }
            };
        },

        createValidate: function () {
            var cityError = false;
            var validator = $("#create_project_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation();
            if (cityError) {
                return false;
            }

            if (validator) {
                this.createSubmit();
            }
        },

        viewAll: function () {
            var fail_error = null;
            var self = this;
            _projects.resetCounter("docs");
            _projects.resetCounter("notes");
            _projects.clearRest();
            _projects.toggleAccordiance("project", "viewAll");

            $.ajax({
                method: "POST",
                url: "/projects/projects/viewAll",
                data: {},
                success: function (response) {
                    $("#project_content").html(response);
                    self.showProjectsList();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createForm: function () {
            var self = this;
            var fail_error = null;
            _projects.clearRest();
            _projects.toggleAccordiance("project", "create_project");
            $.ajax({
                method: "POST",
                url: "/projects/projects/createForm",
                data: {},
                success: function (response) {
                    $("#project_content").html(response);
                    //self.setMandatoryFields();
                    self.hideContractorDetails('all');
                    self.hideAdjusterDetails('all');
                    self.hideDropDowns();
                    _utils.setCustomerDataList();
                    _utils.setAdjusterDataList();
                    _utils.getAndSetCountryStatus("create_project_form");
                    _utils.setAsDateRangeFields({fromDateField: "start_date", toDateField: "end_date"});
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        createSubmit: function () {
            var fail_error = null;
            var self = this;
            var idPrefix = "#create_project_form ";
            var projectTitle = $(idPrefix + "#projectTitle").val();
            var description = $(idPrefix + "#description").val();
            var associated_claim_num = $(idPrefix + "#associated_claim_num").val();
            var project_type = $(idPrefix + "#project_type").val();
            var start_date = _utils.toMySqlDateFormat($(idPrefix + "#start_date").val());
            var end_date = _utils.toMySqlDateFormat($(idPrefix + "#end_date").val());
            var project_status = $(idPrefix + "#project_status").val();
            var project_budget = $(idPrefix + "#project_budget").val();
            var property_owner_id = $(idPrefix + "#property_owner_id").val();
            var contractor_id = [];
            var adjuster_id = [];//$(idPrefix + "#adjuster_id").val();
            var customer_id = $(idPrefix + "#customer_id").val();
            var remaining_budget = $(idPrefix + "#remaining_budget").val();
            var deductible = $(idPrefix + "#deductible").length ? $(idPrefix + "#deductible").val() : "";
            var project_lender = $(idPrefix + "#project_lender").val();
            var lend_amount = $(idPrefix + "#lend_amount").val();
            var addressLine1 = $(idPrefix + "#addressLine1").val();
            var addressLine2 = $(idPrefix + "#addressLine2").val();
            var city = $(idPrefix + "#city").val();
            var state = $(idPrefix + "#state").val();
            var country = $(idPrefix + "#country").val();
            var zipCode = $(idPrefix + "#zipCode").val();

            // Contractor ID is multi-select option, So clubing the values and dropping it in one MySql table field
            $(idPrefix + "#contractorSearchSelected li").each(
                function () {
                    contractor_id.push($(this).attr("data-contractorid"));
                }
           );

            if (contractor_id.length) {
                contractor_id = contractor_id.join(",");
            }

            // Adjuster ID is multi-select option, So clubing the values and dropping it in one MySql table field
            $(idPrefix + "#adjusterSearchSelected li").each(
                function () {
                    adjuster_id.push($(this).attr("data-adjusterid"));
                }
           );

            if (adjuster_id.length) {
                adjuster_id = adjuster_id.join(",");
            }

            $.ajax({
                method: "POST",
                url: "/projects/projects/add",
                data: {
                    projectTitle: projectTitle,
                    description: description,
                    associated_claim_num: associated_claim_num,
                    project_type: project_type,
                    start_date: start_date,
                    end_date: end_date,
                    project_status: project_status,
                    project_budget: project_budget,
                    property_owner_id: property_owner_id,
                    contractor_id: contractor_id,
                    adjuster_id: adjuster_id,
                    customer_id: customer_id,
                    remaining_budget: remaining_budget,
                    deductible: deductible,
                    project_lender: project_lender,
                    lend_amount: lend_amount,
                    addressLine1: addressLine1,
                    addressLine2: addressLine2,
                    city: city,
                    state: state,
                    country: country,
                    zipCode: zipCode
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        self.viewOne(response.insertedId);
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

        editProject: function () {
            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/projects/editForm",
                data: {
                    projectId: self.projectId
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    self.openDialog({title: "Edit Project"});
                    self.setDropdownValue();
                    //self.setMandatoryFields();
                    self.hideContractorDetails('all');
                    self.hideAdjusterDetails('all');
                    self.hideDropDowns();
                    self.getContractorDetails($("#contractorIdDb").val());
                    self.getAdjusterDetails($("#adjusterIdDb").val());
                    self.hideDropDowns();

                    _utils.setCustomerDataList();
                    _utils.setAdjusterDataList();
                    _utils.getAndSetCountryStatus("update_project_form");
                    _utils.setAddressByCity();
                    _utils.getAndSetMatchCity($("#city_jqDD").val(), "edit");
                    _utils.setAsDateRangeFields({fromDateField: "start_date", toDateField: "end_date"});
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        updateValidate: function () {
            var cityError = false;
            var validator = $("#update_project_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            }).form();

            cityError = _utils.cityFormValidation();
            if (cityError) {
                return false;
            }

            if (validator) {
                this.updateSubmit();
            }
        },

        updateSubmit: function () {
            var fail_error = null;
            var self = this;
            var idPrefix = "#update_project_form ";
            var project_sno = $(idPrefix + "#project_sno").val();
            var projectTitle = $(idPrefix + "#projectTitle").val();
            var description = $(idPrefix + "#description").val();
            var associated_claim_num = $(idPrefix + "#associated_claim_num").val();
            var project_type = $(idPrefix + "#project_type").val();
            var start_date = _utils.toMySqlDateFormat($(idPrefix + "#start_date").val());
            var end_date = _utils.toMySqlDateFormat($(idPrefix + "#end_date").val());
            var project_status = $(idPrefix + "#project_status").val();
            var project_budget = $(idPrefix + "#project_budget").val();
            var property_owner_id = $(idPrefix + "#property_owner_id").val();
            var contractor_id = [];
            var adjuster_id = [];//$(idPrefix + "#adjuster_id").val();
            var customer_id = $(idPrefix + "#customer_id").val();
            var remaining_budget = $(idPrefix + "#remaining_budget").val();
            var deductible = $(idPrefix + "#deductible").length ? $(idPrefix + "#deductible").val() : "";
            var project_lender = $(idPrefix + "#project_lender").val();
            var lend_amount = $(idPrefix + "#lend_amount").val();
            var addressLine1 = $(idPrefix + "#addressLine1").val();
            var addressLine2 = $(idPrefix + "#addressLine2").val();
            var city = $(idPrefix + "#city").val();
            var state = $(idPrefix + "#state").val();
            var country = $(idPrefix + "#country").val();
            var zipCode = $(idPrefix + "#zipCode").val();

            // Contractor ID is multi-select option, So clubing the values and dropping it in one MySql table field
            $("#contractorSearchSelected li").each(
                function () {
                    contractor_id.push($(this).attr("data-contractorid"));
                }
           );

            if (contractor_id.length) {
                contractor_id = contractor_id.join(",");
            }

            // Adjuster ID is multi-select option, So clubing the values and dropping it in one MySql table field
            $(idPrefix + "#adjusterSearchSelected li").each(
                function () {
                    adjuster_id.push($(this).attr("data-adjusterid"));
                }
           );

            if (adjuster_id.length) {
                adjuster_id = adjuster_id.join(",");
            }

            $.ajax({
                method: "POST",
                url: "/projects/projects/update",
                data: {
                    project_sno: project_sno,
                    projectTitle: projectTitle,
                    description: description,
                    associated_claim_num: associated_claim_num,
                    project_type: project_type,
                    start_date: start_date,
                    end_date: end_date,
                    project_status: project_status,
                    project_budget: project_budget,
                    property_owner_id: property_owner_id,
                    contractor_id: contractor_id,
                    adjuster_id: adjuster_id,
                    customer_id: customer_id,
                    remaining_budget: remaining_budget,
                    deductible: deductible,
                    project_lender: project_lender,
                    lend_amount: lend_amount,
                    addressLine1: addressLine1,
                    addressLine2: addressLine2,
                    city: city,
                    state: state,
                    country: country,
                    zipCode: zipCode

                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        $(".ui-button").trigger("click");
                        self.viewOne(response.updatedId);
                        alert(response.message);
                    } else if (response.status.toLowerCase() === "error") {
                        alert(response.message);
                    }
                },
                error: function (error) {
                    alert(error);
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
                alert(fail_error);
            });
        },

        deleteRecord: function () {
            var fail_error = null;
            var self = this;
            var deleteConfim = confirm("Do you want to delete this project");

            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/projects/projects/deleteRecord",
                data: {
                    projectId: self.projectId
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        self.viewAll();
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

        viewOne: function (projectId, options) {
            this.projectId = projectId;
            var self = this;
            _projects.clearRest();
            _projects.toggleAccordiance("project", "viewOne");

            var triggeredBy = (options && options.viewTriggeredBy) ? options.viewTriggeredBy : "";

            // Project Details View
            setTimeout(function () {
                self.getProjectDetails(options);
                setTimeout(function () {
                    self.getProjectTasksList();
                    self.getProjectNotesList();
                    self.getProjectDocumentList();
                }, 1000);
            }, 0);

        },

        exportCSV: function (projectId) {
            var exp = window.open("http://" + window.location.hostname + "/main/projects/exportCSV/" + projectId, "_blank");
        },

        getProjectDetails: function (options) {
            var fail_error = null;
            var self = this;
            var taskId = (options && options.taskId) ? options.taskId : "";
            var triggeredBy = (options && options.triggeredBy) ? options.triggeredBy : "";
            var defaultAccordian = (triggeredBy === "issues" && taskId !== "") ? this.viewOneAccordianList.indexOf("tasks") : 0;

            $.ajax({
                method: "POST",
                url: "/projects/projects/viewOne",
                data: {
                    projectId: self.projectId
                },
                success: function (response) {
                    $("#project_content").html(response);
                    $("#accordion").accordion(
                        {
                            collapsible: true,
                            icons: {header: "ui-icon-plus", activeHeader: "ui-icon-minus"},
                            active: defaultAccordian
                        }
                   );
                    $("#projectDescrAccordion").accordion(
                        {
                            collapsible: true,
                            icons: {header: "ui-icon-plus", activeHeader: "ui-icon-minus"},
                            active: false
                        }
                   );
                    $("#contractor_accordion").accordion(
                        {
                            collapsible: true,
                            icons: {header: "ui-icon-triangle-1-e", activeHeader: "ui-icon-triangle-1-s"},
                            active: false
                        }
                   );
                    $("#partner_accordion").accordion(
                        {
                            collapsible: true,
                            icons: {header: "ui-icon-triangle-1-e", activeHeader: "ui-icon-triangle-1-s"},
                            active: false
                        }
                   );
                    //budgetFormat();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        getProjectTasksList: function () {
            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/tasks/viewAll",
                data: {
                    projectId: self.projectId,
                    viewFor: 'projectViewOne'
                },
                success: function (response) {
                    $("#task_content").html(response);
                    var taskCount = $("#tasksCount").val();
                    taskCount = (taskCount && taskCount !== "" || taskCount > 0) ? " (" + taskCount + ")" : '';
                    $("#taskCountDisplay").html(taskCount);

                    _tasks.showTaskList();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        getProjectNotesList: function () {
            var fail_error = null;
            var self = this;
            var taskId = "";
            var noteId = "";
            var noteCount = "";
            $.ajax({
                method: "POST",
                url: "/projects/notes/viewAll",
                data: {
                    projectId: self.projectId,
                    taskId: taskId,
                    noteId: noteId,
                    startRecord: _notes.noteListStartRecord,
                    count: _notes.noteListCount,
                    viewFor: 'projectViewOne'
                },
                success: function (response) {
                    if (response.length) {
                        $("#notesCount").remove();
                        $("#note_content").html(response);

                        _notes.noteRequestSent = _notes.noteListStartRecord;
                        _notes.noteListStartRecord += 5;

                        var noteCount = $("#notesCountForProject").val();
                        noteCount = (noteCount && noteCount !== "" || noteCount > 0) ? " (" + noteCount + ")" : '';
                        $("#notesCountForProjectDisplay").html(noteCount);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        getTaskNotesList: function (taskId, startingRecord) {
            var fail_error = null;
            var self = this;
            var noteId = "";
            $.ajax({
                method: "POST",
                url: "/projects/notes/viewAll",
                data: {
                    projectId: self.projectId,
                    taskId: taskId,
                    noteId: noteId,
                    startRecord: 0,
                    count: _notes.noteListCount,
                    viewFor: 'projectViewOne'
                },
                success: function (response) {
                    if (response.length) {
                        $("#popupForAll").html(response);
                        self.openDialog({title: "Nots List for Task"});
                        self.addTaskNote(taskId);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        addTaskNote: function (taskId) {
            var fail_error = null;
            $.ajax({
                method: "POST",
                url: "/projects/notes/createForm",
                data: {
                    projectId: self.projectId,
                    taskId: taskId,
                    viewFor: 'projectViewOne'
                },
                success: function (response) {
                    $("#popupForAll").append(response);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        getProjectDocumentList: function () {
            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/docs/viewAll",
                data: {
                    projectId: self.projectId,
                    startRecord: _docs.docsListStartRecord
                },
                success: function (response) {
                    if (response.length) {
                        $("#attachment_content").html(response);

                        var docsCount = $("#docsCount").val();
                        docsCount = (docsCount && docsCount !== "" || docsCount > 0) ? " (" + docsCount + ")" : '';
                        $("#docsCountDisplay").html(docsCount);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        addDocumentForm: function () {
            var fail_error = null;
            var self = this;
            event.stopPropagation();
            $.ajax({
                method: "POST",
                url: "/projects/docs/createForm",
                data: {
                    projectId: self.projectId
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    self.openDialog({title: "Add Document"});
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        documentDelete: function (doc_id) {
            var fail_error = null;
            $.ajax({
                method: "POST",
                url: "/projects/docs/deleteRecord",
                data: {
                    docId: doc_id
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        $("#docId_" + response.docId).remove();
                        alert(response.message);
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

        taskDelete: function (task_id, project_id) {
            var fail_error = null;
            var deleteConfim = confirm("Do you want to delete the task");

            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/projects/tasks/deleteRecord",
                data: {
                    task_id: task_id,
                    project_id: project_id
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        $("#task_" + task_id).remove();
                        $("#popupForAll").dialog("close");
                        $("#popupForAll2").dialog("close");
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

        notesDelete: function (noteId, taskId) {
            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/notes/deleteRecord",
                data: {
                    noteId: noteId
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        $("#notes_" + noteId).hide();
                        $("#notes_" + noteId).remove();
                        if (taskId && taskId > 0) {
                            $(".ui-button").trigger("click");
                            self.getTaskNotesList(taskId, 0);
                        }
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
        },

        taskViewOne: function (taskId) {
            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/tasks/viewOne",
                data: {
                    taskId: taskId,
                    viewFor: 'projectViewOne'
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    self.openDialog({title: "Task Details"});

                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        addTask: function () {
            var fail_error = null;
            var dateOptions = null;
            var self = this;
            event.stopPropagation();
            $.ajax({
                method: "POST",
                url: "/projects/tasks/createForm",
                data: {
                    projectId: self.projectId,
                    viewFor: 'projectViewOne'
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    self.openDialog({title: "Add Task"});
                    self.setTaskOwnerListForContractorByID($("#contractorIdDb").val());
                    self.setTaskOwnerListForAdjusterByID($("#adjusterIdDb").val());

                    dateOptions = {
                        fromDateField: "task_start_date",
                        toDateField: "task_end_date"
                    };
                    _utils.setAsDateRangeFields(dateOptions);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        addProjectNote: function () {
            var fail_error = null;
            var self = this;
            event.stopPropagation();

            $.ajax({
                method: "POST",
                url: "/projects/notes/createForm",
                data: {
                    projectId: self.projectId,
                    taskId: "",
                    viewFor: 'projectViewOne'
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    self.openDialog({title: "Add Notes"});
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        editTask: function (taskId) {
            var fail_error = null;
            var dateOptions = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/tasks/editForm",
                data: {
                    taskId: taskId,
                    viewFor: 'projectViewOne'
                },
                success: function (response) {
                    $("#popupForAll").html(response);
                    self.openDialog({title: "Edit Task Details"});
                    self.setTaskOwnerListForContractorByID($("#contractorIdDb").val());
                    self.setTaskOwnerListForAdjusterByID($("#adjusterIdDb").val());
                    _tasks.setDropdownValue();
                    setTimeout(function () {_tasks.setOwnerOption();}, 100);

                    dateOptions = {
                        fromDateField: "task_start_date",
                        toDateField: "task_end_date"
                    };
                    _utils.setAsDateRangeFields(dateOptions);
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        taskCreateSubmit: function () {
            var fail_error = null;
            var self = this;
            var parentId = $("#projectId").val();
            var task_name = $("#task_name").val();
            var task_desc = $("#task_desc").val();
            var task_start_date = _utils.toMySqlDateFormat($("#task_start_date").val());
            var task_end_date = _utils.toMySqlDateFormat($("#task_end_date").val());
            var task_status = $("#task_status").val();
            var task_owner_id = "";
            var task_percent_complete = $("#task_percent_complete").val();
            var task_dependency = $("#task_dependency").val();
            var task_trade_type = $("#task_trade_type").val();

            var ownerSelected = $("input[type='radio'][name='optionSelectedOwner']:checked");
            if (ownerSelected.length > 0) {
                task_owner_id = ownerSelected.val();
            }

            $.ajax({
                method: "POST",
                url: "/projects/tasks/add",
                data: {
                    parentId: parentId,
                    task_name: task_name,
                    task_desc: task_desc,
                    task_start_date: task_start_date,
                    task_end_date: task_end_date,
                    task_status: task_status,
                    task_owner_id: task_owner_id,
                    task_percent_complete: task_percent_complete,
                    task_dependency: task_dependency,
                    task_trade_type: task_trade_type
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        self.getProjectTasksList(projectId);
                        self.taskViewOne(response.insertedId);
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

        noteCreateSubmit: function () {
            var fail_error = null;
            var self = this;
            var taskId = $("#taskId").val();
            var noteName = $("#noteName").val();
            var noteContent = $("#noteContent").val();

            $.ajax({
                method: "POST",
                url: "/projects/notes/add",
                data: {
                    projectId: self.projectId,
                    taskId: taskId,
                    noteName: noteName,
                    noteContent: noteContent
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {

                        $(".ui-button").trigger("click");
                        if (taskId && taskId !== "" && taskId > 0) {
                            self.getTaskNotesList(taskId);
                        } else {
                            self.getProjectNotesList();
                        }

                        alert(response.message);
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

        taskUpdateSubmit: function () {
            var fail_error = null;
            var self = this;
            var task_id = $("#task_sno").val();
            var task_name = $("#task_name").val();
            var task_desc = $("#task_desc").val();
            var task_start_date = _utils.toMySqlDateFormat($("#task_start_date").val());
            var task_end_date = _utils.toMySqlDateFormat($("#task_end_date").val());
            var task_status = $("#task_status").val();
            var task_owner_id = "";
            var task_percent_complete = $("#task_percent_complete").val();
            var task_dependency = $("#task_dependency").val();
            var task_trade_type = $("#task_trade_type").val();

            var ownerSelected = $("input[type='radio'][name='optionSelectedOwner']:checked");
            if (ownerSelected.length > 0) {
                task_owner_id = ownerSelected.val();
            }

            $.ajax({
                method: "POST",
                url: "/projects/tasks/update",
                data: {
                    task_id: task_id,
                    task_name: task_name,
                    task_desc: task_desc,
                    task_start_date: task_start_date,
                    task_end_date: task_end_date,
                    task_status: task_status,
                    task_owner_id: task_owner_id,
                    task_percent_complete: task_percent_complete,
                    task_dependency: task_dependency,
                    task_trade_type: task_trade_type
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        alert(response.message);
                        self.getProjectTasksList();
                        self.taskViewOne(task_id);
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

        setDropdownValue: function () {
            var db_project_type = $("#db_project_type").val();
            var db_project_status = $("#db_project_status").val();

            $("#project_type").val(db_project_type);
            $("#project_status").val(db_project_status);

        },

        openDialog: function (options, popupType) {
            if (popupType && popupType !== "") {
                this.openDialog2(options);
            } else {
                if (typeof(this.popupDialog) === "undefined") {
                    this.popupDialog = $("#popupForAll").dialog({
                        autoOpen: false,
                        maxHeight: 700,
                        width: 700,
                        modal: true
                    });
                }
                this.popupDialog.dialog("open");
                if (typeof(options) !== "undefined") {
                    this.popupDialog.dialog("option", "title", options.title);
                }
            }
        },

        openDialog2: function (options) {
            if (typeof(this.popupDialog2) === "undefined") {
                this.popupDialog2 = $("#popupForAll2").dialog({
                    autoOpen: false,
                    maxHeight: 600,
                    width: 600,
                    modal: true
                });
            }
            this.popupDialog2.dialog("open");
            if (typeof(options) !== "undefined") {
                this.popupDialog2.dialog("option", "title", options.title);
            }
        },

        closeDialog: function (options) {
            var popupType = (options && options.popupType) ? options.popupType : "";
            $("#popupForAll" + popupType).dialog("close");
        },

        hideContractorDetails: function (hide) {
            if (!hide || hide === "" || hide === "all" || hide === "results") {
                $(".contractor-search-result").hide();
            }
            if (!hide || hide === "" || hide === "all" || hide === "selected") {
                $(".contractor-search-selected").hide();
            }
        },

        showContractorDetails: function (show) {
            if (!show || show === "" || show === "all" || show === "results") {
                $(".contractor-search-result").show();
            }
            if (!show || show === "" || show === "all" || show === "selected") {
                $(".contractor-search-selected").show();
            }
        },

        hideAdjusterDetails: function (hide) {
            if (!hide || hide === "" || hide === "all" || hide === "results") {
                $(".adjuster-search-result").hide();
            }
            if (!hide || hide === "" || hide === "all" || hide === "selected") {
                console.log("hideFN");
                $(".adjuster-search-selected").hide();
            }
        },

        showAdjusterDetails: function (show) {
            if (!show || show === "" || show === "all" || show === "results") {
                $(".adjuster-search-result").show();
            }
            if (!show || show === "" || show === "all" || show === "selected") {
                $(".adjuster-search-selected").show();
            }
        },

        hideDropDowns: function () {
            $(".adjuster-search-result").hide();
            $(".customer-search-result").hide();
        },

        getContractorDetails: function (records) {
            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/contractors/getList",
                data: {
                    records: records
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status === "success") {
                        var contractors = {
                            list: response.contractors,
                            appendTo: "contractorSearchSelected",
                            type: "selectedList",
                            prefixId: "contractorSearch",
                            dataIdentifier: "contractor",
                            dispStrKey: "company"
                        };
                        _utils.createDropDownOptionsList(contractors);

                        self.selectedContractor = [];
                        $("#contractorSearchSelected li").each(
                            function () {
                                selectedContractor.push($(this).attr("id"));
                            }
                       );
                        self.showContractorDetails("selected");
                    } else {
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

        setContractorDetails: function () {
            setTimeout(function () {
                $("#contractorId").val($("#contractorIdDb").val().split(","));
            }, 100);
        },

        updateContractorSelectionList: function () {
            var fail_error = null;
            var contractors = null;
            var i;
            $.ajax({
                method: "POST",
                url: "/projects/contractors/getList",
                data: {},
                success: function (response) {
                    response = $.parseJSON(response);
                    $("#contractorId").children().remove();
                    if (response.status === "success") {
                        contractors = response.contractors;
                        for (i = 0; i < contractors.length; i += 1) {
                            $('#contractorId').append($('<option>', {
                                value: contractors[i].id,
                                text: contractors[i].name
                            }));
                        }
                    } else {
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

        setSelectedContractor: function () {
            $("#contractorIdDb").val($("#contractorId").val().join(","));
        },

        getContractorListUsingServiceZip: function (prefix) {
            var fail_error = null;
            var self = this;
            var serviceZip = $("#contractorZipCode").val();

            $.ajax({
                method: "POST",
                url: "/projects/contractors/getList",
                data: {
                    serviceZip: serviceZip
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        //response.contractorList;
                        $("#contractorSearchResult").children().remove();

                        var contractors = {
                            list: response.contractors,
                            appendTo: "contractorSearchResult",
                            type: "searchList",
                            excludeList: self.selectedContractor,
                            prefixId: "contractorSearch",
                            actionButton: "plus",
                            dataIdentifier: "contractor",
                            dispStrKey: "company"
                        };
                        _utils.createDropDownOptionsList(contractors);
                        //$('#contractorSearchResult li .ui-icon').hide();
                        self.showContractorDetails('all');
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

        /*
            Contractor Search Add and Remove
            for create and Edit form
        */
        searchContractorAction: function (events) {
            var element = event.target;
            var modifyactionToDo = null;
            var clickedId = $(element).attr("data-contractorid");
            var prefixId = $(element).attr("data-prefixid");
            var actionToDo = $(element).hasClass("ui-icon-plus") ? "add" : "";
            actionToDo = $(element).hasClass("ui-icon-minus") ? "remove" : actionToDo;

            if (actionToDo === "add") {
                $("#" + prefixId + "Selected").append($("#" + prefixId + clickedId));
                $("#" + prefixId + "Selected li .ui-icon-plus").removeClass("ui-icon-plus").addClass("ui-icon-minus");
                modifyactionToDo = "remove";
            } else if (actionToDo === "remove") {
                element.parentElement.remove();
            }

            if (actionToDo === "add" || actionToDo === "remove") {
                selectedContractor = [];
                $("#" + prefixId + "Selected li").each(
                    function () {
                        selectedContractor.push($(this).attr("data-contractorid"));
                    }
               );
            }

            if (!$("#" + prefixId + "Result").children().length) {
                $(".contractor-search-result").hide();
            }
            if (!$("#" + prefixId + "Selected").children().length) {
                $(".contractor-search-selected").hide();
            }
        },

        getAdjusterListUsingNameCompany: function (prefix) {
            var fail_error = null;
            var self = this;
            var nameOrComp = $("#searchAdjusterName").val();

            $.ajax({
                method: "POST",
                url: "/projects/partners/getList",
                data: {
                    companyName: nameOrComp,
                    name: nameOrComp
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        //response.adjusterList;
                        $("#adjusterSearchResult").children().remove();

                        var adjusters = {
                            list: response.partners,
                            appendTo: "adjusterSearchResult",
                            type: "searchList",
                            excludeList: self.selectedAdjuster,
                            prefixId: "adjusterSearch",
                            actionButton: "plus",
                            dataIdentifier: "adjuster",
                            dispStrKey: "company_name"
                        };
                        _utils.createDropDownOptionsList(adjusters);
                        self.showAdjusterDetails('all');
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

        getAdjusterDetails: function (records) {
            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/partners/getList",
                data: {
                    records: records
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status === "success") {
                        var adjusters = {
                            list: response.partners,
                            appendTo: "adjusterSearchSelected",
                            type: "selectedList",
                            prefixId: "adjusterSearch",
                            dataIdentifier: "adjuster",
                            dispStrKey: "company_name"
                        };
                        _utils.createDropDownOptionsList(adjusters);

                        self.selectedAdjuster = [];
                        $("#adjusterSearchSelected li").each(
                            function () {
                                self.selectedAdjuster.push($(this).attr("id"));
                            }
                       );
                        self.showAdjusterDetails("selected");
                    } else {
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

        searchAdjusterAction: function (events) {
            var element = event.target;
            var modifyactionToDo = null;
            var clickedId = $(element).attr("data-adjusterid");
            var prefixId = $(element).attr("data-prefixid");
            var actionToDo = $(element).hasClass("ui-icon-plus") ? "add" : "";
            actionToDo = $(element).hasClass("ui-icon-minus") ? "remove" : actionToDo;

            if (actionToDo === "add") {
                $("#" + prefixId + "Selected").append($("#" + prefixId + clickedId));
                $("#" + prefixId + "Selected li .ui-icon-plus").removeClass("ui-icon-plus").addClass("ui-icon-minus");
                modifyactionToDo = "remove";
            } else if (actionToDo === "remove") {
                element.parentElement.remove();
            }

            if (actionToDo === "add" || actionToDo === "remove") {
                selectedAdjuster = [];
                $("#" + prefixId + "Selected li").each(
                    function () {
                        selectedAdjuster.push($(this).attr("data-adjusterid"));
                    }
               );
            }

            if (!$("#" + prefixId + "Result").children().length) {
                $(".adjuster-search-result").hide();
            }
            if (!$("#" + prefixId + "Selected").children().length) {
                console.log("setActionFN");
                $(".adjuster-search-selected").hide();
            }
        },

        setTaskOwnerListForContractorByID: function (records) {
            var fail_error = null;
            if (!records) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/projects/contractors/getList",
                data: {
                    records: records
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status === "success") {
                        var contractors = {
                            list: response.contractors,
                            appendTo: "ownerSearchResult",
                            type: "ownerList",
                            prefixId: "ownerSearch",
                            radioOptionName: "optionSelectedOwner",
                            valuePrefix: "contractor",
                            dataIdentifier: "contractor",
                            dispStrKey: "company"
                        };
                        _utils.createDropDownOptionsList(contractors);
                    } else {
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

        setTaskOwnerListForAdjusterByID: function (records) {
            var fail_error = null;
            if (!records) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/projects/partners/getList",
                data: {
                    records: records
                },
                success: function (response) {
                    response = $.parseJSON(response);
                    if (response.status.toLowerCase() === "success") {
                        //response.adjusterList;
                        $("#adjusterSearchResult").children().remove();

                        var adjusters = {
                            list: response.partners,
                            appendTo: "ownerSearchResult",
                            type: "ownerList",
                            prefixId: "ownerSearch",
                            radioOptionName: "optionSelectedOwner",
                            valuePrefix: "adjuster",
                            dataIdentifier: "adjuster",
                            dispStrKey: "company_name"
                        };
                        _utils.createDropDownOptionsList(adjusters);

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

        viewOnlyBudget: function () {
            var fail_error = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/projects/projects/viewOnlyBudget",
                data: {
                    projectId: self.projectId
                },
                success: function (response) {
                    $("#viewOneProjectBudget").html(response);
                    //budgetFormat();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },

        showCustomerListInDropDown: function () {
            var customer = $("#searchCustomerName").val();
            var i;
            $(".customer-search-result").show();
            $("#customerNameList").show();

            for (i = 0; i < $("#customerNameList").children().length; i += 1) {
                if ($($("#customerNameList").children()[i]).text().indexOf(customer) > -1) {
                    $($("#customerNameList").children()[i]).show();
                } else {
                    $($("#customerNameList").children()[i]).hide();
                }
            }
        },

        showAdjusterListInDropDown: function () {
            var adjuster = $("#searchAdjusterName").val();
            var i;
            $(".adjuster-search-result").show();
            $("#adjusterNameList").show();

            for (i = 0; i < $("#adjusterNameList").children().length; i += 1) {
                if ($($("#adjusterNameList").children()[i]).text().indexOf(adjuster) > -1) {
                    $($("#adjusterNameList").children()[i]).show();
                } else {
                    $($("#adjusterNameList").children()[i]).hide();
                }
            }
        },

        setCustomerId: function (event, element, options) {
            $("#searchCustomerName").val(options.first_name + " " + options.last_name);
            $("#customer_id").val(options.searchId);
        },

        setAdjusterId: function (event, element, options) {
            $("#searchAdjusterName").val(options.first_name + " " + options.last_name);
            $("#adjuster_id").val(options.searchId);
        },

        budgetFormat: function () {
            $(".dollers").each(function () {
                console.log($(this).html());
                $(this).html("$ " +_utils.toDisplayNumberFormat($(this).html()));
            });
        },

        showProjectsList: function (event) {
            var options = "open";

            if (event) {
                options = event.target.getAttribute("data-option");
                if (options) {
                    $($(".projects.internal-tab-as-links").children()).removeClass("active");
                    $(".projects-table-list .row").hide();
                    $(event.target).addClass("active");
                }
            } else {
                $($(".projects.internal-tab-as-links").children()).removeClass("active");
                $(".projects-table-list .row").hide();
                $($(".projects.internal-tab-as-links").children()[0]).addClass("active");
            }

            if (options === "all") {
                $(".projects-table-list .row").show();
            } else if (options !== "") {
                $(".projects-table-list .row." + options).show();
            }
        },
        viewOnlyExpandAll: function () {
            var icons = $( "#accordion" ).accordion( "option", "icons" );
            //$('.open').click(function () {
            $('#accordion > .ui-accordion-header').removeClass('ui-corner-all').addClass('ui-accordion-header-active ui-state-active ui-corner-top').attr({
                'aria-selected': 'true',
                'tabindex': '0'
            });
            $('#accordion > .ui-accordion-header > .ui-accordion-header-icon').removeClass(icons.header).addClass(icons.activeHeader);
            $('#accordion > .ui-accordion-content').addClass('ui-accordion-content-active').attr({
                'aria-expanded': 'true',
                'aria-hidden': 'false'
            }).show();
            //});
        },
        viewOnlyCollapseAll: function() {
            var icons = $( "#accordion" ).accordion( "option", "icons" );
            //$('.close').click(function () {
            $('#accordion > .ui-accordion-header').removeClass('ui-accordion-header-active ui-state-active ui-corner-top').addClass('ui-corner-all').attr({
                'aria-selected': 'false',
                'tabindex': '-1'
            });
            $('#accordion > .ui-accordion-header > .ui-accordion-header-icon').removeClass(icons.activeHeader).addClass(icons.header);
            $('#accordion > .ui-accordion-content').removeClass('ui-accordion-content-active').attr({
                'aria-expanded': 'false',
                'aria-hidden': 'true'
            }).hide();
            //});
        }
    };
})();

window.onscroll = function() {
    if($("#note_content").text().length) {
        /*
        var content_height = $(document).height();
        var content_scroll_pos = $(window).scrollTop();
        var percentage_value = content_scroll_pos * 100 / content_height;
        
        if(percentage_value > 50) {
            var projectId = $("#projectId").val();
            _notes.viewAll(projectId);
        }
        */
    } else if($("#attachment_list").text().length) {
/*        var content_height = $(document).height();
        var content_scroll_pos = $(window).scrollTop();
        var percentage_value = content_scroll_pos * 100 / content_height;
        
        if(percentage_value > 20) {
            var projectId = $("#projectId").val();
            _docs.viewAll( projectId );
        }
*/    }
}


$(document).on("click", function(e) {
    if (e && e.target && 
        (e.target.id == "contractorSearchResult" || 
            (e.target.parentElement && 
                (e.target.parentElement.id == "contractorSearchResult" || 
                    (e.target.parentElement.parentElement && e.target.parentElement.parentElement.id == "contractorSearchResult")
                )
            )
        )
    )
        return;

    if (e && e.target && 
        (e.target.id == "contractorSearchSelected" || 
            (e.target.parentElement && 
                (e.target.parentElement.id == "contractorSearchSelected" || 
                    (e.target.parentElement.parentElement && e.target.parentElement.parentElement.id == "contractorSearchSelected")
                )
            )
        )
    )
        return;

    if (e && e.target && 
        (e.target.id == "adjusterSearchResult" || 
            (e.target.parentElement && 
                (e.target.parentElement.id == "adjusterSearchResult" || 
                    (e.target.parentElement.parentElement && e.target.parentElement.parentElement.id == "adjusterSearchResult")
                )
            )
        )
    )
        return;

    if (e && e.target && 
        (e.target.id == "adjusterSearchSelected" || 
            (e.target.parentElement && 
                (e.target.parentElement.id == "adjusterSearchSelected" || 
                    (e.target.parentElement.parentElement && e.target.parentElement.parentElement.id == "adjusterSearchSelected")
                )
            )
        )
    )
        return;

    if (e && e.target && 
        (e.target.id == "customerNameList" || 
            (e.target.parentElement && 
                (e.target.parentElement.id == "customerNameList" || 
                    (e.target.parentElement.parentElement && e.target.parentElement.parentElement.id == "customerNameList")
                )
            )
        )
    )
        return;

    if (e && e.target && 
        (e.target.id == "partnerNameList" || 
            (e.target.parentElement && 
                (e.target.parentElement.id == "partnerNameList" || 
                    (e.target.parentElement.parentElement && e.target.parentElement.parentElement.id == "partnerNameList")
                )
            )
        )
    )
        return;

    $(".contractor-search-result").hide();
    $(".adjuster-search-result").hide();
    $(".customer-search-result").hide();
    $(".partner-search-result").hide();
});