<div class="wpm-tab-content">
    <div class="wpm-inner-tabs" tab-id="h-tabs-6">
        <ul class="wpm-inner-tabs-nav">
            <li><a href="#mbl_inner_tab_6_1">Защита контента</a></li>
            <li><a href="#mbl_inner_tab_6_2">Ограничения для пользователей</a></li>
        </ul>
        <div id="mbl_inner_tab_6_1" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[protection][video_url_encoded]" value="off"/>
                    <input type="checkbox"
                           id="video_url_encoded"
                           name="main_options[protection][video_url_encoded]"
                           <?php echo wpm_option_is('protection.video_url_encoded', 'on') ? 'checked="checked"' : '' ?> >
                    Включить шифрование ссылок .mp4
                    <br>
                </label>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[protection][right_button_disabled]" <?php echo wpm_option_is('protection.right_button_disabled', 'on') ? 'checked' : ''; ?> > Отключить правую кнопку мыши
                </label>
            </div>

            <div class="wpm-row">
                <?php $text_protection_is_enabled = wpm_text_protection_is_enabled($main_options); ?>
                <label>
                    <input id="wpm_text_protection_chbx" type="checkbox"
                           name="main_options[protection][text_protected]" <?php echo $text_protection_is_enabled ? 'checked' : ''; ?> > Запретить копирование текста
                </label>
            </div>
            <?php
                $args = array(
                    'post_type' => 'wpm-page',
                    'nopaging' => true
                );
                $wpm_pages = new WP_Query($args);
            ?>
            <?php if ($wpm_pages->have_posts()) : ?>
                <div
                    class="wpm-protection-exceptions" <?php echo $text_protection_is_enabled ? '' : 'style="display:none;"' ?>>
                    Исключения:
                    <?php while ($wpm_pages->have_posts()): ?>
                        <?php $wpm_pages->the_post(); ?>
                        <div class="wpm-row">
                            <label>
                                <?php $checked = $text_protection_is_enabled && !wpm_text_protection_is_enabled($main_options, get_the_ID()); ?>
                                <input type="checkbox"
                                       name="main_options[protection][text_protected_exceptions][]"
                                       value="<?php echo get_the_ID(); ?>" <?php echo $checked ? 'checked="checked"' : '' ?>>
                                <?php echo get_the_title(); ?>
                            </label>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_6_2" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    <input id="wpm_session_protection" type="checkbox"
                           name="main_options[protection][one_session][status]" <?php echo ($main_options['protection']['one_session']['status'] == 'on') ? 'checked' : ''; ?> > Запретить множественную авторизацию.
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    Интервал проверки акаунтов: <input id="wpm_session_protection_interval" type="number"
                           name="main_options[protection][one_session][interval]" value="<?php echo $main_options['protection']['one_session']['interval']; ?>" > секунд
                </label>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
    </div>
</div>