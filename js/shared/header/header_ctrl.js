// Top Menu Controller
fixit_app.controller('header', function($scope, $http) {
	$scope.header_view 	= "/js/shared/header/header_template.html";
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


			var start 	= "<a class=\"signup\" href=\""+$scope.baseUrl+"main/login\">";
			var end 	= "</a>";
			//$scope.existing_user_sign_in = $scope.existing_user_sign_in.replace(/##replace1##/g, start).replace(/##replace2##/g, end);

			start = "<a class=\"signup\" href=\""+$scope.baseUrl+"main/signup\">";
			end = "</a>";
			//$scope.new_user_sign_up.replace("##replace1##", start).replace("##replace2##", end);
		});
});