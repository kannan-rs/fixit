/*global jQuery:false */
jQuery(document).ready(function($) {
"use strict";

		//add some elements with animate effect
		/*$(".box").hover(
			function () {
			$(this).find('span.badge').addClass("animated fadeInLeft");
			$(this).find('.ico').addClass("animated fadeIn");
			},
			function () {
			$(this).find('span.badge').removeClass("animated fadeInLeft");
			$(this).find('.ico').removeClass("animated fadeIn");
			}
		);*/
		
	/*(function() {

		var $menu = $('.navigation nav'),
			optionsList = '<option value="" selected>Go to..</option>';

		$menu.find('li').each(function() {
			var $this   = $(this),
				$anchor = $this.children('a'),
				depth   = $this.parents('ul').length - 1,
				indent  = '';

			if( depth ) {
				while( depth > 0 ) {
					indent += ' - ';
					depth--;
				}

			}
			$(".nav li").parent().addClass("bold");

			optionsList += '<option value="' + $anchor.attr('href') + '">' + indent + ' ' + $anchor.text() + '</option>';
		}).end()
		.after('<select class="selectmenu">' + optionsList + '</select>');
		
		$('select.selectmenu').on('change', function() {
			console.log(3);
			window.location = $(this).val();
		});
		
	})();*/

		//Navi hover
		/*$('ul.nav li.dropdown').hover(function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn();
			//$(this).find('.sub-menu-level1').css("top", $(this).position().top+"px");
			//css("top", $(this).position().top)
		}, function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut();
			//$(this).find('.sub-menu-level1').css("top", $(this).position().top);
		});*/

		/*$('ul.nav li.dropdown').on("mouseover", function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn();
			//$(this).find('.sub-menu-level1').css("top", $(this).position().top+"px");
			//css("top", $(this).position().top)
		});
		$('ul.nav li.dropdown').on("mouseout", function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut();
			//$(this).find('.sub-menu-level1').css("top", $(this).position().top);
		});*/
		
});