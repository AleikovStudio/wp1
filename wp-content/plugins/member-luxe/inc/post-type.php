<?php

/**
 *
 */
add_action( 'restrict_manage_posts', 'my_restrict_manage_posts' );
function my_restrict_manage_posts() {
    global $typenow;
    if($typenow != 'wpm-page') return;
    $taxonomy = 'wpm-category';
    
        $filters = array(
            'wpm-category', 
            'wpm-levels'
            );
        foreach ($filters as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $tax_name = mb_strtolower($tax_name);
            $terms = get_terms($tax_slug);
            echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
            echo "<option value=''>Все $tax_name</option>";
            foreach ($terms as $term) { echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; }
            echo "</select>";
        }

}

//---------------

add_action('wpm-category_edit_form_fields', 'wpm_category_edit', 10, 2);
function wpm_category_edit($term, $taxonomy){
    $term_id = $term->term_id;
    $term_meta = get_option( "taxonomy_term_$term_id" );
    if(!isset($term_meta['hide_for_not_registered'])){
        $term_meta['hide_for_not_registered'] = 'hide';
    }

    wpm_render_partial('category-edit', 'admin', compact('term', 'taxonomy', 'term_id', 'term_meta'));
}

add_action('wpm-category_edit_form_fields', 'wpm_category_edit_add', 15, 2);
function wpm_category_edit_add($term, $taxonomy){
    $term_id = $term->term_id;
    $term_meta = get_option( "taxonomy_term_$term_id" );
    ?>
    <tr>
        <th>&nbsp;</th>
        <td>
            <p>
                <label>
                    <input type="checkbox"
                           name="term_meta[hide_materials]"
                        <?php echo ($term_meta && array_key_exists('hide_materials', $term_meta) && $term_meta['hide_materials']=='on') ? 'checked' : ''; ?>>
                    Скрыть материалы, показывать только описание.
                </label>
            </p>
        </td>
    </tr>

<?php
}


function wpm_save_category_fields($term_id)
{

    /* if(!isset($term_meta_new['category_type'])){
             $term_meta_new['category_type'] = '';
         }*/

    if (isset($_POST['term_meta'])) {

        $term_meta = get_option("taxonomy_term_$term_id");
        $cat_keys = array_keys($_POST['term_meta']);

        if(!array_key_exists('category_type',$cat_keys)) {
            $term_meta['category_type'] = 'off';
        }

        if(!array_key_exists('hide_for_not_registered',$cat_keys)) {
            $term_meta['hide_for_not_registered'] = 'off';
        }

        if(!array_key_exists('hide_materials',$cat_keys)) {
            $term_meta['hide_materials'] = 'off';
        }

        foreach ($cat_keys as $key) {
            if (isset($_POST['term_meta'][$key])) {
                if ($key == 'exclude_levels') {
                    $term_meta[$key] = implode(',', $_POST['term_meta'][$key]);
                } else {
                    $term_meta[$key] = stripslashes(wp_filter_post_kses(addslashes($_POST['term_meta'][$key])));
                }
            }
        }
        if(!isset($_POST['term_meta']['exclude_levels'])) {
            unset($term_meta['exclude_levels']);
        }
        update_option("taxonomy_term_$term_id", $term_meta);

        if ($term_meta['category_type'] == 'on') {
            wpm_autotraining_schedule_option($term_id);
        }
    }elseif(!isset($_POST['_inline_edit'])){
        update_option("taxonomy_term_$term_id", array());
    }
}

add_action('edited_wpm-category', 'wpm_save_category_fields', 10, 2);

//-----------

function wpm_for_array_map($data){
    $units = array_key_exists('units', $data) ? $data['units'] : 'months';

    return $data['duration'] . '_' . $units;
}

function wpm_get_keys_html_list ($term_keys, $term_id)
{
    $data = '';
    $result = array();

    if(is_array($term_keys) && !empty($term_keys)){

        $keys_by_period = array_count_values(array_map('wpm_for_array_map', $term_keys));

        foreach ($keys_by_period as $key => $value) {
            $key_params = explode('_', $key);
            $duration = $key_params[0];
            $units = $key_params[1];
            $result[$key]['new'] = 0;
            $result[$key]['used'] = 0;

            foreach ($term_keys as $item) {
                $status = $item['status'];

                if(isset($item['sent']) && $item['sent']) {
                    $status = 'used';
                }

                if ($status == 'new' && $item['duration'] == $duration && wpm_is_units_equal($item, $units)) {
                    $result[$key]['new']++;
                }
                if ($status == 'used' && $item['duration'] == $duration && wpm_is_units_equal($item, $units)) {
                    $result[$key]['used']++;
                }
            }

            switch ($units) {
                case 'months':
                    $units_msg = 'мес.';
                    break;
                case 'days':
                    $units_msg = 'дн.';
                    break;
            }

            $data .= '<tr>' .
                         '<td>' . $duration . ' ' . $units_msg . '</td>' .
                         '<td>' . $value . '</td>' .
                         '<td style="white-space: nowrap">' .
                             $result[$key]["used"] .
                            ($result[$key]["used"] ? ' <button type="button" class="button  show-used" term_id="' . $term_id . '" duration="' . $duration . '" units="' . $units . '">Показать</button>' : '') .
                         '</td>' .
                         '<td style="white-space: nowrap">' .
                             $result[$key]["new"] .
                             ' <button type="button" class="button show-keys" term_id="' . $term_id . '" duration="' . $duration . '" units="' . $units . '">Показать</button>' .
                             '<a class="delete-button delete-keys remove-keys" term_id="' . $term_id . '" duration="' . $duration . '" units="' . $units . '">Удалить</a>' .
                         '</td>' .
                     '</tr>';
        }
    }

    return $data;
}

