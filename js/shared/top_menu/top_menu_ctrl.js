// Top Menu Controller
fixit_app.controller('top_menu', function($scope, $http) {
	$scope.top_menu_view 	= "/js/shared/top_menu/top_menu_template.html";
	$http({
		'url' : "/utils/page_utils/get_page_menus"
	})
		.then(function( response ) {
			$scope.page 	= session.page && session.page != "home" ? session.page : "index";

			menus 	= response.data;

			for(menu in menus) {
				menus[menu].active = menus[menu].link ? menus[menu].link.split("/")[2] == $scope.page : $scope.page;
			}

			$scope.menus = menus;
		});
});