(function($) {
  $.entwine("ss", function($) {
     $(".address_finder p a").entwine({
         onmatch: function() {

         },
         onclick: function(e) {
             e.preventDefault();
             $(this).parents('.address_finder[data-api-key]').find('.manual_address').slideToggle(function() {
                $(this).parents('.address_finder[data-api-key]').find('input[name*=ManualAddress]').val(
                 $(this).parents('.address_finder[data-api-key]').find('.manual_address').is(":visible")
                 )
             });
         }
     })
    $(".address_finder").entwine({
      onmatch: function(deferred) {
          var widget,
              key = $(this).data('api-key'),
              address = $(this).find('.address_finder_address'),
              input = $(this).find('input').first(),
              manual = $(this).find('.manual_address'),
              toggle = $(this).find('.toggle_manual_address');

          var useManual = manual.find('input[name*=ManualAddress]'),
              field = address.find('input').get(0)

          /* update ui with javascript */
          toggle.show()
          address.show()

          if (!useManual.val()) {
              manual.hide()
          }

          /* create widget */
          widget = new AddressFinder.Widget(field, key, "NZ", {
              container: $(this).find('.addressfinder__holder').get(0)
          });

          /* updates manual fields and hidden metadata */
          widget.on('result:select', function(value, item) {
              /* populate postal line fields */
              for (var i = 1; i <= 6; i++) {
                  manual.find('input[name*=PostalLine' + i + ']').val(item['postal_line_' + i] || '')
              }

              manual.find('input[name*=Suburb]').val(item.suburb || '')
              manual.find('input[name*=Region]').val(item.region || '')
              manual.find('input[name*=City]').val(item.city || '')
              manual.find('input[name*=Postcode]').val(item.postcode || '')
              manual.find('input[name*=Longitude]').val(item.x || '')
              manual.find('input[name*=Latitude]').val(item.y || '')

              $('body').trigger(jQuery.Event('addressselected'))
          })

          /* on manually changing of the fields then we have to clear x/y */
          manual.on('keydown', 'input', function(e) {
              manual.find('input[name*=Longitude]').val('')
              manual.find('input[name*=Latitude]').val('')
          })

          /* focusing back on the address dropdown should hide the manual */
          input.on('focus', function(e) {
              manual.slideUp()
          })
      }
    })
})
})(jQuery)
