console.log('\'Allo \'Allo!');

 
jQuery(document).ready(function() {

	//nav

		$(".toggle").click(function(e) {
			e.preventDefault();
			$(".menu").toggleClass("showMenu");
			if ($(".dropdown").is(":visible")) {
				$(".dropdown").removeClass("showDropdown");
			}
			$(this).toggleClass("toggle-open");
		});
	
		$(".dropdownStart").click(function(e) {
			$(".dropdown").toggleClass("showDropdown");
		});
		
	//end nav

		$('select').niceSelect();

	//slick

	$('.slider').slick({
		infinite: true,
		dots: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: true
	  });

	  $('.slider-min').slick({
		infinite: true,
		dots: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: true
	  });

	  $('.slider-deco').slick({
		infinite: true,
		dots: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,	
	  });

	  $('.slider-3').slick({
		infinite: true,
		dots: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: true,	
	  });

	  objectFitImages();

});





	
	