add_action('wpm-levels_edit_form_fields', 'wpm_level_taxonomy_keys', 10, 2);
function wpm_level_taxonomy_keys($term, $taxonomy)
{
    add_thickbox();

    // put the term ID into a variable
    $term_id = $term->term_id;
    $term_meta = get_option("taxonomy_term_$term_id" );
    $term_keys = MBLTermKeysQuery::find(array('term_id' => $term_id, 'key_type' => 'wpm_term_keys', 'is_banned' => 'all'));

    ?>
    <script>
        jQuery(function ($) {

            $(document).on('click', '.add-manual-keys', function () {

                var manual_message_wrap = $('.add-manual-keys-message');
                manual_message_wrap.html('');
                var duration_manual = $('#duration-manual').val();
                var units_manual = $('#units-manual').val();
                var keys_manual = $('#manual-keys').val();


                keys_manual = keys_manual.replace(/  +/g, ' ');

                if(keys_manual == "" || keys_manual == ' '){
                    alert('Сначала вставте свои ключи.');
                    $('#manual-keys').val('');
                    return;
                }

                if(Math.floor(duration_manual) != duration_manual || !$.isNumeric(duration_manual) || duration_manual < 0){
                    alert('Неверное значение периода. Только целые числа, больше нуля.');
                    return;
                }


                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    dataType: 'json',
                    data: {
                        action: "wpm_add_manual_user_level_keys_action",
                        term_id: <?php echo $term_id; ?>,
                        duration: duration_manual,
                        units: units_manual,
                        keys: keys_manual
                    },
                    success: function (data) {
                        if(!data.error){
                            $('#keys-list').html(data.html);
                            manual_message_wrap.html('<span class="wpm-message wpm-message-success">' + data.message + '</span>');

                        }else{
                            manual_message_wrap.html('<span class="wpm-message wpm-message-fail">' + data.message + '</span>');
                        }


                    },
                    error: function (errorThrown) {

                    }
                });
            });

            $(document).on('click', '.add-keys', function () {
                var duration = $('#duration').val();
                var units = $('#units').val();
                var count = $('#count').val();
                var format = $('');
                if(Math.floor(duration) != duration || !$.isNumeric(duration) || duration < 0){
                    alert('Неверное значение периода. Только целые числа, больше нуля.');
                    return;
                }
                if(Math.floor(count) != count || !$.isNumeric(count) || count < 0){
                    alert('Неверное значение количества колчей. Только целые числа, больше нуля.');
                    return;
                } 

                tb_show("<?php _e('Ключи', 'wpm'); ?>", "#TB_inline?width=640&&height=550&inlineId=wpm_popup_box");
                $('#TB_ajaxContent').find('#user-level-keys').html('');
                $('.wpm-top-popup-nav .message').html('');
                $('#TB_ajaxContent').css({'width': '640', 'height': ($('#TB_window').height() - 50) + 'px'}).addClass('wpm-loader');
                $.ajax({
                    type     : 'POST',
                    url      : ajaxurl,
                    dataType : 'json',
                    data     : {
                        action   : "wpm_add_user_level_keys_action",
                        term_id  : <?php echo $term_id; ?>,
                        count    : count,
                        duration : duration,
                        units    : units
                    },
                    success  : function (data) {
                        var $TBAjaxContent = $('#TB_ajaxContent');

                        $TBAjaxContent.find('#user-level-keys').html(data.keys);
                        $TBAjaxContent.removeClass('wpm-loader');

                        $('#keys-list').html(data.html);
                    },
                    error    : function (errorThrown) {

                    }
                });
            });


            /*  show keys in popup */
            $(document).on('click', '.show-keys', function () {
                var $TBAjaxContent;

                tb_show("<?php _e('Ключи', 'wpm'); ?>", "#TB_inline?width=640&&height=550&inlineId=wpm_popup_box");

                $TBAjaxContent = $('#TB_ajaxContent');
                $TBAjaxContent.find('#user-level-keys').html('');
                $TBAjaxContent.find('.wpm-top-popup-nav').hide();
                $('.wpm-top-popup-nav .message').html('');
                $TBAjaxContent.css({'width': '640', 'height': ($('#TB_window').height() - 50) + 'px'}).addClass('wpm-loader');

                var $duration = $(this).attr('duration');
                var $units = $(this).attr('units');

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: "wpm_get_keys_action",
                        term_id: $(this).attr('term_id'),
                        duration: $duration,
                        units: $units
                    },
                    success : function (data) {
                        var $TBAjaxContent = $('#TB_ajaxContent');
                        $TBAjaxContent.find('#user-level-keys').html(data);
                        $TBAjaxContent.removeClass('wpm-loader');
                        $TBAjaxContent.find('[name="wpm_move_keys_duration_from"]').val($duration);
                        $TBAjaxContent.find('[name="wpm_move_keys_units_from"]').val($units);

                        if (data == '') {
                            $TBAjaxContent.find('.wpm-top-popup-nav').hide();
                        } else {
                            $TBAjaxContent.find('.wpm-top-popup-nav').show();
                        }
                    }
                });
            });
            $(document).on('click', '.show-used', function () {
                var $TBAjaxContent;

                tb_show("<?php _e('Ключи', 'wpm'); ?>", "#TB_inline?width=640&&height=550&inlineId=wpm_popup_box");

                $TBAjaxContent = $('#TB_ajaxContent');
                $TBAjaxContent.find('#user-level-keys').html('');
                $TBAjaxContent.find('.wpm-top-popup-nav').hide();
                $TBAjaxContent.find('#wpm-move-keys-tools').hide();

                $('.wpm-top-popup-nav .message').html('');
                $TBAjaxContent.css({'width': '640', 'height': ($('#TB_window').height() - 50) + 'px'}).addClass('wpm-loader');

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: "wpm_get_used_keys_action",
                        term_id: $(this).attr('term_id'),
                        duration: $(this).attr('duration'),
                        units: $(this).attr('units')
                    },
                    success: function (data) {
                        var $TBAjaxContent = $('#TB_ajaxContent');
                        $TBAjaxContent.find('#user-level-keys').html(data);
                        $TBAjaxContent.removeClass('wpm-loader')
                    }
                });
            });

            var notification = $('.notification');

            $(document).on('click', '.remove-keys', function () {
                var duration = $(this).attr('duration');
                var units = $(this).attr('units');
                var do_remove = confirm("<?php _e('Вы действительно хотите удалить ключи?'); ?>");
                if (do_remove) {
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: "wpm_remove_user_level_keys_action",
                            term_id: <?php echo $term_id; ?>,
                            duration: duration,
                            units: units
                        },
                        success: function (data) {
                            if (!data.error) {
                                location.reload();
                            } else {
                                alert('Коды не удалены');
                            }

                        }
                    });
                }

            });

            /* copy keys to clipboard */
            var copy_keys = new ZeroClipboard( $('.wpm-copy-keys'), {
                moviePath: "<?php echo plugins_url('/member-luxe/js/zeroclipboard/ZeroClipboard.swf') ?>"
            } );
            copy_keys.on("aftercopy", function(e) {
                $('.wpm-top-popup-nav .message').html('<?php _e('Скопировано!','wpm'); ?>');
            });


            $('.wpm-tabs').tabs({
                autoHeight: false,
                collapsible: false,
                fx: {
                    opacity: 'toggle',
                    duration: 'fast'
                }
            });

            $('.justclick_subscribe_trigger').on('change', function () {
                var $trigger = $(this),
                    $wrapper = $trigger.closest('.justclick_subscribe'),
                    $holder = $wrapper.find('.justclick_subscribe_holder'),
                    $isActive = $trigger.prop('checked');

                if ($isActive) {
                    $wrapper.addClass('active');
                    $holder.slideDown();
                } else {
                    $wrapper.removeClass('active');
                    $holder.slideUp();
                }

                if(!$isActive) {
                    $holder.find('select').val([]);
                }

                return false;
            });
            $('.justclick_unsubscribe').on('change', function () {
                var $holder = $('#justclick_del_level_groups');
                if($(this).val() == '') {
                    $holder.slideDown();
                } else {
                    $holder.find('select').val([]);
                    $holder.slideUp();
                }
            });

            var $justclickLevelGroups = $('#justclick_level_groups');
            if ($justclickLevelGroups.length) {
                $.post(ajaxurl, {
                    'action' : 'wpm_justclick_groups_level_select',
                    'term_id' : '<?php echo $term_id; ?>'
                }, function (html) {
                    $justclickLevelGroups.html(html);
                    $justclickLevelGroups.closest('.justclick_subscribe_holder').addClass('loaded');
                })
            }

            var $justclickDelLevelGroups = $('#justclick_del_level_groups');
            if ($justclickDelLevelGroups.length) {
                $.post(ajaxurl, {
                    'action' : 'wpm_justclick_groups_del_level_select',
                    'term_id' : '<?php echo $term_id; ?>'
                }, function (html) {
                    $justclickDelLevelGroups.html(html);
                    $justclickDelLevelGroups.closest('.justclick_subscribe_holder').addClass('loaded');
                })
            }

        });
    </script>
    
