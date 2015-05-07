//Security JS
function personalDetails() {

	this._userInfo = new userInfo();
};

var personalDetailsObj = new personalDetails();

$().ready(function() {
	var module = session.module != "" ? session.module : "view_my_details";

	if(module) {
		switch (module) {
			case "view_my_details":
				personalDetailsObj._userInfo.getUserData();
			break;
			case "changePass_form":
				personalDetailsObj._userInfo.changePassForm();
			break;
			default:
			break;
		}
	}
});