/**
    Tasks functions
*/
function task() {

};

/*
task.prototype.viewAll = function( projectId ) {
    projectObj.resetCounter("docs");
    projectObj.resetCounter("notes");
    projectObj.clearRest();
    projectObj.toggleAccordiance("task");
    $.ajax({
        method: "POST",
        url: "/projects/tasks/viewAll",
        data: {
            projectId: projectId
        },
        success: function( response ) {
            $("#task_content").html(response);
        },
        error: function( error ) {
            error = error;
        }
    })
    .fail(function ( failedObj ) {
        fail_error = failedObj;
    });
};
*/

/*
task.prototype.createForm = function( projectId ) {
    projectObj.clearRest();
    projectObj.toggleAccordiance("task");
    $.ajax({
        method: "POST",
        url: "/projects/tasks/createForm",
        data: {
            projectId: projectId
        },
        success: function( response ) {
            $("#task_content").html(response);
        },
        error: function( error ) {
            error = error;
        }
    })
    .fail(function ( failedObj ) {
        fail_error = failedObj;
    });
};
*/

task.prototype.errorMessage = function () {
    return {
        task_name : {
            required : _lang.english.errorMessage.taskForm.task_name
        },
        task_desc : {
            required : _lang.english.errorMessage.taskForm.task_desc
        },
        task_start_date : {
            required : _lang.english.errorMessage.taskForm.task_start_date
        },
        task_end_date : {
            required : _lang.english.errorMessage.taskForm.task_end_date
        },
        task_status : {
            required : _lang.english.errorMessage.taskForm.task_status
        },
        task_percent_complete : {
            required : _lang.english.errorMessage.taskForm.task_percent_complete
        },
        task_dependency : {
            required : _lang.english.errorMessage.taskForm.task_dependency
        },
        task_trade_type : {
            required : _lang.english.errorMessage.taskForm.task_trade_type
        }
    };
}

task.prototype.validationRules = function() {
    return {
        task_end_date: {
            greaterThanOrEqualTo: "#task_start_date"
        }
    }
}

task.prototype.createValidate =  function ( viewFor ) {
    var validator = $("#create_task_form").validate({
        rules: this.validationRules(),
        messages: this.errorMessage()
    });

    if(validator.form()) {
        if(!viewFor) {
            projectObj._tasks.createSubmit();
        } else {
            projectObj._projects.taskCreateSubmit();
        }
    }
};

/*task.prototype.createSubmit = function() {
    parentId                     = $("#parentId").val();
    task_name                     = $("#task_name").val();
    task_desc                     = $("#task_desc").val();
    task_start_date             = $("#task_start_date").val();
    task_end_date                 = $("#task_end_date").val();
    task_status                    = $("#task_status").val();
    task_owner_id                = $("#task_owner_id").val();
    task_percent_complete        = $("#task_percent_complete").val();
    task_dependency                = $("#task_dependency").val();
    task_trade_type                = $("#task_trade_type").val();

    $.ajax({
        method: "POST",
        url: "/projects/tasks/add",
        data: {
            parentId:                 parentId,
            task_name:                 task_name,
            task_desc:                 task_desc,
            task_start_date:         task_start_date,
            task_end_date:             task_end_date,
            task_status:             task_status,
            task_owner_id:             task_owner_id,
            task_percent_complete:     task_percent_complete,
            task_dependency:         task_dependency,
            task_trade_type:         task_trade_type
        },
        success: function( response ) {
            response = $.parseJSON(response);
            if(response.status.toLowerCase() == "success") {
                alert(response.message);
                projectObj._tasks.viewOne(response.insertedId);
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
}*/

/*task.prototype.editTask = function(taskId) {
    projectObj.clearRest();
    projectObj.toggleAccordiance("task");
    $.ajax({
        method: "POST",
        url: "/projects/tasks/editForm",
        data: {
            'taskId' : taskId
        },
        success: function( response ) {
            $("#project_content").html(response);
            projectObj._tasks.setDropdownValue();
        },
        error: function( error ) {
            error = error;
        }
    })
    .fail(function ( failedObj ) {
        fail_error = failedObj;
    });
};*/

