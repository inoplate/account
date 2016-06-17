(function() {
  $("#user-registration-form").on("ajax.form.success", function() {
    $(this).trigger("reset");
    return $('select', this).trigger('change');
  });

  $('.library-finder', '.widget-user').on('media.finder.selected', function(e, id, library) {
    return $('input[name="avatar"]', "#user-registration-form").val("/uploads/" + library.description.path + "/thumb");
  });

}).call(this);

//# sourceMappingURL=register.js.map
