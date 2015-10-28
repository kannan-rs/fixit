//Security JS
function security() {
    'use strict';
    this._users = _users;
    this._operations = _operations;
    this._roles = _roles;//new securityRoles();
    this._functions = _functions;//new securityFunctions();
    this._dataFilters = _dataFilters;//new securityDataFilters();
    this._permissions = _permissions;//new securityPermissions();
}

/*var securityObj = new security();

$().ready(function() {
    var module = session.module != "" ? session.module : "users";
    if(module) {
        switch (module) {
            case "users":
                securityObj._users.viewAll();
            break;
            case "operations":
                securityObj._operations.viewAll();
            break;
            case "roles":
                securityObj._roles.viewAll();
            break;
            case "functions":
                securityObj._functions.viewAll();
            break;
            case "data_filters":
                securityObj._dataFilters.viewAll();
            break;
            case "permissions":
                securityObj._permissions.viewAll();
            break;
            default:
            break;
        }
    }
});*/