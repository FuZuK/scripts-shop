var Pin = {
	init : function (elem) {
		var elem = $(elem);
		elemPosition = elem.offset();
		elemWidth = elem.width();
		positionY = elemPosition.top;
		positionX = elemPosition.left;
		pinned = false;
		$(window).scroll(function() {
			if ($(window).scrollTop() >= positionY && !pinned) {
				newDiv = elem.clone().prependTo("body");
				newDiv.css({
					'left' : positionX, 
					'width' : elemWidth
				});
				newDiv.addClass("pinned");
				pinned = true;
			} else if ($(window).scrollTop() < positionY && pinned) {
				newDiv.remove();
				pinned = false;
			}
		});
	}
}