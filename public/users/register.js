(function() {
  $("#user-registration-form").on("ajax.form.success", function() {
    $(this).trigger("reset");
    return $('select', this).trigger('change');
  });

}).call(this);

//# sourceMappingURL=register.js.map
