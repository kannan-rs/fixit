// Top Menu Controller
fixit_app.controller('top_menu', function($scope, $http) {
	$scope.top_menu_view 	= "/js/shared/top_menu/template.html";
	$http({
		'url' : "/utils/page_utils/get_page_menus"
	})
		.then(function( response ) {
			var page = _utils.get_current_module();
			$scope.page 	= page && page != "home" ? page : "index";

			var menus 	= response.data;

			for(menu in menus) {
				menus[menu].active = menus[menu].link ? menus[menu].link.split("/")[2] == $scope.page : $scope.page;
			}
			$scope.menus = menus;

			homeObj.loadPage(menus);
		});
});