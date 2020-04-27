import Widgets from 'js-widget-hooks';

Widgets.register('wp-image-attachments', function (el) {

    const $ = jQuery;
    const elem = $(el);
    const multiMode = elem.data('multi_select');
    const input = elem.find('.js-trigger--ids');
    let newIds = [];

    if (input.val()) {
        input.val().split(',').forEach(function (val, index) {
            newIds[index] = parseInt(val);
        })
    }

    var media_popup;
    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
    var set_to_post_id = elem.data('attachment_post_id'); // Set this

    // @todo add drag & drop https://alligator.io/js/drag-and-drop-vanilla-js/

    elem.find('.js-trigger--upload').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( media_popup ) {
            // Set the post ID to what we want
            media_popup.uploader.uploader.param( 'post_id', set_to_post_id );
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            wp.media.model.settings.post.id = set_to_post_id;


            // Create the media frame.
            media_popup = wp.media.frames.file_frame = wp.media({
                title: multiMode ? 'Select one or more images' : 'Select or Upload an image',
                button: {
                    text: multiMode ? 'use this images' : 'use this image',
                },
                multiple: multiMode	// Set to true to allow multiple files to be selected
            });

            if (multiMode) {

                media_popup.on('open', function () {
                    // we filter out everything, that was already selected
                    // kind of hacky fix for now
                    media_popup.state().frame.$el.find('.attachment').each(function () {
                        if (newIds.indexOf(parseInt($(this).data('id'))) > -1) {
                            $(this).hide();
                        }
                    });
                });
            }

            // When an image is selected, run a callback.
            media_popup.on( 'select', function () {

                const previewArea = elem.find('.image-preview-wrapper');

                if (!multiMode) {
                    previewArea.empty();
                    newIds = [];
                }

                media_popup.state().get('selection').forEach(function (media) {

                    let image = media.toJSON();

                    if (newIds.indexOf(image.id) < 0) {
                        let previewUrl = image.sizes.thumbnail.url;
                        let title = image.caption ? image.caption : image.filename;

                        previewArea.append('<span class="image-preview" draggable="true" data-id="' + image.id + '"><img src="' + previewUrl + '" title="' + title + '" style="width: auto; height: 80px" /></span>');

                        newIds.push(image.id);
                    } else {
                        console.warn("Ignored image %o, because it is already part of the list", image.id);
                    }
                });

                elem.find('.js-trigger--ids').val( newIds.join(',') );

                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });
        }

        // Open frame
        media_popup.open();
    });

    // Restore the main ID when the add media button is pressed
    jQuery( 'a.add_media' ).on( 'click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });

});