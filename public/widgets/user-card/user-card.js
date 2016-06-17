(function() {
  $(function() {
    return $('.widget-user-image').on('click', function() {
      var mediaSelectorWrapper;
      mediaSelectorWrapper = $(this).parents('.media-selector-wrapper');
      return $('.library-finder', mediaSelectorWrapper).libraryFinder('show', 'widget-user-image');
    });
  });

  $('.library-finder').on('media.finder.selected', function(e, id, library) {
    var errorMessage, form, mediaSelectorWrapper;
    mediaSelectorWrapper = $(this).parents('.media-selector-wrapper');
    errorMessage = mediaSelectorWrapper.data('nonImageError');
    form = $('form', mediaSelectorWrapper);
    if (!isImage(library.description.mime)) {
      return $.notify({
        message: errorMessage
      }, {
        type: 'error',
        placement: {
          align: 'center'
        }
      });
    } else {
      $('input[name="avatar"]', mediaSelectorWrapper).val("/uploads/" + library.description.path + "/thumb");
      $('.media-selector img', mediaSelectorWrapper).attr('src', "/uploads/" + library.description.path + "/thumb");
      if (form.length) {
        return form.submit();
      }
    }
  });

}).call(this);

//# sourceMappingURL=user-card.js.map
