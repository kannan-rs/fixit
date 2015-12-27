/**
    Tasks functions
*/
var _tasks = (function () {
    "use strict";
    return {
        errorMessage: function () {
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
                    required : _lang.english.errorMessage.taskForm.task_end_date,
                    //greaterThanOrEqualTo : _lang.english.errorMessage.taskForm.task_end_date
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
        },

        validationRules: function() {
            return {
                task_end_date: {
                    //greaterThanOrEqualTo: "#task_start_date"
                }
            }
        },

        createValidate:  function ( viewFor ) {
            var validator = $("#create_task_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if(validator.form()) {
                _projects.taskCreateSubmit();
            }
        },

        updateValidate: function( viewFor ) {
            var validator = $("#update_task_form").validate({
                rules: this.validationRules(),
                messages: this.errorMessage()
            });

            if(validator.form()) {
                _projects.taskUpdateSubmit();
            }
        },

        setDropdownValue: function() {
            var db_task_status = $("#db_task_status").val();

            $("#task_status").val(db_task_status);
        },

        setOwnerOption: function() {
            $("input[name=optionSelectedOwner][value='" + $("#taskOwnerIdDb").val() + "']").attr('checked', 'checked');
        },

        setPercentage: function(statusValue) {
            if(statusValue == "completed") {
                $("#task_percent_complete").val("100").attr("disabled",true);
            } else {
                if($("#task_percent_complete").val() == "100" || $("#task_percent_complete").val() != $("#task_percent_complete").attr("defaultValue")) {
                    $("#task_percent_complete").val($("#task_percent_complete").attr("defaultValue")).attr("disabled",false);
                }
            }
        },

        percentageChange: function (percentageValue) {
            $("#task_percent_complete").attr("defaultValue", percentageValue);
        },

        showTaskList: function ( event ) {
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
    }
})();