</pre>
    <tr>
        <th>
            Видимость
        </th>
        <td>
            <p><label><input type="checkbox" name="term_meta[hide_for_no_access]" value="hide" <?php if($term_meta['hide_for_no_access'] == 'hide') echo 'checked'; ?> >Скрывать материалы если нет доступа к этому уровню</label></p>
        </td>
    </tr>
    <tr class="form-field">
        <th><label><?php _e('Продажа доступа', 'wpm'); ?></label></th>
        <td>
            <div style="width: 95%">
                <?php wp_editor( stripslashes($term_meta['no_access_content']), 'no_access_content', array('textarea_name' => 'term_meta[no_access_content]', 'textarea_rows' => '20')); ?>
            </div>

        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="wpm_keys_new"><?php _e('Письма напоминания', 'wpm'); ?></label></th>
        <td>
            <div class="wpm-tabs-wrap postbox wpm-ui-wrap">
                <div class="wpm-inner-wrap">
                    <div class="wpm-tabs wpm-inner-tabs">
                        <ul class="wpm-inner-tabs-nav">
                            <li><a href="#tab-1"><?php _e('Письмо 1', 'wpm'); ?></a></li>
                            <li><a href="#tab-2"><?php _e('Письмо 2', 'wpm'); ?></a></li>
                            <li><a href="#tab-3"><?php _e('Письмо 3', 'wpm'); ?></a></li>
                            <li><a href="#tab-4"><?php _e('Массовые операции', 'wpm'); ?></a></li>
                        </ul>
                        <div id="tab-1" class="tab">
                            <div class="wpm-tab-content">
                                <div class="wpm-row">
                                    <label class="schedule-days-wrap">
                                        <?php _e('Отправить письмо за', 'wpm'); ?> <input type="number" min="1" size="2" maxlength="2" name="term_meta[letter_1_days]" value="<?php echo $term_meta['letter_1_days'] ?>">  <?php _e(' дней до окончания срока', 'wpm'); ?>
                                    </label>
                                </div>
                                <div class="wpm-row">
                                    <label>
                                        <input name="term_meta[letter_1_title]" type="text" class="large-text" value="<?php echo $term_meta['letter_1_title'] ?>" placeholder="<?php _e('Заголовок письма', 'wpm'); ?>">
                                    </label>
                                </div>
                                <div class="wpm-row">
                                    <?php wp_editor( stripslashes($term_meta['letter_1']), 'letter_1', array('textarea_name' => 'term_meta[letter_1]', 'textarea_rows' => '10')); ?>
                                </div>
                                <div class="wpm-help-wrap">
                                    <p>
                                        <span class="code-string">[user_name]</span> - имя пользователя <br>
                                        <span class="code-string">[user_login]</span> - логин пользователя <br>
                                        <span class="code-string">[start_page]</span> - страница входа <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="tab-2" class="tab">
                            <div class="wpm-tab-content">
                                <div class="wpm-row">
                                    <label class="schedule-days-wrap">
                                        <?php _e('Отправить письмо за', 'wpm'); ?> <input type="number" min="1" size="2" maxlength="2" name="term_meta[letter_2_days]" value="<?php echo $term_meta['letter_2_days'] ?>">  <?php _e(' дней до окончания срока', 'wpm'); ?>
                                    </label>
                                </div>
                                <div class="wpm-row">
                                    <label>
                                        <input name="term_meta[letter_2_title]" type="text" class="large-text" value="<?php echo $term_meta['letter_2_title'] ?>" placeholder="<?php _e('Заголовок письма', 'wpm'); ?>">
                                    </label>
                                </div>
                                <div class="wpm-row">
                                    <?php wp_editor( stripslashes($term_meta['letter_2']), 'letter_2', array('textarea_name' => 'term_meta[letter_2]', 'textarea_rows' => '20')); ?>
                                </div>
                                <div class="wpm-help-wrap">
                                    <p>
                                        <span class="code-string">[user_name]</span> - имя пользователя <br>
                                        <span class="code-string">[user_login]</span> - логин пользователя <br>
                                        <span class="code-string">[start_page]</span> - страница входа <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="tab-3" class="tab">
                            <div class="wpm-tab-content">
                                <div class="wpm-row">
                                    <label class="schedule-days-wrap">
                                        <?php _e('Отправить письмо за', 'wpm'); ?> <input type="number" min="1" size="2" maxlength="2" name="term_meta[letter_3_days]" value="<?php echo $term_meta['letter_3_days'] ?>">  <?php _e(' дней до окончания срока', 'wpm'); ?>
                                    </label>
                                </div>
                                <div class="wpm-row">
                                    <label>
                                        <input name="term_meta[letter_3_title]" type="text" class="large-text" value="<?php echo $term_meta['letter_3_title'] ?>" placeholder="<?php _e('Заголовок письма', 'wpm'); ?>">
                                    </label>
                                </div>
                                <div class="wpm-row">
                                    <?php wp_editor( stripslashes($term_meta['letter_3']), 'letter_3', array('textarea_name' => 'term_meta[letter_3]', 'textarea_rows' => '20')); ?>
                                </div>
                                <div class="wpm-help-wrap">
                                    <p>
                                        <span class="code-string">[user_name]</span> - имя пользователя <br>
                                        <span class="code-string">[user_login]</span> - логин пользователя <br>
                                        <span class="code-string">[start_page]</span> - страница входа <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div id="tab-4" class="tab">
                            <div class="wpm-tab-content">
                                <br>
                                <div class="wpm-tabs wpm-inner-tabs">
                                    <ul class="wpm-inner-tabs-nav">
                                        <li><a href="#tab-1"><?php _e('Регистрация новых пользователей', 'wpm'); ?></a></li>
                                        <li><a href="#tab-2"><?php _e('Добавление ключей', 'wpm'); ?></a></li>
                                    </ul>
                                    <div id="tab-1" class="tab">
                                        <div class="wpm-tab-content">
                                            <div class="wpm-row">
                                                <label>
                                                    <input name="term_meta[mass_users_title]" type="text" class="large-text" value="<?php echo $term_meta['mass_users_title'] ?>" placeholder="<?php _e('Заголовок письма', 'wpm'); ?>">
                                                </label>
                                            </div>
                                            <div class="wpm-row">
                                                <?php wp_editor( stripslashes($term_meta['mass_users_message']), 'mass_users_message', array('textarea_name' => 'term_meta[mass_users_message]', 'textarea_rows' => '20')); ?>
                                            </div>
                                        </div>
                                        <div class="wpm-help-wrap">
                                            <p>
                                                <span class="code-string">[user_login]</span> - логин пользователя <br>
                                                <span class="code-string">[user_pass]</span> - пароль пользователя <br>
                                                <span class="code-string">[start_page]</span> - страница входа <br>
                                                <span class="code-string">[term_name]</span> - название уровня доступа <br>
                                            </p>
                                        </div>
                                    </div>
                                    <div id="tab-2" class="tab">
                                        <div class="wpm-tab-content">
                                            <div class="wpm-row">
                                                <label>
                                                    <input name="term_meta[mass_keys_title]" type="text" class="large-text" value="<?php echo $term_meta['mass_keys_title'] ?>" placeholder="<?php _e('Заголовок письма', 'wpm'); ?>">
                                                </label>
                                            </div>
                                            <div class="wpm-row">
                                                <?php wp_editor( stripslashes($term_meta['mass_keys_message']), 'mass_keys_message', array('textarea_name' => 'term_meta[mass_keys_message]', 'textarea_rows' => '20')); ?>
                                            </div>
                                        </div>
                                        <div class="wpm-help-wrap">
                                            <p>
                                                <span class="code-string">[user_name]</span> - имя пользователя <br>
                                                <span class="code-string">[user_login]</span> - логин пользователя <br>
                                                <span class="code-string">[start_page]</span> - страница входа <br>
                                                <span class="code-string">[term_name]</span> - название уровня доступа <br>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </td>

    </tr>


    <?php if (wpm_levels_show_subscriptions()) : ?>
        <tr>
            <th><label for="wpm_keys_new"><?php _e('Автоподписки', 'wpm'); ?></label></th>
            <td>
                <div class="wpm-tabs-wrap postbox wpm-ui-wrap">
                    <div class="wpm-inner-wrap">
                        <div class="wpm-tabs wpm-inner-tabs">
                            <ul class="wpm-inner-tabs-nav">
                                        <li><a href="#wpm_subscr_tab_1"><?php _e('JustClick', 'wpm'); ?></a></li>
                                <?php /*
                                    <li><a href="#wpm_subscr_tab_2"><?php _e('SmartResponder', 'wpm'); ?></a></li>
                                    <li><a href="#wpm_subscr_tab_3"><?php _e('GetResponce', 'wpm'); ?></a></li>
                                    <li><a href="#wpm_subscr_tab_4"><?php _e('UniSender', 'wpm'); ?></a></li>
                                */ ?>
                            </ul>

                            <div id="wpm_subscr_tab_1" class="tab">
                                <?php $justclickRid =  wpm_array_get($term_meta, 'auto_subscriptions.justclick.rid'); ?>
                                <?php $justclickDelRid =  wpm_array_get($term_meta, 'auto_subscriptions.justclick.del_rid'); ?>
                                <div>
                                    <div class="justclick_subscribe">
                                        <input type="hidden" name="term_meta[auto_subscriptions][justclick][rid]" value="">
                                        <label>
                                            <input
                                                type="checkbox"
                                                class="justclick_subscribe_trigger"
                                                <?php echo $justclickRid ? 'checked="checked"' : ''; ?>>
                                            <?php _e('Подписать', 'wpm'); ?>
                                        </label>
                                        <div class="justclick_subscribe_holder" <?php echo !$justclickRid ? 'style="display:none;"' : ''; ?>>
                                            <div id="justclick_level_groups"><div class="wpm-inline-loader"></div></div>
                                        </div>
                                    </div>
                                    <div class="justclick_subscribe">
                                        <input type="hidden" name="term_meta[auto_subscriptions][justclick][del_rid]" value="">
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="term_meta[auto_subscriptions][justclick][del_rid]"
                                                <?php echo $justclickDelRid == 'all' ? 'checked="checked"' : ''; ?>
                                                value="all"
                                                <?php echo $justclickDelRid ? 'checked="checked"' : ''; ?>>
                                            <?php _e('Отписать от всех остальных групп', 'wpm'); ?>
                                        </label>
                                        <?php /*
                                            <label>
                                                <input
                                                    type="checkbox"
                                                    class="justclick_subscribe_trigger"
                                                    <?php echo $justclickDelRid ? 'checked="checked"' : ''; ?>>
                                                <?php _e('Отписать', 'wpm'); ?>
                                            </label>
                                            <div class="justclick_subscribe_holder" <?php echo !$justclickDelRid ? 'style="display:none;"' : ''; ?>>
                                                <label>
                                                    <input
                                                        type="radio"
                                                        name="term_meta[auto_subscriptions][justclick][del_rid]"
                                                        <?php echo $justclickDelRid == 'all' ? 'checked="checked"' : ''; ?>
                                                        value="all"
                                                        class="justclick_unsubscribe">
                                                    <?php _e('От всех остальных групп', 'wpm'); ?>
                                                </label>
                                                <br>
                                                <br>
                                                <label>
                                                    <input
                                                        type="radio"
                                                        name="term_meta[auto_subscriptions][justclick][del_rid]"
                                                        value=""
                                                        <?php echo is_array($justclickDelRid) && count($justclickDelRid) ? 'checked="checked"' : ''; ?>
                                                        class="justclick_unsubscribe">
                                                    <?php _e('Выбрать из списка', 'wpm'); ?>
                                                </label>
                                                <br>
                                                <br>
                                                <div id="justclick_del_level_groups"  <?php echo !is_array($justclickDelRid) || !count($justclickDelRid)  ? 'style="display:none;"' : ''; ?>>
                                                    <div class="wpm-inline-loader"></div>
                                                </div>
                                            </div>
                                        */ ?>
                                    </div>
                                </div>
                                <div class="wpm-tab-footer">
                                    <button type="submit"
                                            class="button button-primary wpm-save-options"><?php _e('Сохранить', 'wpm'); ?></button>
                                </div>
                            </div>

                            <?php /*
                                <div id="wpm_subscr_tab_2" class="tab">
                                    <div>
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                       name="term_meta[auto_subscriptions][smartresponder][active]"
                                                    <?php echo isAutosubscriptionActive('smartresponder', $term_meta) ? ' checked' : '';?>/>
                                                Включить</label>
                                        </p>
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                       name="term_meta[auto_subscriptions][smartresponder][auto_disable]"
                                                    <?php echo autoDisable('smartresponder', $term_meta) ? ' checked' : '';?>/>
                                                По истечению срока действия пин-кода удалить пользователя из рассылки</label>
                                        </p>
                                        <p class="block-space">
                                            <label>Ваш API-ключ<br>
                                                <input type="text"
                                                       name="term_meta[auto_subscriptions][smartresponder][api_key]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['smartresponder']['api_key']; ?>">
                                            </label>
                                        </p>
                                        <p>

                                            <label>ID рассылки<br>
                                                <input type="text"
                                                       name="term_meta[auto_subscriptions][smartresponder][delivery_id]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['smartresponder']['delivery_id']; ?>">
                                            </label>
                                        </p>
                                        <p>
                                            <label>ID трека<br>
                                                <input type="text"
                                                       name="term_meta[auto_subscriptions][smartresponder][track_id]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['smartresponder']['track_id']; ?>">
                                            </label>
                                        </p>
                                        <p>
                                            <label>ID группы<br>
                                                <input type="text"
                                                       name="term_meta[auto_subscriptions][smartresponder][group_id]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['smartresponder']['group_id']; ?>">
                                            </label>
                                        </p>
                                    </div>
                                    <div class="wpm-tab-footer">
                                        <button type="submit"
                                                class="button button-primary wpm-save-options"><?php _e('Сохранить', 'wpm'); ?></button>
                                    </div>
                                </div>

                                <div id="wpm_subscr_tab_3" class="tab">
                                    <div>
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                       name="term_meta[auto_subscriptions][getresponse][active]"
                                                    <?php echo isAutosubscriptionActive('getresponse', $term_meta) ? ' checked' : '';?>/>
                                                Включить</label>
                                        </p>
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                       name="term_meta[auto_subscriptions][getresponse][auto_disable]"
                                                    <?php echo autoDisable('getresponse', $term_meta) ? ' checked' : '';?>/>
                                                По истечению срока действия пин-кода удалить пользователя из рассылки</label>
                                        </p>
                                        <p class="block-space">
                                            <label>API ключ: &nbsp;&nbsp;<span class="help"><br><a target="_blank" href="https://app.getresponse.com/account.html#api">https://app.getresponse.com/account.html#api</a></span><br>
                                                <input type="text" style="margin-top: 8px"
                                                       name="term_meta[auto_subscriptions][getresponse][api_key]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['getresponse']['api_key']; ?>">
                                            </label>
                                        </p>

                                        <p>
                                            <label>Токен кампании:<br><span class="help"><a target="_blank" href="https://app.getresponse.com/campaign_list.html">https://app.getresponse.com/campaign_list.html</a></span><br>
                                                <input type="text" style="margin-top: 8px"
                                                       name="term_meta[auto_subscriptions][getresponse][campaign_token]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['getresponse']['campaign_token']; ?>">
                                            </label>
                                        </p>
                                    </div>
                                    <div class="wpm-tab-footer">
                                        <button type="submit"
                                                class="button button-primary wpm-save-options"><?php _e('Сохранить', 'wpm'); ?></button>
                                    </div>
                                </div>

                                <div id="wpm_subscr_tab_4" class="tab">
                                    <div>
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                       name="term_meta[auto_subscriptions][unisender][active]"
                                                    <?php echo isAutosubscriptionActive('unisender', $term_meta) ? ' checked' : '';?>/>
                                                Включить</label>
                                        </p>
                                        <p>
                                            <label>
                                                <input type="checkbox"
                                                       name="term_meta[auto_subscriptions][unisender][auto_disable]"
                                                    <?php echo autoDisable('unisender', $term_meta) ? ' checked' : '';?>/>
                                                По истечению срока действия пин-кода удалить пользователя из рассылки</label>
                                        </p>
                                        <p class="block-space">
                                            <label>API ключ:<br>
                                                <input type="text"
                                                       name="term_meta[auto_subscriptions][unisender][api_key]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['unisender']['api_key']; ?>">
                                            </label>
                                        </p>
                                        <p>
                                            <label>Списки<br>
                                                <input type="text"
                                                       name="term_meta[auto_subscriptions][unisender][lists]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['unisender']['lists']; ?>">
                                            </label>
                                        </p>
                                        <p>
                                            <label>Метки<br>
                                                <input type="text"
                                                       name="term_meta[auto_subscriptions][unisender][tags]"
                                                       value="<?php echo $term_meta['auto_subscriptions']['unisender']['tags']; ?>">
                                            </label>
                                        </p>

                                    </div>
                                    <div class="wpm-tab-footer">
                                        <input type="submit" name="submit" class="button button-primary" value="<?php _e('Сохранить', 'wpm'); ?>">
                                    </div>
                                </div>
                            */ ?>
                        </div>
                    </div>
                </div>

            </td>
        </tr>
    <?php endif; ?>


    <tr class="form-field" style="vertical-align: top">
        <th scope="row"><label for="wpm_keys_new"><?php _e('Коды доступа', 'wpm'); ?></label></th>
        <td>
            <div class="wpm-tabs-wrap postbox wpm-ui-wrap">
                <div class="wpm-inner-wrap">
                    <div class="wpm-tabs wpm-inner-tabs">
                        <ul class="wpm-inner-tabs-nav">
                            <li><a href="#tab-keys-1"><?php _e('Сгенерировать ключи', 'wpm'); ?></a></li>
                            <li><a href="#tab-keys-2"><?php _e('Добавить свои', 'wpm'); ?></a></li>
                        </ul>
                        <div id="tab-keys-1" class="tab">
                            <div class="wpm-tab-content">
                                <div class="wpm-row">
                                    <p>
                                        <?php _e('количество', 'wpm'); ?>
                                        <input type="number" size="5" min="1" id="count" value="500" maxlength="4"
                                               style="width: 100px"> <?php _e('(шт.)', 'wpm'); ?>, &nbsp;&nbsp;&nbsp;
                                        <?php _e('время действия', 'wpm'); ?>
                                        <input type="number" size="2" min="1" max="99" id="duration" value="12" maxlength="2"
                                               style="width: 100px">
                                        <select name="units" id="units">
                                            <option value="months" selected><?php _e('месяцев', 'wpm'); ?></option>
                                            <option value="days"><?php _e('дней', 'wpm'); ?></option>
                                        </select>
                                    </p>
                                </div>
                                <div class="wpm-tab-footer">
                                    <button type="button" class="button button-primary add-keys"
                                            data-keys="new"><?php _e('Сгенерировать ключи доступа', 'wpm'); ?></button>

                                    <textarea id="wpm_keys" rows="20" class="large-text" readonly></textarea>
                                </div>
                            </div>
                        </div>
                        <div id="tab-keys-2" class="tab">
                            <div class="wpm-tab-content">
                                <div class="wpm-row">
                                    <p>
                                        <?php _e('время действия', 'wpm'); ?>
                                        <input type="number" size="2" min="1" max="99" id="duration-manual" value="12" maxlength="2"
                                               style="width: 100px">
                                        <select name="units" id="units-manual">
                                            <option value="months" selected><?php _e('месяцев', 'wpm'); ?></option>
                                            <option value="days"><?php _e('дней', 'wpm'); ?></option>
                                        </select>
                                    </p>
                                    <div class="add-manual-keys-message">

                                    </div>
                                    <p class="description">
                                        <?php _e('Вставьте список ключей (каждый ключ в отдельной строке).', 'wpm'); ?>
                                    </p><br>
                                        <label>
                                            <textarea id="manual-keys" rows="10"></textarea>

                                        </label>

                                    <p class="description">
                                       <?php _e('Разрешено использовать только: A-Z, a-z, А-Я, а-я, 0-9, ! @ # $ % ^ & * ( ) - _ [ ] { } < > ~ + = , . ; : / ? |', 'wpm'); ?>
                                    </p>

                                </div>
                                <div class="wpm-tab-footer">
                                        <button type="button" class="button button-primary add-manual-keys"
                                                data-keys="new"><?php _e('Добавить свои ключи доступа', 'wpm'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>




            </div>
            <div>
                <table class="wpm-table">
                    <tr>
                        <th><?php _e('Период', 'wpm'); ?></th>
                        <th><?php _e('Всего', 'wpm'); ?></th>
                        <th><?php _e('Использовано', 'wpm'); ?></th>
                        <th><?php _e('Осталось', 'wpm'); ?></th>
                    </tr>

                    <tbody id="keys-list">
                        <?php echo wpm_get_keys_html_list($term_keys, $term_id); ?>
                    </tbody>

                </table>
                <p>
                    <button type="button"
                            class="button button-primary remove-keys" duration="all"><?php _e('Удалить все ключи', 'wpm'); ?></button>
                </p>

            </div>
            <div style="display: none">
                <div id="wpm_popup_box">
                    <div class="wpm-top-popup-nav">
                        <button type="button" class="button button-primary wpm-move-keys"><?php _e('Переместить ключи','wpm'); ?></button>

                        <span class="message" style="float: left"></span>
                        <button type="button" class="button button-primary wpm-copy-keys" data-clipboard-target="user-level-keys"><?php _e('Копировать ключи','wpm'); ?></button>
                    </div>
                    <div id="wpm-move-keys-tools">
                        <h4><?php _e('Перемещение ключей', 'wpm'); ?></h4>
                        <input type="hidden" name="wpm_move_keys_term_id" value="<?php echo $term_id; ?>">
                        <input type="hidden" name="wpm_move_keys_duration_from">
                        <input type="hidden" name="wpm_move_keys_units_from">
                        <label>
                            <?php _e('Уровень доступа', 'wpm'); ?>
                            <select name="wpm_move_keys">
                                <?php foreach (get_terms('wpm-levels', array()) as $level) : ?>
                                    <option value="<?php echo $level->term_id; ?>"><?php echo $level->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <p>
                            <?php _e('Время действия', 'wpm'); ?>
                            <input type="number" size="2" min="1" max="99" name="wpm_move_keys_duration" value="12" maxlength="2"
                                   style="width: 100px">
                            <select name="wpm_move_keys_units">
                                <option value="months" selected><?php _e('месяцев', 'wpm'); ?></option>
                                <option value="days"><?php _e('дней', 'wpm'); ?></option>
                            </select>
                        </p>
                        <button type="button" class="button button-primary wpm-copy-keys-trigger" style="float: right;">
                            <?php _e('Переместить'); ?>
                        </button>
                        <button type="button" class="button button-danger button-cancel wpm-copy-keys-cancel" style="float: right; margin-right: 3px">
                            <?php _e('Отменить'); ?>
                        </button>
                    </div>
                    <pre id="user-level-keys" class="popup-content-wrap">

                    </pre>
                </div>
            </div>

        </td>
    </tr>

<?php
    include_once('js/wpm-admin-js.php');

}

