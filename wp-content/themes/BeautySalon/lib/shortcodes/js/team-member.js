jQuery(document).ready(function ($) {

	$('body').on('click', '.su-member-clickable', function (e) {
		document.location.href = $(this).data('url');
	});


	jQuery(".su-member-style-5, .su-member-style-6").hover(
		function(){
			jQuery(this).find(".su-content-wrap:not(:empty)").animate({
				height: "toggle",
				opacity: 1
			});
		},
		function(){
			jQuery(this).find(".su-content-wrap:not(:empty)").animate({
				height: "toggle",
				opacity: 0
			});
		}
	);
});

