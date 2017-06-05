$(document).ready(function () {
	function showAlertBoxWindow(alertBox_id, afterFn) {
		alertBox = $('#' + alertBox_id);
		alertBox.removeClass('hidden');
		alertBox.addClass('alertBox');
		$('<div>', {class: 'alertBox_wrapper'}).prependTo('body');
		alertBox_wrapper = $('.alertBox_wrapper').animate({'opacity' : 1}, 300);
		alertBox_pos_x = ($(window).width() / 2 - (alertBox.width() / 2));
		alertBox_pos_y = 50;
		alertBox.css({'left' : alertBox_pos_x, top : alertBox_pos_y}).animate({'opacity' : 1}, 300);
		alertBox_wrapper.click(function () {
			hideAlertBoxWindow(alertBox_id, afterFn);
		});
		$(document).keydown(function(e) {
			if (e.keyCode == 27) {
				hideAlertBoxWindow(alertBox_id, afterFn);
			}
		});
	}

	function hideAlertBoxWindow(alertBox_id, afterFn) {
		var alertBox = $("#" + alertBox_id);
		$(".alertBox_wrapper").animate({'opacity' : 0}, 100, function () {
			$(this).remove();
		});
		alertBox.animate({'opacity' : 0}, 100, function () {
			alertBox.removeClass('alertBox');
			alertBox.addClass('hidden');
			if (afterFn != undefined)
				afterFn();
		});
	}

	$("[data-toggle='modal']").click(function (e) {
		e.preventDefault();
		window.scrollTo(0, 0);
		var object = $(this).attr('href');
		showAlertBoxWindow(object);
	});
});