task.prototype.updateValidate = function( viewFor ) {
    var validator = $("#update_task_form").validate({
        rules: this.validationRules(),
        messages: this.errorMessage()
    });

    if(validator.form()) {
        if(!viewFor) {
            projectObj._tasks.updateSubmit();
        } else {
            projectObj._projects.taskUpdateSubmit();
        }
    }
};

/*
task.prototype.updateSubmit = function() {
    task_id                 = $("#task_sno").val();
    task_name                 = $("#task_name").val();
    task_desc                 = $("#task_desc").val();
    task_start_date         = $("#task_start_date").val();
    task_end_date             = $("#task_end_date").val();
    task_status             = $("#task_status").val();
    task_owner_id             = $("#task_owner_id").val();
    task_percent_complete    = $("#task_percent_complete").val();
    task_dependency            = $("#task_dependency").val();
    task_trade_type            = $("#task_trade_type").val();


    $.ajax({
        method: "POST",
        url: "/projects/tasks/update",
        data: {
            task_id:                 task_id,
            task_name:                 task_name,
            task_desc:                 task_desc,
            task_start_date:         task_start_date,
            task_end_date:             task_end_date,
            task_status:             task_status,
            task_owner_id:             task_owner_id,
            task_percent_complete:     task_percent_complete,
            task_dependency:         task_dependency,
            task_trade_type:         task_trade_type
        },
        success: function( response ) {
            response = $.parseJSON(response);
            if(response.status.toLowerCase() == "success") {
                alert(response.message);
                projectObj._tasks.viewOne(response.updatedId);
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
};
*/

/*
task.prototype.deleteRecord = function(task_id, project_id, viewFor) {
    $.ajax({
        method: "POST",
        url: "/projects/tasks/deleteRecord",
        data: {
            task_id     :     task_id,
            project_id     :     project_id,
            viewFor     :     viewFor
        },
        success: function( response ) {
            response = $.parseJSON(response);
            if(response.status.toLowerCase() == "success") {
                alert(response.message);
                projectObj._tasks.viewAll();
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
};
*/

/*
task.prototype.viewOne = function( taskId ) {
    projectObj.clearRest();
    projectObj.toggleAccordiance("task");
    $.ajax({
        method: "POST",
        url: "/projects/tasks/viewOne",
        data: {
            taskId: taskId
        },
        success: function( response ) {
            $("#task_content").html(response);
        },
        error: function( error ) {
            error = error;
        }
    })
    .fail(function ( failedObj ) {
        fail_error = failedObj;
    });
};
*/

task.prototype.setDropdownValue = function() {
    var db_task_status = $("#db_task_status").val();

    $("#task_status").val(db_task_status);
};

task.prototype.setOwnerOption = function() {
    $("input[name=optionSelectedOwner][value='" + $("#taskOwnerIdDb").val() + "']").attr('checked', 'checked');
}

task.prototype.setPercentage = function(statusValue) {
    if(statusValue == "completed") {
        $("#task_percent_complete").val("100").attr("disabled",true);
    } else {
        if($("#task_percent_complete").val() == "100" || $("#task_percent_complete").val() != $("#task_percent_complete").attr("defaultValue")) {
            $("#task_percent_complete").val($("#task_percent_complete").attr("defaultValue")).attr("disabled",false);
        }
    }
};

task.prototype.percentageChange = function (percentageValue) {
    $("#task_percent_complete").attr("defaultValue", percentageValue);
}

task.prototype.showTaskList = function ( event ) {
    var options = "open";

    if( event ) {
        options = event.target.getAttribute("data-option");
        if(options) {
            $($(".tasks.internal-tab-as-links").children()).removeClass("active");
            $(".task-table-list .row").hide();
            $(event.target).addClass("active");
        }
    } else {
        $($(".tasks.internal-tab-as-links").children()).removeClass("active");
        $(".task-table-list .row").hide();
        $($(".tasks.internal-tab-as-links").children()[0]).addClass("active")
    }

    if(options == "all") {
        $(".task-table-list .row").show();
    } else if (options != "") {
        $(".task-table-list .row."+options).show();
    }
}