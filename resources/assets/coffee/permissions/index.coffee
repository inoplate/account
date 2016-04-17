$ '#permissions-table'
    .DataTable
        dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-12"rt>><"row"<"col-sm-5"i><"col-sm-7"p>>'
        serverSide: false
        ajax: false
        createdRow: (row, data, index) ->
            true

$ '#permissions-table'
    .on 'change', 'input[name="attached"]', () ->
        that = this
        form = $ this
                    .parents 'form'

        form.submit()

$ '#permissions-table'
    .on 'ajax.form.beforeSend', 'form.ajax', () ->
        $ 'input[name="attached"]', this
            .iCheck 'disable'

$ '#permissions-table'
    .on 'ajax.form.complete', 'form.ajax', () ->
        console.log this
        $ 'input[name="attached"]', this
            .iCheck 'enable'