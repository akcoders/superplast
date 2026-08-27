(function ($) {
  'use strict';
  $(function () {
    var frame;
    var $field = $('#patrai_product_gallery_ids');
    var $preview = $('.patrai-gallery-preview');

    function render(selection) {
      var ids = [];
      $preview.empty();
      selection.each(function (attachment) {
        var data = attachment.toJSON();
        var url = data.sizes && data.sizes.thumbnail ? data.sizes.thumbnail.url : data.url;
        ids.push(data.id);
        $('<span>', { 'data-id': data.id }).append($('<img>', { src: url, alt: '' })).appendTo($preview);
      });
      $field.val(ids.join(','));
    }

    $('#patrai-select-gallery').on('click', function (event) {
      event.preventDefault();
      if (!frame) {
        frame = wp.media({ title: 'Select product gallery images', button: { text: 'Use selected images' }, multiple: true });
        frame.on('open', function () {
          var selection = frame.state().get('selection');
          String($field.val()).split(',').forEach(function (id) {
            if (id) {
              var attachment = wp.media.attachment(parseInt(id, 10));
              attachment.fetch();
              selection.add(attachment);
            }
          });
        });
        frame.on('select', function () { render(frame.state().get('selection')); });
      }
      frame.open();
    });

    $('#patrai-clear-gallery').on('click', function (event) {
      event.preventDefault();
      $field.val('');
      $preview.empty();
      if (frame) { frame.state().get('selection').reset(); }
    });
  });
})(jQuery);