function wpm_save_levels_fields( $term_id ) {
//
    if ( isset( $_POST['term_meta'] ) ) {

        $term_meta = get_option( "taxonomy_term_$term_id" );
        $cat_keys  = array_keys( $_POST['term_meta'] );
        foreach ($cat_keys as $key){
            if ( isset( $_POST['term_meta'][$key] ) ){
                if($key=='auto_subscriptions') {
                    $term_meta[$key] = wpm_array_clear($_POST['term_meta'][$key]);
                } else {
                    $term_meta[$key] = stripslashes(wp_filter_post_kses(addslashes($_POST['term_meta'][$key])));
                }
            }
        }
        if(isset($_POST['term_meta']['hide_for_no_access']) && $_POST['term_meta']['hide_for_no_access'] == 'hide'){
            $term_meta['hide_for_no_access'] = 'hide';
        }else{
            $term_meta['hide_for_no_access'] = false;
        }
        //save the option array
        update_option( "taxonomy_term_$term_id", $term_meta );
    }
}
add_action( 'edited_wpm-levels', 'wpm_save_levels_fields', 10, 2 );



/**
 *
 */

function wpm_add_manual_user_level_keys()
{
    $result = array(
        'updated' => true,
        'error' => false,
        'message' => '',
        'html' => ''
    );
    $term_id = $_POST['term_id'];
    $duration = $_POST['duration'];
    $units = $_POST['units'];
    $input_keys = isset($_POST['keys']) ? $_POST['keys'] : "";
    $input_keys = str_replace("\r", "", $input_keys);
    $input_keys = str_replace(array(" "), "\n", $input_keys);

    $new_keys = array();

    $input_keys = explode("\n", $input_keys);
    $input_keys = array_filter($input_keys);

    $keys_count = count($input_keys);

    for ($i = 0; $i < count($input_keys); $i++) {
        $new_keys[$i]['key'] = trim($input_keys[$i]);
        $new_keys[$i]['status'] = 'new';
        $new_keys[$i]['duration'] = $duration;
        $new_keys[$i]['units'] = $units;
    }

    if (empty($new_keys)) {
        $result['message'] = _('Ошибка! Неверно введенные ключи.');
        $result['error'] = true;
    } else {
        $term_keys = wpm_get_term_keys($term_id);

        if (empty($term_keys)) {
            $term_keys = array();
        }

        $term_keys = array_merge($term_keys, $new_keys);
        $result['updated'] = wpm_set_term_keys($term_id, $term_keys);

        $result['message'] = _("Ключи добавлены ($keys_count шт).");
        $result['html'] = wpm_get_keys_html_list($term_keys, $term_id);
    }

    echo json_encode($result);
    die();
}

