(function() {
  var activeButtons, activeTable, incr, index, trashedButtons, trashedTable;

  $('#create-form, #update-form').modal({
    show: false
  });

  $(document).on('ajax.form.success', '#trashed-restore', function(evt, data, textStatus, jqXHR) {
    return activeTable.draw();
  });

  $(document).on('ajax.form.success', '#active-delete', function(evt, data, textStatus, jqXHR) {
    return trashedTable.draw();
  });

  $('form.ajax', '#create-form, #update-form').on('ajax.form.success', function(evt, data, textStatus, jqXHR) {
    $(this).trigger("reset");
    $(this).parents('.modal').modal('hide');
    $('select', this).trigger('change');
    return activeTable.draw();
  });

  $('#active-table').on('click', '.active-update', function() {
    var $overlay, that, url;
    that = this;
    url = $(this).prop('href');
    $('form', '#update-form')[0].reset();
    $('select', '#update-form').trigger('change');
    $overlay = $('.overlay', '#update-form');
    $('#update-form').modal('show');
    $overlay.removeClass('hide');
    $.get(url, function(result) {
      $overlay.addClass('hide');
      $('form', '#update-form').prop('action', "/admin/inoplate-account/roles/" + result.role.id);
      $('input[name="name"]', '#update-form').val(result.role.name);
      return $('input[name="slug"]', '#update-form').val(result.role.description.slug);
    });
    return false;
  });

  activeTable = $('#active-table').DataTable({
    order: [[3, "desc"]],
    columns: [
      {
        "name": "id"
      }, {
        "name": "name"
      }, {
        "name": "slug"
      }, {
        "name": "created_at"
      }, {
        "name": "actions"
      }
    ],
    columnDefs: [
      {
        targets: 0,
        sortable: false,
        searchable: false
      }, {
        targets: 3,
        render: function(data, type, row, meta) {
          return moment(data).format('LL');
        }
      }, {
        targets: 4,
        searchable: false,
        sortable: false,
        render: function(data, type, row, meta) {
          var rendered, token;
          rendered = '<div class="text-right">';
          token = $('meta[name="csrf-token"]').attr('content');
          if ($.inArray('update', data !== -1)) {
            rendered += "<a href=\"/admin/inoplate-account/roles/" + row[0] + "/edit\" class=\"btn btn-default btn-sm active-update\"><i class=\"fa fa-pencil\"></i></a>";
          }
          rendered += "</div>";
          return rendered;
        }
      }
    ],
    ajax: {
      url: 'admin/inoplate-account/roles/datatables'
    },
    buttons: true
  });

  trashedTable = $('#trashed-table').DataTable({
    order: [[3, "desc"]],
    columns: [
      {
        "name": "id"
      }, {
        "name": "name"
      }, {
        "name": "slug"
      }, {
        "name": "created_at"
      }
    ],
    columnDefs: [
      {
        targets: 0,
        sortable: false,
        searchable: false
      }, {
        targets: 3,
        render: function(data, type, row, meta) {
          return moment(data).format('LL');
        }
      }
    ],
    ajax: {
      url: 'admin/inoplate-account/roles/datatables/trashed'
    },
    buttons: true
  });

  activeButtons = $('#active-table').data('actions');

  incr = 2;

  index = $.inArray('delete', activeButtons);

  if (index !== -1) {
    index += incr;
    activeTable.button().add(index, {
      extend: 'bulk',
      method: 'delete',
      url: 'admin/inoplate-account/roles',
      text: '<i class="fa fa-times"></i> Delete selected roles',
      className: 'btn btn-sm btn-danger pull-right',
      formId: 'active-delete'
    });
  }

  incr = index;

  index = $.inArray('create', activeButtons);

  if (index !== -1) {
    index += incr;
    activeTable.button().add(index, {
      text: '<i class="fa fa-plus"></i> Create new role',
      url: 'admin/inoplate-account/roles/create',
      className: 'btn btn-sm btn-primary pull-right',
      init: function(dt, button, config) {
        return $(button.prop("href", config.url));
      },
      action: function(e, dt, node, config) {
        var container;
        container = dt.table().container();
        $('#create-form').modal('show');
      }
    });
  }

  trashedButtons = $('#trashed-table').data('actions');

  incr = 2;

  index = $.inArray('delete', trashedButtons);

  if (index !== -1) {
    index += incr;
    trashedTable.button().add(index, {
      extend: 'bulk',
      method: 'delete',
      url: 'admin/inoplate-account/roles/delete',
      text: '<i class="fa fa-times"></i> Permanently delete selected roles',
      className: 'btn btn-sm btn-danger pull-right',
      formClass: 'undoable'
    });
  }

  incr = index;

  index = $.inArray('create', trashedButtons);

  if (index !== -1) {
    index += incr;
    trashedTable.button().add(index, {
      extend: 'bulk',
      method: 'put',
      formId: 'trashed-restore',
      text: '<i class="fa fa-undo"></i> Restore selected roles',
      url: 'admin/inoplate-account/roles/restore',
      className: 'btn btn-sm btn-info pull-right'
    });
  }

}).call(this);

//# sourceMappingURL=index.js.map
