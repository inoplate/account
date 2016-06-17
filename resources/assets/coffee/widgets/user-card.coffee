$ ->
    $ '.widget-user-image'
        .on 'click', () ->
            mediaSelectorWrapper = $ this
                                    .parents '.media-selector-wrapper'

            $ '.library-finder', mediaSelectorWrapper
                .libraryFinder 'show', 'widget-user-image'

$ '.library-finder'
    .on 'media.finder.selected', (e, id, library) ->
        mediaSelectorWrapper = $ this
                                    .parents '.media-selector-wrapper'

        errorMessage = mediaSelectorWrapper.data 'nonImageError'
        form = $ 'form', mediaSelectorWrapper

        if !isImage(library.description.mime)
            $.notify
                message: errorMessage
            ,
                type: 'error'
                placement:
                    align: 'center'
        else
            $ 'input[name="avatar"]', mediaSelectorWrapper
                .val "/uploads/#{library.description.path}/thumb"

            $ '.media-selector img', mediaSelectorWrapper
                .attr 'src', "/uploads/#{library.description.path}/thumb"

            if form.length
                form.submit()