add_action('wp_ajax_wpm_add_manual_user_level_keys_action', 'wpm_add_manual_user_level_keys'); //

/**
 *
 */

function wpm_add_user_level_keys()
{
    $result = array(
        'updated' => 'true',
        'keys'    => '',
        'message' => '',
        'html'    => ''
    );
    $term_id = $_POST['term_id'];
    $count = $_POST['count'];
    $duration = $_POST['duration'];
    $units = $_POST['units'];

    $term_keys = wpm_get_term_keys($term_id);

    $new_keys = wpm_generate_keys(array('count' => $count, 'duration' => $duration, 'date_start' => '', 'date_end' => '', 'units' => $units));
    if(empty($term_keys)) $term_keys = array();
    $term_keys = array_merge($term_keys, $new_keys);
    $result['updated'] = wpm_set_term_keys($term_id, $term_keys);

    foreach ($new_keys as $key => $item) {
        if (!empty($result['keys'])) {
            $result['keys'] .= "\n" . $item['key'];
        } else {
            $result['keys'] .= $item['key'];
        }
    }

    $result['html'] = wpm_get_keys_html_list($term_keys, $term_id);

    echo json_encode($result);
    die();
}

add_action('wp_ajax_wpm_add_user_level_keys_action', 'wpm_add_user_level_keys'); //

