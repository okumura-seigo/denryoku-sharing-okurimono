(function() {
	$.fn.dragScroll = function() {
		var target = this;
		$(this).mousedown(function (event) {
			$(this)
				.data('down', true)
				.data('x', event.clientX)
				.data('y', event.clientY)
				.data('scrollLeft', this.scrollLeft)
				.data('scrollTop', this.scrollTop);
			return false;
		}).css({
			'overflow': 'hidden',
			'cursor': 'move'
		});

		$(document).mousemove(function (event) {
			if ($(target).data('down') == true) {
				target.scrollLeft($(target).data('scrollLeft') + $(target).data('x') - event.clientX);
				target.scrollTop($(target).data('scrollTop') + $(target).data('y') - event.clientY);
				return false; // •¶Žš—ñ‘I‘ð‚ð—}Ž~
			}
		}).mouseup(function (event) {
			$(target).data('down', false);
		});

		return this;
	}
})(jQuery);