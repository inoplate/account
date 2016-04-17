$ "#user-registration-form"
    .on "ajax.form.success", () ->
        $ this
            .trigger "reset"

        $ 'select', this
            .trigger 'change'