/**
 *
 */

function wpm_is_units_equal ($key, $cur_units)
{
    $units = array_key_exists('units', $key) ? $key['units'] : 'months';

    return $cur_units==$units ? true : false;
}

function wpm_remove_user_level_keys()
{
    $result = array(
        'message' => '',
        'error'   => ''
    );

    $term_id   = intval($_POST['term_id']);
    $duration  = $_POST['duration'];
    $units  = $_POST['units'];

    if($duration == 'all') {
        $where = array(
            'term_id' => $term_id
        );
    } else {
        $where = array(
            'term_id' => $term_id,
            'duration' => $duration,
            'units' => $units
        );
    }

    $update = MBLTermKeysQuery::update(array('key_type' => 'wpm_keys_basket'), $where);

    $result['error'] = (bool)($update === false);

    echo json_encode($result);
    die();
}

add_action('wp_ajax_wpm_remove_user_level_keys_action', 'wpm_remove_user_level_keys'); //

/**
 *
 */

function wpm_get_keys()
{
    $term_id = intval($_POST['term_id']);
    $duration = $_POST['duration'];
    $units = $_POST['units'];

    $where = array(
        'term_id' => $term_id,
        'status' => 'new',
        'sent' => 0,
        'is_banned' => 0,
        'duration' => $duration,
        'units' => $units,
        'key_type' => 'wpm_term_keys',
    );

    $keys = '';

    $termKeys = MBLTermKeysQuery::find($where, array('key'));
    if(!empty($termKeys)){
        $keys = implode("\n", wpm_array_pluck($termKeys, 'key')) . "\n";        
    }
    
    echo $keys;
    die();
}

