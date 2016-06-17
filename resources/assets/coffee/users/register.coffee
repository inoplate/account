$ "#user-registration-form"
    .on "ajax.form.success", () ->
        $ this
            .trigger "reset"

        $ 'select', this
            .trigger 'change'

$ '.library-finder', '.widget-user'        
    .on 'media.finder.selected', (e, id, library) ->
        $ 'input[name="avatar"]', "#user-registration-form"
            .val "/uploads/#{library.description.path}/thumb"