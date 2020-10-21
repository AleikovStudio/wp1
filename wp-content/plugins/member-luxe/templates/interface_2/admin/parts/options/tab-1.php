<div class="wpm-tab-content">
    <div class="wpm-inner-tabs" tab-id="h-tabs-1">
        <ul class="wpm-inner-tabs-nav">
            <li><a href="#mbl_inner_tab_1_1">Генерация пин-кодов</a></li>
            <li><a href="#mbl_inner_tab_1_2">Блокировка</a></li>
            <li><a href="#mbl_inner_tab_1_4">Комментарии</a></li>
            <li><a href="#mbl_inner_tab_1_5">Поиск</a></li>
            <li><a href="#mbl_inner_tab_1_6">Тексты</a></li>
            <li><a href="#mbl_inner_tab_1_8">Автоподписки</a></li>
            <li><a href="#mbl_inner_tab_1_9">Аудио</a></li>
            <li><a href="#mbl_inner_tab_1_10">Скрипты</a></li>
        </ul>
        <div id="mbl_inner_tab_1_1" class="wpm-tab-content">
                <div class="wpm-row">
                    <label>
                        <input type="hidden" name="main_options[pincode_page][generate]" value="off"/>
                        <input type="checkbox"
                               name="main_options[pincode_page][generate]"
                            <?php echo wpm_option_is('pincode_page.generate', 'on') ? 'checked' : ''; ?>>
                        Включить генерацию
                    </label>
                </div>

                <div class="wpm-row">
                    <label>
                        Название ссылки<br>
                        <input type="text"
                               name="main_options[pincode_page][link_name]"
                               value="<?php echo wpm_get_option('pincode_page.link_name', __('Генерировать', 'wpm')); ?>">
                    </label>
                </div>

                <div class="wpm-row">
                    <label>
                        Выберите уровень доступа
                    </label>
                    <?php $plain_levels = get_terms('wpm-levels', array()); ?>
                    <div>
                        <select id="send_term_key_lvl" name="main_options[pincode_page][lvl]"
                                onchange="changeLinkedList(this, '#send_term_key')">
                            <option value="" disabled hidden >Не выбрано</option>
                            <?php foreach ($plain_levels AS $level) : ?>
                                <option
                                    value="<?php echo $level->term_id; ?>"
                                    <?php echo wpm_is_pin_code_page_lvl($level->term_id) ? 'selected="selected"' : '' ?>
                                ><?php echo $level->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="wpm-row">
                    <label>
                        Выберите код доступа
                    </label>

                    <div>
                        <select id="send_term_key" name="main_options[pincode_page][term_key]">
                            <option value=""></option>
                        </select>
                        <select id="send_term_key_src" name="term_key_src" style="display: none">
                            <option value=""></option>
                            <?php echo wpm_get_term_keys_options_for_pin_code_page($plain_levels); ?>
                        </select>
                    </div>
                </div>
            <script type="text/javascript">
                function changeLinkedList(main, linked) {
                    var $ = jQuery,
                        val = $(main).val(),
                        linkedSrc,
                        options;

                    linked = $(linked);

                    if (linked.length) {
                        linkedSrc = $('#' + linked.attr('id') + '_src');
                        options = linkedSrc.find('option');

                        if (val != '') {
                            linked.prop('disabled', false);
                            if (linked.data('empty') == '1') {
                                linked.html('<option value=""></option>');
                            } else {
                                linked.html('');
                            }
                            options
                                .filter(function () {
                                    return $(this).data('main') == val;
                                })
                                .clone()
                                .appendTo(linked);
                        } else {
                            linked.prop('disabled', true);
                        }
                    }
                }

                jQuery(function () {
                    changeLinkedList('#send_term_key_lvl', '#send_term_key');
                });
            </script>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_1_2" class="wpm-tab-content">
            <div class="wpm-row">
                <label><input type="checkbox"
                              name="main_options[main][opened]"<?php if ($main_options['main']['opened'] == true) echo 'checked'; ?> >Снять
                    блокировку<br>
                </label>
            </div>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_1_4" class="wpm-tab-content">
            <div class="wpm-row">
                <div class="wpm-control-row">
                    <p><b>Тип комментариев:</b></p>
                </div>
                <label>
                    <input type="radio"
                           class="wpm_comments_mode"
                           name="main_options[main][comments_mode]"
                           value="standard" <?php if (!wpm_option_is('main.comments_mode', 'cackle')) echo 'checked'; ?>> Стандартные
                </label><br/><br/>
                <label>
                    <input type="radio"
                           class="wpm_comments_mode"
                           name="main_options[main][comments_mode]"
                           value="cackle" <?php if (wpm_option_is('main.comments_mode', 'cackle')) echo 'checked'; ?>> Cackle
                </label>
            </div>
            <div class="wpm-row wpm-comment-cackle-row" <?php if (!wpm_option_is('main.comments_mode', 'cackle')) echo 'style="display:none;"'; ?>>
                <div class="wpm-control-row">
                    <p><b>ID сайта Cackle:</b></p>
                </div>

                <input type="text"
                           name="main_options[main][cackle_id]"
                           id="cackle_id"
                           value="<?php echo wpm_get_option('main.cackle_id'); ?>"/>

                <br>
                <br>
                <label>
                    <input type="checkbox"
                           name="main_options[main][cackle_auto_update]"
                        <?php echo wpm_option_is('main.cackle_auto_update', 'on') ? ' checked' : ''; ?> >
                    Автообновление комментариев<br/>
                </label>

            </div>
            <div class="wpm-row wpm-comment-images-row" <?php if (wpm_option_is('main.comments_mode', 'cackle')) echo 'style="display:none;"'; ?>>
                <div class="wpm-control-row">
                    <p><b>Загрузка изображений:</b></p>
                </div>

                <?php $attachments_mode = array_key_exists('attachments_mode', $main_options['main']) ? $main_options['main']['attachments_mode'] : 'allowed_to_all'; ?>

                <label>
                    <input type="radio"
                           name="main_options[main][attachments_mode]"
                           value="allowed_to_all" <?php if ($attachments_mode == 'allowed_to_all') echo 'checked'; ?>> Доступна всем
                </label><br/><br/>
                <label>
                    <input type="radio"
                           name="main_options[main][attachments_mode]"
                           value="allowed_to_admin" <?php if ($attachments_mode == 'allowed_to_admin') echo 'checked'; ?>> Доступна только администратору
                </label><br/><br/>
                <label>
                    <input type="radio"
                           name="main_options[main][attachments_mode]"
                           value="disabled" <?php if ($attachments_mode == 'disabled') echo 'checked'; ?>> Не доступна
                </label>

            </div>

            <div class="wpm-row">
                <div class="wpm-control-row">
                    <p><b>Видимость комментариев:</b></p>
                </div>

                <?php $visibility = array_key_exists('visibility', $main_options['main']) ? $main_options['main']['visibility'] : 'to_all'; ?>

                <label>
                    <input type="radio"
                           name="main_options[main][visibility]"
                           value="to_all" <?php if ($visibility == 'to_all') echo 'checked'; ?>> Показывать всем
                </label><br/><br/>
                <label>
                    <input type="radio"
                           name="main_options[main][visibility]"
                           value="to_registered" <?php if ($visibility == 'to_registered') echo 'checked'; ?>> Только зарегистрированным пользователям
                </label>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_1_5" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[main][search_visible]" value="off">
                    <input type="checkbox"
                           name="main_options[main][search_visible]"
                           <?php echo wpm_option_is('main.search_visible', 'on', 'on') ? 'checked' : ''; ?>
                    >
                    Отображать
                </label>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_1_6" class="wpm-tab-content">
            <div class="wpm-control-row">
                <p>Редактирование текстов Memberlux</p>
            </div>
            <ol>
                <?php foreach (MBLTranslator::getDBValues() as $translationRow) : ?>
                    <li class="wpm-control-row">
                        <label>
                            <input type="text"
                                   class="large-text"
                                   name="translations[<?php echo $translationRow->hash; ?>]"
                                   value="<?php echo $translationRow->msgstr; ?>"
                                >
                        </label>
                    </li>
                <?php endforeach; ?>
            </ol>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_1_8" class="wpm-tab-content">
            <div>
                <p>
                    <label>
                        <?php if ($main_options['auto_subscriptions']['justclick']['active'] == 'on') { ?>
                            <input type="checkbox"
                                   name="main_options[auto_subscriptions][justclick][active]"
                                   checked="">
                        <?php } else { ?>
                            <input type="checkbox"
                                   name="main_options[auto_subscriptions][justclick][active]">
                        <?php } ?>
                        Включить</label>
                </p>

                <p>
                    <label>
                        <input type="checkbox"
                               name="main_options[auto_subscriptions][justclick][auto_disable]"
                            <?php echo autoDisable('justclick', $main_options) ? ' checked' : ''; ?>/>
                        По истечению срока действия пин-кода удалить пользователя из рассылки</label>
                </p>

                <div class="letter_options_label">
                    <p>
                        <label>Логин<br>
                            <input type="text"
                                   name="main_options[auto_subscriptions][justclick][user_id]"
                                   value="<?php echo $main_options['auto_subscriptions']['justclick']['user_id']; ?>">
                        </label>
                    </p>

                    <p>
                        <label>Секретный ключ для подписи<br>
                            <input type="text"
                                   name="main_options[auto_subscriptions][justclick][user_rps_key]"
                                   value="<?php echo $main_options['auto_subscriptions']['justclick']['user_rps_key']; ?>">
                        </label>
                    </p>
                </div>

                <?php if (MBLSubscription::direct('justclick', 'credentialsFilled')) : ?>
                    <div>
                        <div id="justclick_groups"><div class="wpm-inline-loader"></div></div>
                    </div>

                    <p>
                        <label>Адрес после подтверждения (не обязательно)<br>
                            <input type="text"
                                   name="main_options[auto_subscriptions][justclick][doneurl2]"
                                   value="<?php echo $main_options['auto_subscriptions']['justclick']['doneurl2']; ?>">
                        </label>
                    </p>
                <?php endif; ?>
            </div>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_1_9" class="wpm-tab-content">
            <div class="wpm-control-row">
                <h3>Аудио-плеер</h3>
            </div>
            <div class="wpm-control-row wpm-audio-settings">
                <label>
                    <input type="radio"
                           name="main_options[audio][player]"
                           data-radio-toggle="wpm_audio"
                           value="mediaelement"
                           <?php echo !wpm_option_is('audio.player', 'wavesurfer') ? 'checked' : ''; ?>
                    >Mediaelement Player
                </label>
                <div data-radio-toggle-holder="wpm_audio"
                     <?php echo wpm_option_is('audio.player', 'wavesurfer') ? 'style="display:none;"' : ''; ?>
                     data-value="mediaelement"
                     class="mediaelement-preview">
                </div>
                <br>
                <br>
                <label>
                    <input type="radio"
                           name="main_options[audio][player]"
                           data-radio-toggle="wpm_audio"
                           value="wavesurfer"
                           <?php echo wpm_option_is('audio.player', 'wavesurfer') ? 'checked' : ''; ?>
                    >Wave Surfer Player
                </label>
                <div data-radio-toggle-holder="wpm_audio"
                     <?php echo !wpm_option_is('audio.player', 'wavesurfer') ? 'style="display:none;"' : ''; ?>
                     data-value="wavesurfer">
                     <div class="wavesurfer-preview"></div>

                    <div class="wpm-help-wrap">
                        <p>Аудио файлы должны быть загружены на ваш сайт (например в Медиафайлы)</p>
                    </div>
                </div>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_1_10" class="wpm-tab-content">
            <div class="wpm-control-row">
                <p>&lt;head&gt; <span class="text_green">ваш код</span> &lt;/head&gt;</p>
                <label>
                    <textarea name="main_options[header_scripts]" class="wpm-wide code-style"
                              placeholder="Ваш код"
                              rows="20"><?php echo stripslashes($main_options['header_scripts']); ?></textarea>
                </label>
            </div>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
    </div>
</div>