add_action('wp_ajax_wpm_get_keys_action', 'wpm_get_keys'); //


function wpm_get_used_keys()
{
    $term_id = intval($_POST['term_id']);
    $duration = $_POST['duration'];
    $units = $_POST['units'];

    $where = array(
        'term_id' => $term_id,
        'status' => 'used',
        'duration' => $duration,
        'units' => $units,
        'key_type' => 'wpm_term_keys',
        'is_banned' => 'all'
    );

    $keys = '';

    $termKeys = MBLTermKeysQuery::find($where, array('key', 'user_id'));

    $sentWhere = array(
        'term_id' => $term_id,
        'status' => 'new',
        'duration' => $duration,
        'units' => $units,
        'key_type' => 'wpm_term_keys',
        'is_banned' => 'all',
        'sent' => 1
    );

    $sentTermKeys = MBLTermKeysQuery::find($sentWhere, array('key', 'user_id'));

    $termKeys = array_merge($termKeys, $sentTermKeys);

    foreach ($termKeys as $key) {
        $keys .= (wpm_array_get($key, 'user_id')
            ? sprintf("<a href='%s' target='_blank'>%s</a>\n", admin_url('/user-edit.php?user_id=' . wpm_array_get($key, 'user_id')), wpm_array_get($key, 'key'))
            :  (wpm_array_get($key, 'key') . "\n"));
    }

    echo $keys;
    die();
}

