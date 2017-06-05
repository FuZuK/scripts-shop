jQuery(document).ready(function () {
	sidebar = jQuery(".sidebar");
	wrapper = jQuery(".wrapper");
	wrapper_width = wrapper.width();
	wrapper_height = wrapper.height();
	sidebar_width = sidebar.width();
	sidebar_height = sidebar.height();
	sidebar_x = sidebar.offset().left;
	sidebar_y = sidebar.offset().top;
	scrollto = sidebar_y + sidebar_height;
	jQuery(window).scroll(function () {
		now = jQuery(window).scrollTop();
		if (scrollto + 70 < now) {
			sidebar.hide();
			new_width = wrapper_width - sidebar_width;
			wrapper.width(new_width);
		} else {
			sidebar.show();
			wrapper.width(wrapper_width);
		}
	});
});