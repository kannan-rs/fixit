fixit_app.directive('navLevel1Finished', function() {
	return function(scope, element, attrs) {
		if (scope.$last){
			setTimeout(function() {
				$('ul.nav li.dropdown').hover(function () {
					$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn();
				}, function () {
					$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut();
				});
			}, 250);
		}
	};
});

/*fixit_app.directive('navLevel2Finished', function() {
	return function(scope, element, attrs) {
		angular.element(element).css('border','5px solid red');
	};
});
*/