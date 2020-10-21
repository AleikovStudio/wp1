<div class="wpm-tab-content">
    <div class="wpm-inner-tabs" tab-id="h-tabs-2">
        <ul class="wpm-inner-tabs-nav">
            <li><a href="#mbl_inner_tab_2_1">Стартовая</a></li>
            <li><a href="#mbl_inner_tab_2_2">Расписание</a></li>
            <li><a href="#mbl_inner_tab_2_3">Вход</a></li>
            <li><a href="#mbl_inner_tab_2_4">Регистрация</a></li>
            <li><a href="#mbl_inner_tab_2_5">Пользовательское соглашение</a></li>
            <li><a href="#mbl_inner_tab_2_6">Постраничная навигация</a></li>
        </ul>
        <div id="mbl_inner_tab_2_1" class="wpm-tab-content">
            Стартовая страница:
            <?php
            $start_page = '';
            $args = array(
                'post_type' => 'wpm-page',
                'nopaging' => true
            );
            $wpm_pages = new WP_Query($args);
            $wpm_pages_select = '';
            if ($wpm_pages->have_posts()): while ($wpm_pages->have_posts()): $wpm_pages->the_post();

                $selected = '';
                if ($main_options['home_id'] == get_the_ID()) {
                    $selected = 'selected';
                    $start_page = get_permalink();
                }
                $wpm_pages_select .= '<option value="' . get_the_ID() . '" ' . $selected . '>' . get_the_title() . '</option>';
            endwhile;
                $wpm_pages_select = '<select name="main_options[home_id]">' . $wpm_pages_select . '</select>';
            endif;
            echo $wpm_pages_select;
            ?>
            <br>
            <label>
                <?php
                if ($main_options['make_home_start']) {
                    ?>
                    <input type="checkbox" name="main_options[make_home_start]" checked>
                <?php } else { ?>
                    <input type="checkbox" name="main_options[make_home_start]">
                <?php } ?>
                Сделать главной страницей сайта
            </label>
            <br>
            <br>

            Описание:
            <div class="wpm-row" style="padding-left: 25px; margin-bottom: 0; margin-top: 5px">
                <label>
                    <input type="hidden" name="main_options[homepage][show_description]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[homepage][show_description]"
                        value="1"
                        <?php echo wpm_get_option('homepage.show_description', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать
                </label>
            </div>
            <div class="wpm-row" style="padding-left: 50px; margin-bottom: 0; margin-top: 5px">
                <label>
                    <input type="hidden" name="main_options[homepage][description_expand]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[homepage][description_expand]"
                        value="1"
                        <?php echo wpm_get_option('homepage.description_expand', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Открывать при входе
                </label>
            </div>
            <div class="wpm-row" style="padding-left: 50px; margin-bottom: 0; margin-top: 5px">
                <label>
                    <input type="hidden" name="main_options[homepage][show_description_always]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[homepage][show_description_always]"
                        value="1"
                        <?php echo wpm_get_option('homepage.show_description_always', 0) ? 'checked="checked"' : ''; ?>
                    >
                    Зафиксировать открытым
                </label>
            </div>
            <br>

            <div class="wpm-row">
                <label>
                    Стартовая страница
                </label><br>

                <div class="code">
                    <?php echo utf8_encode($start_page); ?>
                </div>
                <label>
                    Страница входа пользователя
                </label><br>

                <div class="code">
                    <?php echo utf8_encode($start_page); ?>#login
                </div>
                <label>
                    Страница регистрации пользователя
                </label><br>

                <div class="code">
                    <?php echo utf8_encode($start_page); ?>#registration
                </div>

            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_2_2" class="wpm-tab-content">
            <div class="wpm-row">
                <?php
                $schedule_page = '';
                $args = array(
                    'post_type' => 'wpm-page',
                    'nopaging' => true
                );
                $wpm_pages = new WP_Query($args);
                $wpm_pages_select = '';
                if ($main_options['schedule_id'] == 'no') {
                    $wpm_pages_select .= '<option value="no" selected>' . __("-- Не задано --", "wpm") . '</option>';
                } else {
                    $wpm_pages_select .= '<option value="no">' . __("-- Не задано --", "wpm") . '</option>';
                }
                if ($wpm_pages->have_posts()): while ($wpm_pages->have_posts()): $wpm_pages->the_post();
                    $selected = '';
                    if ($main_options['schedule_id'] == get_the_ID()) {
                        $selected = 'selected';
                        $schedule_page = get_the_permalink();
                    }
                    $wpm_pages_select .= '<option value="' . get_the_ID() . '" ' . $selected . '>' . get_the_title() . '</option>';
                endwhile;
                    $wpm_pages_select = '<select name="main_options[schedule_id]">' . $wpm_pages_select . '</select>';
                endif;
                echo $wpm_pages_select;
                ?>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[hide_schedule]" value="off"/>
                    <input type="checkbox"
                           name="main_options[hide_schedule]"
                        <?php echo (array_key_exists('hide_schedule', $main_options) && $main_options['hide_schedule'] == 'on') ? 'checked' : ''; ?>>
                    Спрятать расписание
                </label>
            </div>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_2_3" class="wpm-tab-content">
            <div class="wpm-control-row">
                <p>Контент для отображения на странице входа в систему.</p>
            </div>
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="main_options[login_content][visible]" value="off">
                    <input type="checkbox"
                              name="main_options[login_content][visible]" <?php if ($main_options['login_content']['visible'] == 'on') echo 'checked'; ?>>Отображать
                </label>
                &nbsp;&nbsp;&nbsp;
                <label>
                    <input type="hidden" name="main_options[login_content_opened]" value="off">
                    <input type="checkbox"
                              name="main_options[login_content_opened]" <?php if (wpm_option_is('login_content_opened', 'on')) echo 'checked'; ?>>Зафиксировать открытым
                </label>
                &nbsp;&nbsp;&nbsp;
                <label><input type="radio"
                              name="main_options[login_content][position]"
                              value="top" <?php if ($main_options['login_content']['position'] == 'top') echo 'checked'; ?>>Вверху
                </label>
                &nbsp;
                <label><input type="radio"
                              name="main_options[login_content][position]"
                              value="bottom" <?php if ($main_options['login_content']['position'] == 'bottom') echo 'checked'; ?>>Внизу
                </label>
            </div>
            <div class="wpm-control-row">
                <?php wp_editor($main_options['login_content']['content'], 'wpm_login_content', array('textarea_name' => 'main_options[login_content][content]', 'editor_height' => 300)); ?>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_2_4" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[registration_form][surname]"
                        <?php echo wpm_reg_field_is_enabled($main_options, 'surname') ? ' checked' : ''; ?> />
                    <?php _e('Фамилия', 'mbl'); ?>
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[registration_form][name]"
                        <?php echo wpm_reg_field_is_enabled($main_options, 'name') ? ' checked' : ''; ?> />
                    <?php _e('Имя', 'mbl'); ?>
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[registration_form][patronymic]"
                        <?php echo wpm_reg_field_is_enabled($main_options, 'patronymic') ? ' checked' : ''; ?> />
                    <?php _e('Отчество', 'mbl'); ?>
                </label>
            </div>
            <div class="wpm-row wpm-row-disabled"
                 title="Это поле нельзя убрать из регистрационной формы">
                <label>
                    <input type="checkbox" disabled checked/> <?php _e('Email', 'mbl'); ?>
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[registration_form][phone]"
                        <?php echo wpm_reg_field_is_enabled($main_options, 'phone') ? ' checked' : ''; ?> />
                    <?php _e('Телефон', 'mbl'); ?>
                </label>
            </div>
            <div class="wpm-row wpm-row-disabled"
                 title="Это поле нельзя убрать из регистрационной формы">
                <label>
                    <input type="checkbox" disabled checked/> <?php _e('Желаемый логин', 'mbl'); ?>
                </label>
            </div>
            <div class="wpm-row wpm-row-disabled"
                 title="Это поле нельзя убрать из регистрационной формы">
                <label>
                    <input type="checkbox" disabled checked/> <?php _e('Желаемый пароль', 'mbl'); ?>
                </label>
            </div>
            <div class="wpm-row wpm-row-disabled"
                 title="Это поле нельзя убрать из регистрационной формы">
                <label>
                    <input type="checkbox" disabled checked/> <?php _e('Код активации', 'mbl'); ?>
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[registration_form][custom1]"
                        <?php echo wpm_reg_field_is_enabled($main_options, 'custom1') ? ' checked' : ''; ?> />
                </label>
                <input type="text" name="main_options[registration_form][custom1_label]"
                       value="<?php echo $main_options['registration_form']['custom1_label'] ?>">
            </div>
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[registration_form][custom2]"
                        <?php echo wpm_reg_field_is_enabled($main_options, 'custom2') ? ' checked' : ''; ?> />
                </label>
                <input type="text" name="main_options[registration_form][custom2_label]"
                       value="<?php echo $main_options['registration_form']['custom2_label'] ?>">
            </div>
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[registration_form][custom3]"
                        <?php echo wpm_reg_field_is_enabled($main_options, 'custom3') ? ' checked' : ''; ?> />
                </label>
                <input type="text" name="main_options[registration_form][custom3_label]"
                       value="<?php echo $main_options['registration_form']['custom3_label'] ?>">
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_2_5" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[user_agreement][enabled_login]"
                        <?php echo wpm_option_is('user_agreement.enabled_login', 'on') ? ' checked' : ''; ?> >
                    Вход в систему
                    <input type="text"
                           name="main_options[user_agreement][login_link_title]"
                           class="regular-text"
                           value="<?php echo wpm_get_option('user_agreement.login_link_title', __('Пользовательское соглашение', 'wpm')); ?>"
                    >
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[user_agreement][enabled_registration]"
                        <?php echo wpm_option_is('user_agreement.enabled_registration', 'on') ? ' checked' : ''; ?> >
                    Регистрация
                    <input type="text"
                           name="main_options[user_agreement][registration_link_title]"
                           class="regular-text"
                           value="<?php echo wpm_get_option('user_agreement.registration_link_title', __('пользовательское соглашение', 'wpm')); ?>"
                    >
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="checkbox"
                           name="main_options[user_agreement][enabled_footer]"
                        <?php echo wpm_option_is('user_agreement.enabled_footer', 'on') ? ' checked' : ''; ?> >
                    Подвал системы
                    <input type="text"
                           name="main_options[user_agreement][footer_link_title]"
                           class="regular-text"
                           value="<?php echo wpm_get_option('user_agreement.footer_link_title', __('Пользовательское соглашение', 'wpm')); ?>"
                    >
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    Название
                    <input type="text"
                           name="main_options[user_agreement][title]"
                           class="large-text"
                           value="<?php echo wpm_get_option('user_agreement.title', __('Пользовательское соглашение', 'wpm')); ?>"
                    >
                </label>
            </div>
            <div class="wpm-control-row">
                <?php wp_editor(stripslashes(wpm_get_option('user_agreement.text')), 'wpm_user_agreement_text', array('textarea_name' => 'main_options[user_agreement][text]', 'editor_height' => 300)); ?>
            </div>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_2_6" class="wpm-tab-content">
            <div class="wpm-row">
                <label>Сколько рубрик отображать на одной странице?<br>
                    <input type="number" name="main_options[main][terms_per_page]"
                           value="<?php echo wpm_get_option('main.terms_per_page', 12); ?>"
                           size="3"
                           maxlength="3">
                </label>
            </div>

            <div class="wpm-help-wrap">
                <p>(-1) показать все на одной странице</p>
            </div>

            <div class="wpm-row">
                <label>Сколько материалов отображать на одной странице?<br>
                    <input type="number" name="main_options[main][posts_per_page]"
                           value="<?php echo wpm_get_option('main.posts_per_page', -1); ?>"
                           size="3"
                           maxlength="3">
                </label>
            </div>

            <div class="wpm-help-wrap">
                <p>(-1) показать все на одной странице</p>
            </div>

            <?php wpm_render_partial('options/color-row', 'admin', array('label' => __('Цвет фона', 'mbl'), 'key' => 'pagination.bg_color', 'default' => 'fbfbfb')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => __('Цвет текста', 'mbl'), 'key' => 'pagination.color', 'default' => '7e7e7e')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => __('Цвет рамки', 'mbl'), 'key' => 'pagination.border_color', 'default' => 'c1c1c1')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => __('Цвет фона при наведении', 'mbl'), 'key' => 'pagination.hover_bg_color', 'default' => 'fbfbfb')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => __('Цвет текста при наведении', 'mbl'), 'key' => 'pagination.hover_color', 'default' => '000000')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => __('Цвет рамки при наведении', 'mbl'), 'key' => 'pagination.hover_border_color', 'default' => 'c1c1c1')) ?>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
    </div>
</div>