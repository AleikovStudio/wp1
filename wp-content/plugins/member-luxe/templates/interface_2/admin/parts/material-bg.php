 <input type="hidden"
       id="wpm_post_bg_url"
       name="page_meta[bg_url]"
       value="<?php echo wpm_array_get($page_meta, 'bg_url'); ?>">

 <input type="hidden"
       id="wpm_post_bg_url_original"
       name="page_meta[bg_url_original]"
       value="<?php echo wpm_array_get($page_meta, 'bg_url_original', wpm_array_get($page_meta, 'bg_url')); ?>">

<div class="wpm-control-row">
    <p>
        <button type="button" class="wpm-media-upload-button button"
                data-id="post_bg_url"><?php _e('Загрузить', 'wpm'); ?></button>
        <a id="delete-wpm-favicon"
                        class="wpm-delete-media-button button submit-delete"
                        data-id="post_bg_url"><?php _e('Удалить', 'wpm'); ?></a>
        <?php wpm_render_partial('crop-button', 'admin', array('key' => wpm_array_get($page_meta, 'bg_url'), 'id' => 'post_bg_url', 'w' => 295, 'h' => 220)) ?>
    </p>
</div>
<div class="wpm-post_bg_url-preview-wrap inactive">
    <div class="wpm-post_bg_url-preview-box">
        <img src="<?php echo wpm_array_get($page_meta, 'bg_url'); ?>" title="" alt=""
             id="wpm-post_bg_url-preview">
    </div>
</div>

<script>
    jQuery(function ($) {
        // Upload media file ====================================
        var wpm_file_frame;
        var image_id = '';
        $('.wpm-media-upload-button').live('click', function (event) {
            image_id = $(this).attr('data-id');
            event.preventDefault();
            // If the media frame already exists, reopen it.
            if (wpm_file_frame) {
                wpm_file_frame.open();
                return;
            }
            // Create the media frame.
            wpm_file_frame = wp.media.frames.downloadable_file = wp.media({
                title    : 'Выберите файл',
                button   : {
                    text : 'Использовать изображение'
                },
                multiple : false
            });
            // When an image is selected, run a callback.
            wpm_file_frame.on('select', function () {
                var attachment = wpm_file_frame.state().get('selection').first().toJSON();
                // console.log(attachment);
                $('input#wpm_' + image_id).val(attachment.url);
                if($('input#wpm_' + image_id + '_original').length) {
                    $('input#wpm_' + image_id + '_original').val(attachment.url);
                }
                $('#wpm-' + image_id + '-preview').attr('src', attachment.url).show();
                $('#delete-wpm-' + image_id).show();
                $('.wpm-crop-media-button[data-id="' + image_id +'"]').show();
            });
            // Finally, open the modal.
            wpm_file_frame.open();
        });
        $('.wpm-delete-media-button').on('click', function () {
            image_id = $(this).attr('data-id');
            $('input#wpm_' + image_id).val('');
            if($('input#wpm_' + image_id + '_original').length) {
                $('input#wpm_' + image_id + '_original').val('');
            }
            $('#delete-wpm-' + image_id).hide();
            $('#wpm-' + image_id + '-preview').hide();
            $('.wpm-crop-media-button[data-id="' + image_id +'"]').hide();
        });
        //--------
    });
</script>