var _permissions = (function () {
    //'use strict';
    var component = null;
    var compBySno = {};
    var compById = {};
    var permissionList = null;
    var permissionListById = {};
    var popupDialog;

    var selectedUserId = null;
    var selectedRoleId = null;

    var compKeyMap = {
        users: "user_name",
        roles: "role_id",
        operations: "ope_id",
        functions: "fn_id",
        dataFilters: "data_filter_id"
    };

    function convertComponentData() {
        for( var dbName in component ) {
            compBySno[dbName] = {};
            compById[dbName] = {};

            for(var i = 0; i < component[dbName].length; i++) {
                compBySno[dbName][component[dbName][i]['sno']] = component[dbName][i];
                compById[dbName][component[dbName][i][compKeyMap[dbName]]] = component[dbName][i];
             }
        }
    };

    function convertPermissionsToId() {
        for( var dbName in component ) {
            compBySno[dbName] = {};
            compById[dbName] = {};

            for(var i = 0; i < component[dbName].length; i++) {
                compBySno[dbName][component[dbName][i]['sno']] = component[dbName][i];
                compById[dbName][component[dbName][i][compKeyMap[dbName]]] = component[dbName][i];
             }
        }
    };

    function convertPermissionsToId() {
        for( var i in permissionList ) {
            permissionListById[permissionList[i]['sno']] = permissionList[i];
        }
    };

    function showAddNewButton() {
        $("#addNewButtons").show();
    }

    function getComponent() {
        return component;
    }

    function getCompBySno() {
        return compBySno;
    }

    function getCompById() {
        return compById;
    }

    function getPermissionList() {
        return permissionList;
    }

    function getPermissionListById() {
        return permissionListById;
    }

    function getRoleDisplayStrBySno( sno ) {
        return getCompBySno().roles[sno].role_name;
    }

    function getUserDisplayStrBySno( sno ) {

    }

    function setPermissionsFromDB() {
        var userDisplayStr = selectedUserId ? getUserDisplayStrBySno(selectedUserId) : "Global Permissions";
        var roleDisplayStr = getRoleDisplayStrBySno(selectedRoleId);

        $("#selectedUserDisplayStr").text(userDisplayStr);
        $("#selectedRoleDisplayStr").text(roleDisplayStr);

        var dbFunctionId = $("#dbFunctionId").val();
        var dbOperationId = $("#dbOperationId").val();
        var dbdataFilterId = $("#dbdataFilterId").val();

        $("#functions").val("");
        $("input[type='checkbox'][name='operations']").prop('checked', false);
        $("input[type='checkbox'][name='dataFilters']").prop('checked', false);

        if(dbFunctionId != "") {
            $("#functions").val(dbFunctionId);
        }

        if(dbOperationId != "") {
            var opIds = dbOperationId.split(",");
            for(var i = 0; i < opIds.length; i++) {
                $("input[type='checkbox'][name='operations'][value='"+opIds[i]+"']").prop('checked', true);
            }
        }

        if(dbdataFilterId != "") {
            var data_filter_id = dbdataFilterId.split(",");
            for(var i = 0; i < data_filter_id.length; i++) {
                $("input[type='checkbox'][name='dataFilters'][value='"+data_filter_id[i]+"']").prop('checked', true);
            }
        }
    }

    function updateInputForm( data ) {
        $("#dbPermissionId").val("");
        $("input[type='checkbox'][name='operations']").prop('checked', false);
        $("input[type='checkbox'][name='dataFilters']").prop('checked', false);
        if(data && data.length) {
            var opIds = data[0].op_id.split(",");
            var data_filter_id = data[0].data_filter_id.split(",");
            $("#dbPermissionId").val(data[0].sno);

            for(var i = 0; i < opIds.length; i++) {
                $("input[type='checkbox'][name='operations'][value='"+opIds[i]+"']").prop('checked', true);
            }

            for(var i = 0; i < data_filter_id.length; i++) {
                $("input[type='checkbox'][name='dataFilters'][value='"+data_filter_id[i]+"']").prop('checked', true);
            }
        }
    }

    return {
        p_getComponent: function() {
            return getComponent();
        },
        p_getCompBySno: function() {
            return getCompBySno();
        },
        p_getCompById: function() {
            return getCompById();
        },
        p_getPermissionList: function() {
            return getPermissionList();
        },
        p_getPermissionListById: function() {
            return getPermissionListById();
        },
        p_getRoleDisplayStrBySno: function() {
            return getRoleDisplayStrBySno();
        },
        p_getUserDisplayStrBySno: function() {
            return getUserDisplayStrBySno();
        },
        getComponentInfo: function() {
            var responseObj = null;
            var self = this;
            $.ajax({
                method: "POST",
                url: "/security/permissions/getComponentInfo",
                data: {},
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    responseObj = JSON.parse(response);
                    if(responseObj.status == "success") {
                        $(".error").hide();
                        $(".success").hide();
                        component = responseObj.data;
                        setTimeout(function(){ convertComponentData(); },0);
                    } else {
                        $(".error").show().text(responseObj.message);
                    }
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        inputForm: function( type, permissionId ) {
            var type = type || "default";
            var self = this;
            $.ajax({
                method: "POST",
                url: "/security/permissions/inputForm",
                data: {
                    permissionId : permissionId,
                    type : type
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#popupForAll").html(response);
                    self.openDialog({title: "Add/Update Permission Form"});
                    setPermissionsFromDB();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        openDialog: function (options) {           
            this.popupDialog = $("#popupForAll").dialog({
                autoOpen: false,
                maxHeight: 700,
                width: 700,
                modal: true
            });
            this.popupDialog.dialog("open");
            if (typeof(options) !== "undefined") {
                this.popupDialog.dialog("option", "title", options.title);
            }
        },
        setButtonActive: function(type) {
            $($(".permission.internal-tab-as-links").children()).removeClass("active");
            $($(".permission.internal-tab-as-links").children()).each(
                function(key, ele) { 
                    if($(ele).attr("data-option") == type) {
                        $(ele).addClass("active");
                    }
                }
            );
        },
        showPermissionsPage: function(event) {
            var type = "default";

            if (event) {
                type = event.target.getAttribute("data-option");   
            }
            this.getPageForType(type);
        },
        getPageForType: function ( type ) {
            var type = type || "default";
            var self = this;
            $.ajax({
                method: "POST",
                url: "/security/permissions/getPageForType",
                data: {
                    type : type
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    $("#security_content").html(response);
                    $(".error").hide();
                    $(".success").hide();
                    $(".user_type_as").hide();
                    $("#addNewButtons").hide();
                    self.setButtonActive(type);
                    //$(".role_type_as").hide();
                },
                error: function (error) {
                    error = error;
                }
            }).fail(function (failedObj) {
                fail_error = failedObj;
            });
        },
        getPermissions: function ( type , from ) {

            selectedUserId = $("#user").val() || null;
            selectedRoleId = $("#role").val() || null;
            var functionId = $("#functions").val() || null;

            if(from && from == "role") {
                selectedUserId = (type == "default") ? "" : selectedUserId;
                functionId = "";
            } else if( from && from == "user") {
                functionId = "";
                if(!selectedRoleId) {
                    return;
                }
            }

            var self = this;

            var user_type = selectedUserId ? $("#user").find(":selected").attr("data_user_type") : null;
             $("#user_type").text("'" + user_type + "'");
            $(".user_type_as").show();

            /*var role_type = role_id ? $("#role").find(":selected").attr("data_role_type") : null;
             $("#role_type").text("'" + role_type + "'");
            role_type ? $(".role_type_as").show() : $(".role_type_as").hide();*/

            if(!selectedUserId && !selectedRoleId) {
                if(type == "default") {
                    $(".error").text("Please select the role to view/update its permissions").show(); 
                } else if(type == "user") {
                    $(".error").text("Please select the user to view/update its permissions").show(); 
                } else {
                    $(".error").text("Some thing went wrong, please refresh the page and try again.").show(); 
                }
                return false;
            }

            var responseObj = null;

            $.ajax({
                method: "POST",
                url: "/security/permissions/getPermissions",
                data: {
                    user_id: selectedUserId,
                    role_id : selectedRoleId,
                    function_id: functionId,
                    type : type
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    responseObj = jQuery.parseJSON(response);
                    if (responseObj.status === "success") {
                        //$(".error").hide().text('');
                        //$(".success").hide().text('');
                        if(!functionId) {
                            permissionList = responseObj.data;
                            convertPermissionsToId();
                            self.listPermissions();
                            showAddNewButton();
                        } else {
                            updateInputForm( responseObj.data );
                        }
                    } else if (responseObj.status === "error") {
                        $(".error").show().text(responseObj.message);
                    }
                },
                error: function (error) {
                    $(".error").show().text(error);
                }
            }).fail(function (failedObj) {
                $(".error").show().text(failedObj);
            });
        },
        updateDisplayPermissionList: function() {
            var role_id = $("#role").val();
            var user_id = $("#user").val();

            if(!role_id) {
                return;
            }

            var type = !user_id && role_id ? "default" : "user";

            this.getPermissions(type, "role");
        },
        setPermissions: function ( type ) {
            var error = "";
            var self = this;
            if(type == "") {
                error = 1;
            } else if( type != "default" && (selectedUserId == "" || selectedRoleId == "")) {
                error = 1;
            } else if (type == "default" && selectedRoleId == "") {
                error = 1;
            }

            if(error != "") {
                alert("Some thing went wrong while setting permission, Please refresh and retry");
                return false;
            }

            var fn_id = "";
            var op_id = "";
            var df_id = "";

            var op_selected = "";
            var df_selected = "";
            //var user_sno = "";
            
            var op_sno = "";
            var df_sno = "";
            //var user_type = "";
            
            var opIdx;
            var dfIdx;
            var responseObj = null;
            var permissionId = $("#dbPermissionId").val();
            
            var user_id = selectedUserId || "" ;
            var role_id = selectedRoleId || "";

            fn_id = $("#functions").val();

            op_selected = $("input[type='checkbox'][name='operations']:checked");

            if (op_selected && op_selected.length) {
                for (opIdx = 0; opIdx < op_selected.length; opIdx += 1) {
                    if (opIdx > 0) {
                        op_sno += ",";
                        op_id += ",";
                    }
                    op_sno += $(op_selected[opIdx]).val();
                    op_id += $(op_selected[opIdx]).attr("data_operation_id");
                }
            }

            df_selected = $("input[type='checkbox'][name='dataFilters']:checked");

            if (df_selected && df_selected.length) {
                for (dfIdx = 0; dfIdx < df_selected.length; dfIdx += 1) {
                    if (dfIdx > 0) {
                        df_sno += ",";
                        df_id += ",";
                    }
                    df_id += $(df_selected[dfIdx]).attr("data_data_filter_id");
                    df_sno += $(df_selected[dfIdx]).val();
                }
            }

            $.ajax({
                method: "POST",
                url: "/security/permissions/setPermissions",
                data: {
                    user_id: user_id,
                    role_id: role_id,
                    op_id: op_id,
                    fn_id: fn_id,
                    df_id: df_id,
                    op_sno: op_sno,
                    df_sno: df_sno,
                    type: type,
                    permissionId : permissionId

                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    responseObj = jQuery.parseJSON(response);
                    $("#popupForAll").dialog("close");
                    if (responseObj.status === "success") {
                        if (responseObj.action === "inserted") {
                            $(".success").show().text("Permissions Added successfully");
                        } else if (responseObj.action === "updated") {
                            $(".success").show().text("Permissions Updated successfully");
                        }
                        self.updateDisplayPermissionList();
                    } else if (response.status === "error") {
                        $(".error").show().text(response.message);
                    }
                },
                error: function (error) {
                    $(".error").show().text(error);
                }
            }).fail(function (failedObj) {
                $(".error").show().text(failedObj);
            });
        },
        clearPermissions: function () {
            var opIdx;
            var fnIdx;
            var dfIdx;

            var role_selected = $("input[type='radio'][name='role']:checked");
            if (role_selected.length > 0) {
                $(role_selected).prop("checked", false);
            }

            var op_selected = $("input[type='checkbox'][name='operations']:checked");
            if (op_selected && op_selected.length) {
                for (opIdx = 0; opIdx < op_selected.length; opIdx += 1) {
                    $(op_selected[opIdx]).prop("checked", false);
                }
            }

            var fn_selected = $("input[type='checkbox'][name='functions']:checked");
            if (fn_selected && fn_selected.length) {
                for (fnIdx = 0; fnIdx < fn_selected.length; fnIdx += 1) {
                    $(fn_selected[fnIdx]).prop("checked", false);
                }
            }

            var df_selected = $("input[type='checkbox'][name='dataFilters']:checked");
            if (df_selected && df_selected.length) {
                for (dfIdx = 0; dfIdx < df_selected.length; dfIdx += 1) {
                    $(df_selected[dfIdx]).prop("checked", false);
                }
            }
            //$("#print_api").text("");
        },
        resetPermissions: function (data) {
            var opIdx;
            var fnIdx;
            var dfIdx;
            var role_selected = null;

            data = data[0];
            //$("#print_api").text(JSON.stringify(data)).show();

            if (data.role_id && data.role_id !== "") {
                role_selected = $("input[type='radio'][name='role'][data_role_id='" + data.role_id + "']").prop("checked", true);
            }

            if (data.op_id && data.op_id !== "") {
                var op_id = data.op_id.split(",");
                if (op_id && op_id.length) {
                    for (opIdx = 0; opIdx < op_id.length; opIdx += 1) {
                        $("input[type='checkbox'][name='operations'][data_operation_id='" + op_id[opIdx] + "']").prop("checked", true);
                    }
                }
            }

            if (data.function_id && data.function_id !== "") {
                var fn_id = data.function_id.split(",");
                if (fn_id && fn_id.length) {
                    for (fnIdx = 0; fnIdx < fn_id.length; fnIdx += 1) {
                        $("input[type='checkbox'][name='functions'][data_function_id='" + fn_id[fnIdx] + "']").prop("checked", true);
                    }
                }
            }

            if (data.data_filter_id && data.data_filter_id !== "") {
                var df_id = data.data_filter_id.split(",");
                if (df_id && df_id.length) {
                    for (dfIdx = 0; dfIdx < df_id.length; dfIdx += 1) {
                        $("input[type='checkbox'][name='dataFilters'][data_data_filter_id='" + df_id[dfIdx] + "']").prop("checked", true);
                    }
                }
            }
        },
        listPermissions: function () {
            $('#permissionListTable tr:gt(0)').remove()
            $("#permissionListTable").append("<tr class=\"cell NoData\"><td colspan=\"6\">No Permissions Set</td></tr>");
            
            for( var i in permissionList) {
                $(".NoData").hide();

                var userTd = "<td class=\"cell\"></td>";
                var roleTd = "<td class=\"cell\"></td>";
                var functionTd = "<td class=\"cell\"></td>";
                var operationTd = "<td class=\"cell\"></td>";
                var dataFilterTd = "<td class=\"cell\"></td>";

                var type = "default";
                
                /*console.log(permissionList[i].user_id);
                console.log(permissionList[i].role_id);
                console.log(permissionList[i].function_id);
                console.log(permissionList[i].op_id);
                console.log(permissionList[i].data_filter_id);*/

                if( permissionList[i].user_id && permissionList[i].user_id.trim() != "" ) {
                    userTd = "<td class=\"cell\">"+permissionList[i].user_id+"</td>";
                    type = "user";
                }
                
                if( permissionList[i].role_id && permissionList[i].role_id.trim() != "" ) {
                    roleTd = "<td class=\"cell\">"+compBySno["roles"][permissionList[i].role_id].role_name+"</td>";
                }

                if( permissionList[i].function_id && permissionList[i].function_id.trim() != "" ) {
                    if(permissionList[i].function_id.trim().toLocaleLowerCase() == "all") {
                        functionTd = "<td class=\"cell\">ALL</td>";
                    } else {
                        functionTd = "<td class=\"cell\">"+compBySno["functions"][permissionList[i].function_id].fn_name+"</td>";
                    }
                }
                
                if( permissionList[i].op_id && permissionList[i].op_id.trim() != "" ) {
                    if(permissionList[i].op_id.trim().toLocaleLowerCase() == "all") {
                        operationTd = "<td class=\"cell\">ALL</td>";
                    } else {
                        var temp_op_id = permissionList[i].op_id.split(",");
                        var op_id_text = "";
                        for(var opIdx = 0; opIdx < temp_op_id.length; opIdx++) {
                            if(opIdx > 0) { 
                                op_id_text += ",<br/>";
                            }
                            op_id_text += compBySno["operations"][temp_op_id[opIdx]].ope_name
                        }
                        operationTd = "<td class=\"cell\">"+op_id_text+"</td>";
                    } 
                }

                if( permissionList[i].data_filter_id && permissionList[i].data_filter_id.trim() != "" ) {
                    if(permissionList[i].data_filter_id.trim().toLocaleLowerCase() == "all") {
                        dataFilterTd = "<td class=\"cell\">ALL</td>";
                    } else {
                        var temp_df_id = permissionList[i].data_filter_id.split(",");
                        var df_id_text = "";
                        for(var dfIdx = 0; dfIdx < temp_df_id.length; dfIdx++) {
                            if(dfIdx > 0) { 
                                df_id_text += ",<br/>";
                            }
                            df_id_text += compBySno["dataFilters"][temp_df_id[dfIdx]].data_filter_name
                        }
                        dataFilterTd = "<td class=\"cell\">"+df_id_text+"</td>";
                    }
                }

                var action = "<td class=\"cell table-action\"><span>";
                action += "<a class=\"step fi-page-edit size-21\" href=\"javascript:void(0);\" ";
                action += "onclick=\"_permissions.inputForm('"+type+"',"+permissionList[i].sno+");\" title=\"Edit Permission\"></a>";
                action += "</span>";
                action += "<span>";
                action += "<a class=\"step fi-deleteRow size-21 red\" href=\"javascript:void(0);\" ";
                action += "onclick=\"_permissions.deleteRecord("+permissionList[i].sno+")\" title=\"Delete Permission\"></a>";
                action += "</span>";

                $("#permissionListTable").append("<tr class='cell'>"+functionTd+operationTd+dataFilterTd+action+"</tr>");
            }
        },
        deleteRecord: function( permissionId ) {
            var responseObj = null;
            var self = this;
            var deleteConfim = confirm("Do you want to delete this permission");

            if (!deleteConfim) {
                return;
            }

            $.ajax({
                method: "POST",
                url: "/security/permissions/deleteRecord",
                data: {
                    permissionId: permissionId
                },
                success: function (response) {
                    if(!_utils.is_logged_in( response )) { return false; }
                    responseObj = $.parseJSON(response);
                    if (responseObj.status === "success") {
                        if (responseObj.action === "deleted") {
                            $(".success").show().text("Permissions Deleted successfully");
                        }
                        self.updateDisplayPermissionList();
                    } else if (response.status === "error") {
                        $(".error").show().text(response.message);
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