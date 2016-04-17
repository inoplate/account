$ '#users-register-form, #users-update-form'
    .modal
        show: false

$ document
    .on 'ajax.form.success', '#trashed-restore', (evt, data, textStatus, jqXHR) ->
        activeUsersTable.draw()

$ document
    .on 'ajax.form.success', '#active-delete', (evt, data, textStatus, jqXHR) ->
        trashedUsersTable.draw()

$ 'form.ajax', '#users-register-form, #users-update-form'
    .on 'ajax.form.success', (evt, data, textStatus, jqXHR) ->
        $ this
            .trigger "reset"

        $ this
            .parents '.modal'
            .modal 'hide'

        $ 'select', this
            .trigger 'change'

        activeUsersTable.draw()

$ '#active-users-table'
    .on 'click', '.active-users-update', () ->
        that = this
        selectedRoles = []
        url = $ this
                  .prop 'href'

        $('form', '#users-update-form')[0]
            .reset()

        $ 'select', '#users-update-form'
            .trigger 'change'

        $overlay = $ '.overlay', '#users-update-form'

        $ '#users-update-form'
            .modal 'show'
        
        $overlay.removeClass 'hide'

        $.get url, (result) ->
            $overlay.addClass 'hide'

            $ 'form', '#users-update-form'
                .prop 'action', "/admin/inoplate-account/users/#{result.user.id}"

            $ 'input[name="username"]', '#users-update-form'
                .val result.user.username
            $ 'input[name="email"]', '#users-update-form'
                .val result.user.email
            $ 'input[name="name"]', '#users-update-form'
                .val result.user.description.name
            $ 'select[name="status"]', '#users-update-form'
                .val result.user.description.active

            $.each result.user.roles, () ->
                selectedRoles.push(result.user.roles.id)

            selectedRoles = for key, role of result.user.roles
                "#{role.id}"

            $ 'select[name="roles[]"]', '#users-update-form'
                .val selectedRoles

            $ 'select', '#users-update-form'
                .trigger 'change'

        false


activeUsersTable = $ '#active-users-table'
                        .DataTable
                            order: [[ 6, "desc" ]],
                            columns: [
                                "name": "id"
                            ,
                                "name": "username"
                            ,
                                "name": "email"
                            ,
                                "name": "name"
                            ,
                                "name": "roles"
                            ,
                                "name": "active"
                            ,
                                "name": "created_at"
                            ,
                                "name": "actions"
                            ],
                            columnDefs: [
                                targets: 0
                                sortable: false
                                searchable: false
                            ,
                                targets: 4
                                searchable: false
                                sortable: false
                                render: (data, type, row, meta) ->
                                    rendered = ''
                                    rendered += "<span class=\"label bg-blue\">#{role}</span> " for role, i in data

                                    return rendered
                            ,
                                targets: 5
                                render: (data, type, row, meta) ->
                                    return if data == 1 then '<span class=\"label bg-blue\">Active</span>' else '<span class=\"label bg-orange\">Inactive</span>'
                            ,
                                targets: 6
                                render: (data, type, row, meta) ->
                                    return moment data
                                                .format 'LL'
                            ,
                                targets: 7
                                searchable: false
                                sortable: false
                                render: (data, type, row, meta) ->
                                    rendered = '<div class="text-right">'
                                    token = $ 'meta[name="csrf-token"]'
                                                .attr 'content'

                                    if $.inArray 'update', data != -1 then rendered += "<a href=\"/admin/inoplate-account/users/#{row[0]}/edit\" class=\"btn btn-default btn-sm active-users-update\"><i class=\"fa fa-pencil\"></i></a>"

                                    rendered += '</div>'

                                    return rendered
                            ]
                            ajax: 
                                url: 'admin/inoplate-account/users/datatables'
                                data: (d) ->
                                    d.roles = $ 'select[name="roles[]"]'
                                                .val()
                                    d.active = $ 'select[name="active"]'
                                                .val()

                                    return

                            buttons: true

trashedUsersTable = $ '#trashed-users-table'
                        .DataTable
                            order: [[ 6, "desc" ]],
                            columns: [
                                "name": "id"
                            ,
                                "name": "username"
                            ,
                                "name": "email"
                            ,
                                "name": "name"
                            ,
                                "name": "roles"
                            ,
                                "name": "active"
                            ,
                                "name": "deleted_at"
                            ],
                            columnDefs: [
                                targets: 0
                                sortable: false
                                searchable: false
                            ,
                                targets: 4
                                searchable: false
                                sortable: false
                                render: (data, type, row, meta) ->
                                    rendered = ''
                                    rendered += "<span class=\"label bg-blue\">#{role}</span> " for role, i in data

                                    return rendered
                            ,
                                targets: 5
                                render: (data, type, row, meta) ->
                                    return if data == 1 then '<span class=\"label bg-blue\">Active</span>' else '<span class=\"label bg-orange\">Inactive</span>'
                            ,
                                targets: 6
                                render: (data, type, row, meta) ->
                                    return moment data
                                                .format 'LL'
                            ]
                            ajax: 
                                url: 'admin/inoplate-account/users/datatables/trashed'
                                data: (d) ->
                                    d.roles = $ 'select[name="roles[]"]'
                                                .val()
                                    d.active = $ 'select[name="active"]'
                                                .val()

                                    return

                            buttons: true

activeUsersButtons = $ '#active-users-table'
                        .data 'actions'

incr = 2;

index = $.inArray 'delete', activeUsersButtons

if index != -1 
    index += incr;
    activeUsersTable.button()
        .add index,
            extend: 'bulk'
            method: 'delete'
            url: 'admin/inoplate-account/users'
            text: '<i class="fa fa-user-times"></i> Unregister selected users'
            className: 'btn btn-sm btn-danger pull-right'
            formId: 'active-delete'

incr = index

index = $.inArray 'register', activeUsersButtons

if index != -1 
    index += incr;
    activeUsersTable.button()
        .add index,
            text: '<i class="fa fa-user-plus"></i> Register new user'
            url: 'admin/inoplate-account/users/register'
            className: 'btn btn-sm btn-primary pull-right'
            init: ( dt, button, config )->
                $ button.prop "href", config.url
            action: (e, dt, node, config) ->
                container = dt.table().container()

                $ '#users-register-form'
                    .modal 'show'

                return

trashedUsersButtons = $ '#trashed-users-table'
                        .data 'actions'

incr = 2;

index = $.inArray 'delete', trashedUsersButtons

if index != -1 
    index += incr;
    trashedUsersTable.button()
        .add index,
            extend: 'bulk'
            method: 'delete'
            url: 'admin/inoplate-account/users/delete'
            text: '<i class="fa fa-times"></i> Permanently delete selected users'
            className: 'btn btn-sm btn-danger pull-right'
            formClass: 'undoable'

incr = index

index = $.inArray 'register', trashedUsersButtons

if index != -1 
    index += incr;
    trashedUsersTable.button()
        .add index,
            extend: 'bulk'
            method: 'put'
            formId: 'trashed-restore'
            text: '<i class="fa fa-undo"></i> Restore selected users'
            url: 'admin/inoplate-account/users/restore'
            className: 'btn btn-sm btn-info pull-right'