add_action('wp_ajax_wpm_get_used_keys_action', 'wpm_get_used_keys');


/**
 * @param $term_id
 */


function wpm_create_levels_taxonomy_term($term_id)
{
    if (!$term_id) {
        return;
    }

    $term_keys = wpm_generate_keys(array('count' => 500, 'duration' => 12, 'units' => 'months'));

    wpm_set_term_keys($term_id, $term_keys, 'wpm_term_keys', true);
}

add_action('create_wpm-levels', 'wpm_create_levels_taxonomy_term', 10, 2);


/**
 * @param $term_id
 */

function wpm_delete_levels_taxonomy_term($term_id)
{
    if (!$term_id) {
        return;
    }

    delete_option("wpm_term_keys_$term_id");
}

add_action('delete_wpm-levels', 'wpm_delete_levels_taxonomy_term', 10, 2);

/**
 *
 */

function wpm_generate_keys($args)
{
    $count = intval($args['count']);
    $duration = $args['duration'];
    $units = $args['units'];
    $keys_array = array();
    for ($i = 0; $i < $count; $i++) {
        $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . time();
        $keys_array[$i]['key'] = str_shuffle($str);
        $keys_array[$i]['status'] = 'new';
        $keys_array[$i]['duration'] = $duration;
        $keys_array[$i]['units'] = $units;
    }
    return $keys_array;
}

function wpm_fix_keys($term_id){

    $term_keys = wpm_get_term_keys($term_id);
    if(is_array($term_keys)){
        foreach($term_keys as $key_id => $key){
            $term_keys[$key_id]['key'] = trim($term_keys[$key_id]['key']);
        }
    }
    wpm_set_term_keys($term_id, $term_keys);
}

function wpm_migrate_keys(){

    $term_meta = get_option("wpm_user_level_keys");
    if(!empty($term_meta)){
        foreach($term_meta as $term_id => $term_array){
            wpm_set_term_keys($term_id, $term_array);
        }
    }

}


/**
 *  Migrate keys to separate table
 */

function migrate_keys_0_3_0()
{

    global $wpdb;
    $codes_table = $wpdb->prefix . "memberlux_codes";

    $sql_response_table = "CREATE TABLE IF NOT EXISTS $codes_table (
          id bigint(20) NOT NULL AUTO_INCREMENT,
          created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          code longtext NOT NULL,
          user_id bigint(11) NOT NULL,
          status varchar(20) DEFAULT 'new' NOT NULL,
          duration bigint(11) NOT NULL,
          date_start datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          date_end datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
          UNIQUE KEY id (id)
            )
            DEFAULT CHARACTER SET utf8
            DEFAULT COLLATE utf8_general_ci;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_response_table);
    add_option("memberlux_db_version", '0.3.0');

    
    $terms_table = $wpdb->prefix . "term_taxonomy";
    $options_table = $wpdb->prefix . "options";

    $terms = $wpdb->get_results( "SELECT term_taxonomy_id
                FROM $terms_table
                WHERE taxonomy='wpm-levels'", OBJECT );

    $i = 0;
    if(!empty($terms)){
        foreach($terms as $term){            
            $option_name = 'wpm_term_keys_'.$term->term_taxonomy_id;
            $options = $wpdb->get_results( "SELECT option_value
                FROM $options_table
                WHERE option_name='$option_name'", OBJECT);            
        }
    }

}
