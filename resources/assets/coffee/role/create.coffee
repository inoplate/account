$ "#role-create-form"
    .on "ajax.form.success", () ->
        $ this
            .trigger "reset"