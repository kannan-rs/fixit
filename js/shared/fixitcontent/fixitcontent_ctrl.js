// Top Menu Controller
fixit_app.controller('fixitcontent', function($scope, $http) {
	console.log($http);
	/*$scope.header_view 	= "/js/shared/header/template.html";
	$http({
			method 	: "POST",
			url		: "/utils/page_utils/header_data"
		}).then(function( response ) {
			var data = response.data;
			$scope.base_url 				= data.base_url;
			$scope.logged_in_user_email 	= data.logged_in_user_email;
			$scope.role_disp_name			= data.role_disp_name;
			$scope.is_logged_in				= data.is_logged_in;
			$scope.existing_user_sign_in	= data.existing_user_sign_in ? data.existing_user_sign_in.replace(/##replace[0-9]##/g,"##replace##").split("##replace##") : "";
			$scope.new_user_sign_up			= data.new_user_sign_up ? data.new_user_sign_up.replace(/##replace[0-9]##/g,"##replace##").split("##replace##") : "";
		});*/
});