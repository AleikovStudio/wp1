
<tr class="form-field term-description-wrap">
    <th scope="row"><label for="short_description">Подзаголовок</label></th>
    <td>
        <textarea
                name="term_meta[short_description]"
                id="short_description"
                rows="2"
                cols="50"
                class="large-text"
        ><?php echo wpm_array_get($term_meta, 'short_description'); ?></textarea>
    </td>
</tr>

<tr>
    <th>Автотренинг</th>
    <td>
        <label>
        <?php if($term_meta['category_type'] == 'on'){ ?>
            <input type="checkbox" name="term_meta[category_type]" checked="">
        <?php }else{ ?>
            <input type="checkbox" name="term_meta[category_type]">
        <?php } ?>
        Сделать автотренингом</label>
    </td>
</tr>

<tr>
    <th>Стикер</th>
    <td>
        <div class="wpm-control-row">
            <label>
                <input type="hidden" name="term_meta[sticker_show]" value="off">
                <input
                    type="checkbox"
                    name="term_meta[sticker_show]"
                    <?php echo wpm_array_get($term_meta, 'sticker_show') == 'on' ? 'checked="checked"' : ''; ?>
                >
                Отображать
            </label>
        </div>
        <table>
            <tr class="form-field">
                <th style="width: 10%;">Цвет фона</th>
                <td>
                    <input
                        type="text"
                        name="term_meta[sticker_bg_color]"
                        class="color"
                        style="width: auto"
                        value="<?php echo wpm_array_get($term_meta, 'sticker_bg_color', 'cd814e'); ?>">
                </td>
            </tr>
            <tr class="form-field">
                <th>Цвет текста</th>
                <td>
                    <input
                        type="text"
                        name="term_meta[sticker_text_color]"
                        style="width: auto"
                        class="color"
                        value="<?php echo wpm_array_get($term_meta, 'sticker_text_color'); ?>">
                </td>
            </tr>
            <tr class="form-field">
                <th>Текст</th>
                <td><input
                    type="text"
                    maxlength="40"
                    name="term_meta[sticker_text]"
                    value="<?php echo wpm_array_get($term_meta, 'sticker_text'); ?>"></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <th>Индикаторы</th>
    <td>
        <div class="wpm-control-row">
            <label>
                <input type="hidden" name="term_meta[individual_indicators]" value="0">
                <input
                    type="checkbox"
                    id="individual_indicators_checkbox"
                    name="term_meta[individual_indicators]"
                    value="1"
                    <?php echo wpm_array_get($term_meta, 'individual_indicators', 0) ? 'checked="checked"' : ''; ?>
                >
                Индивидуальные настройки индикаторов данной рубрики
            </label>
        </div>
        <div
            style="padding-left: 25px; <?php echo wpm_array_get($term_meta, 'individual_indicators', 0) ? '' : 'display:none;'; ?>"
            class="wpm-control-row"
            id="individual_indicators">
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="term_meta[comments_show]" value="0">
                    <input
                        type="checkbox"
                        name="term_meta[comments_show]"
                        value="1"
                        <?php echo wpm_array_get($term_meta, 'comments_show', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать количество комментариев
                </label>
            </div>
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="term_meta[views_show]" value="0">
                    <input
                        type="checkbox"
                        name="term_meta[views_show]"
                        value="1"
                        <?php echo wpm_array_get($term_meta, 'views_show', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать количество просмотров
                </label>
            </div>
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="term_meta[progress_show]" value="0">
                    <input
                        type="checkbox"
                        name="term_meta[progress_show]"
                        value="1"
                        <?php echo wpm_array_get($term_meta, 'progress_show', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать прогресс курса
                </label>
            </div>
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="term_meta[access_show]" value="0">
                    <input
                        type="checkbox"
                        name="term_meta[access_show]"
                        value="1"
                        <?php echo wpm_array_get($term_meta, 'access_show', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать доступность
                </label>
            </div>
        </div>
    </td>
</tr>

<tr>
    <th>Описание рубрики</th>
    <td>
        <div class="wpm-control-row">
            <label>
                <input type="hidden" name="term_meta[individual_description]" value="0">
                <input
                    type="checkbox"
                    id="individual_description_checkbox"
                    name="term_meta[individual_description]"
                    value="1"
                    <?php echo wpm_array_get($term_meta, 'individual_description', 0) ? 'checked="checked"' : ''; ?>
                >
                Индивидуальные настройки описания данной рубрики
            </label>
        </div>
        <div
            style="padding-left: 25px; <?php echo wpm_array_get($term_meta, 'individual_description', 0) ? '' : 'display:none;'; ?>"
            class="wpm-control-row"
            id="individual_description">
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="term_meta[show_description]" value="0">
                    <input
                        type="checkbox"
                        name="term_meta[show_description]"
                        value="1"
                        <?php echo wpm_array_get($term_meta, 'show_description', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать
                </label>
            </div>
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="term_meta[description_expand]" value="0">
                    <input
                        type="checkbox"
                        name="term_meta[description_expand]"
                        value="1"
                        <?php echo wpm_array_get($term_meta, 'description_expand', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Открывать при входе
                </label>
            </div>
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="term_meta[show_description_always]" value="0">
                    <input
                        type="checkbox"
                        name="term_meta[show_description_always]"
                        value="1"
                        <?php echo wpm_array_get($term_meta, 'show_description_always', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Зафиксировать открытым
                </label>
            </div>
        </div>
    </td>
</tr>

<tr>
    <th>Доступ</th>
    <td>
        <select name="term_meta[visibility_level_action]">
            <option value="hide" <?php echo ($term_meta['visibility_level_action'] == 'hide')? 'selected':''; ?>>Не отображать для cледующих уровней доступа:</option>
            <option value="show_only" <?php echo ($term_meta['visibility_level_action'] == 'show_only')? 'selected':''; ?>>Отображать только для следующих уровней доступа:</option>
        </select>
            <?php  wpm_hierarchical_category_tree(0, $term_meta); ?>
        <p>
            <label>
                <input type="checkbox"
                       name="term_meta[hide_for_not_registered]"
                    <?php echo $term_meta['hide_for_not_registered']=='on' ? 'checked' : ''; ?>>
                Не отображать для незарегистрированных пользователей.
            </label>
        </p>
    </td>
</tr>

<tr>
    <th>Фоновое изображение</th>
    <td>
        <input type="hidden"
               id="wpm_term_bg_url"
               name="term_meta[bg_url]"
               value="<?php echo wpm_array_get($term_meta, 'bg_url'); ?>">

        <input type="hidden"
               id="wpm_term_bg_url_original"
               name="term_meta[bg_url_original]"
               value="<?php echo  wpm_array_get($term_meta, 'bg_url_original', wpm_array_get($term_meta, 'bg_url')); ?>">

        <div class="wpm-control-row">
            <p>
                <button type="button" class="wpm-media-upload-button button"
                        data-id="term_bg_url"><?php _e('Загрузить', 'wpm'); ?></button>
                <a id="delete-wpm-favicon"
                                class="wpm-delete-media-button button submit-delete"
                                data-id="term_bg_url"><?php _e('Удалить', 'wpm'); ?></a>
                <?php wpm_render_partial('crop-button', 'admin', array('key' => wpm_array_get($term_meta, 'bg_url'), 'id' => 'term_bg_url', 'w' => 345, 'h' => 260)) ?>
            </p>
        </div>
        <div class="wpm-term_bg_url-preview-wrap inactive grey">
            <div class="wpm-term_bg_url-preview-box">
                <img src="<?php echo wpm_array_get($term_meta, 'bg_url'); ?>" title="" alt=""
                     id="wpm-term_bg_url-preview">
            </div>
        </div>
    </td>
</tr>

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
        $('#individual_indicators_checkbox').on('change', function() {
            var $holder = $('#individual_indicators');

            if($(this).prop('checked')) {
                $holder.slideDown();
            } else {
                $holder.slideUp();
            }
        });
        $('#individual_description_checkbox').on('change', function() {
            var $holder = $('#individual_description');

            if($(this).prop('checked')) {
                $holder.slideDown();
            } else {
                $holder.slideUp();
            }
        });
        //--------
    });
</script>