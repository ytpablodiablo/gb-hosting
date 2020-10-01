jQuery(document).ready(function () {
	"use strict";
	var $ = jQuery.noConflict();
  
  		function showMenu() {
			$(this).removeClass("drop-collapsed");
			$(this).addClass("open");
		}
		function hideMenu(){
			$(this).removeClass("open");
			var $dropdown = $(".dropdown");

			$dropdown.each(function () {
				$(this).addClass("drop-collapsed");
			});
		}
  
  
  		var $dropdown = $(".dropdown");

			$dropdown.each(function () {
				var $this = $(this);

				var $dropmenu = $this.find(".dropdown-menu");
				$dropmenu.css("height", $dropmenu.outerHeight());
				$this.addClass("drop-collapsed");
			});


			 // dropdown menu hover intent
			var hovsettings = {
					timeout:0,
			    interval: 0,
			    over: showMenu,
			    out: hideMenu
			};

			$(".dropdown").hoverIntent(hovsettings);
  
  
  
});