(function() {
  $('#permissions-table').DataTable({
    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-12"rt>><"row"<"col-sm-5"i><"col-sm-7"p>>',
    serverSide: false,
    ajax: false,
    createdRow: function(row, data, index) {
      return true;
    }
  });

  $('#permissions-table').on('change', 'input[name="attached"]', function() {
    var form, that;
    that = this;
    form = $(this).parents('form');
    return form.submit();
  });

  $('#permissions-table').on('ajax.form.beforeSend', 'form.ajax', function() {
    return $('input[name="attached"]', this).iCheck('disable');
  });

  $('#permissions-table').on('ajax.form.complete', 'form.ajax', function() {
    console.log(this);
    return $('input[name="attached"]', this).iCheck('enable');
  });

}).call(this);

//# sourceMappingURL=index.js.map
