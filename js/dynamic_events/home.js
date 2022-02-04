
$(document).ready(function(){
	alertify.set('notifier','position', 'bottom-center');

	$('.slider-configuration').slick({
		infinite: true,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 1,
		responsive: [
		{
			breakpoint: 1024,
			settings: {
				slidesToShow: 3
			}
		},
		{
			breakpoint: 800,
			settings: {
				slidesToShow: 2
			}
		},
		{
		  	breakpoint: 500,
			settings: {
				slidesToShow: 1
			}
		}]
	});
});
