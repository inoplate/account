(function() {
  $('#permissions-table').DataTable({
    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-12"rt>><"row"<"col-sm-5"i><"col-sm-7"p>>',
    serverSide: false,
    ajax: false,
    columnDefs: [
      {
        targets: 0,
        visible: false
      }, {
        targets: 1,
        searchable: true,
        sortable: true
      }, {
        targets: '_all',
        searchable: false,
        sortable: false
      }
    ],
    createdRow: function(row, data, index) {
      return true;
    },
    drawCallback: function(settings) {
      var api, colLength, last, rows;
      api = this.api();
      rows = api.rows({
        page: 'current'
      }).nodes();
      last = null;
      colLength = api.columns().header().length;
      return api.column(0, {
        page: 'current'
      }).data().each(function(group, i) {
        if (last !== group) {
          $(rows).eq(i).before("<tr class=\"group\"><td colspan=\"" + colLength + "\">" + group + "</td></tr>");
          return last = group;
        }
      });

      /*api.column(0, {page:'current'} ).data().each( function ( group, i ) {
          if ( last !== group ) {
              $(rows).eq( i ).before(
                  '<tr class="group"><td colspan="5">'+group+'</td></tr>'
              );
       
              last = group;
          }
      } )
       */
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
