;(function($) {
	$(document).ready(function () {
		$(".address_finder").each(function (i, elem) {
			var widget, 
				key = $(elem).data('api-key'),
				field = $(this).find('input.addressfinder').get(0);
				address = $(elem).find('.address_finder_address'),
				input = $(elem).find("input").first(),
				manual = $(elem).find('.manual_address'),
				toggle = $(elem).find('.toggle_manual_address');

			/* update ui with javascript */
			toggle.show();
			address.show();
			manual.hide();

			/* create widget */
			widget = new AddressFinder.Widget(field, key, {
				show_locations: false
			});

			/* updates manual fields and hidden metadata */
			widget.on("result:select", function (value, item) {
				/* populate postal line fields */
				for(var i = 1; i <= 6; i++) {
					manual.find("input[name*=PostalLine" + i +"]").val(item["postal_line_"+ i] || '');
				}

				manual.find("input[name*=Suburb]").val(item.suburb || '');
				manual.find("input[name*=City]").val(item.city || '');
				manual.find("input[name*=Postcode]").val(item.postcode || '');
				manual.find("input[name*=Longitude]").val(item.x || '');
				manual.find("input[name*=Latitude]").val(item.y || '');
			});

			/* click handler to toggle manual div */
			toggle.on('click', function(e) {
				e.preventDefault();

				manual.toggle();
			});

			/* on manually changing of the fields then we have to clear x/y */
			manual.on('keydown', 'input', function(e) {
				console.log('blurred');
			});
		});
	});
})(jQuery);