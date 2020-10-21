<div class="wpm-tab-content">
    <div class="wpm-inner-tabs" tab-id="h-tabs-3">
        <ul class="wpm-inner-tabs-nav">
            <li><a href="#mbl_inner_tab_3_1">Интерфейс</a></li>
            <li><a href="#mbl_inner_tab_3_2">Логотип и favicon</a></li>
            <li><a href="#mbl_inner_tab_3_3">Фон</a></li>
            <li><a href="#mbl_inner_tab_3_4">Панель управления</a></li>
            <li><a href="#mbl_inner_tab_3_5">Шапка</a></li>
            <li><a href="#mbl_inner_tab_3_12">Подвал</a></li>
            <li><a href="#mbl_inner_tab_3_6">Хлебные крошки</a></li>
            <li><a href="#mbl_inner_tab_3_9">Рубрика</a></li>
            <li><a href="#mbl_inner_tab_3_7">Заголовок и подзаголовок рубрики</a></li>
            <li><a href="#mbl_inner_tab_3_8">Описание рубрики</a></li>
            <li><a href="#mbl_inner_tab_3_10">Материал</a></li>
            <li><a href="#mbl_inner_tab_3_11">Контент материала</a></li>
            <li><a href="#mbl_inner_tab_3_14">Прогресс курса</a></li>
        </ul>
        <div id="mbl_inner_tab_3_1" class="wpm-tab-content">
            <?php wpm_render_partial('settings-interface', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_2" class="wpm-tab-content">
            <label>Логотип<br>
                <input type="hidden" id="wpm_logo" name="main_options[logo][url]"
                       value="<?php echo $main_options['logo']['url']; ?>"
                       class="wide"></label>

            <div class="wpm-control-row">
                <p>
                    <button type="button" class="wpm-media-upload-button button"
                            data-id="logo">Загрузить</button>
                    &nbsp;&nbsp; <a id="delete-wpm-logo"
                                    class="wpm-delete-media-button button submit-delete"
                                    data-id="logo">Удалить</a>
                </p>
            </div>
            <div class="wpm-logo-preview-wrap">
                <div class="wpm-logo-preview-box">
                    <img src="<?php echo $main_options['logo']['url']; ?>" title="" alt=""
                         id="wpm-logo-preview">
                </div>
            </div>

            <label>Favicon<br>
                <input type="hidden" id="wpm_favicon" name="main_options[favicon][url]"
                       value="<?php echo $main_options['favicon']['url']; ?>" class="wide"></label>

            <div class="wpm-control-row">
                <p>
                    <button type="button" class="wpm-media-upload-button button"
                            data-id="favicon">Загрузить</button>
                    &nbsp;&nbsp; <a id="delete-wpm-favicon"
                                    class="wpm-delete-media-button button submit-delete"
                                    data-id="favicon">Удалить</a>
                </p>
            </div>
            <div class="wpm-favicon-preview-wrap">
                <div class="wpm-favicon-preview-box">
                    <img src="<?php echo $main_options['favicon']['url']; ?>" title="" alt=""
                         id="wpm-favicon-preview">
                </div>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_3" class="wpm-tab-content">
            <div class="wpm-control-row">
                <label>Цвет фона<br>
                    <input type="text" name="design_options[main][background_color]"
                           class="color"
                           value="<?php echo $design_options['main']['background_color']; ?>">
                </label>
            </div>
            <div class="wpm-control-row">
                <label>Фоновое изображение<br>
                    <input type="text" id="wpm_background"
                           name="design_options[main][background_image][url]"
                           value="<?php echo $design_options['main']['background_image']['url']; ?>"
                           class="wide"></label>

                <div class="wpm-control-row upload-image-row">
                    <p>
                        <button type="button" class="wpm-media-upload-button button"
                                data-id="background">Загрузить</button>
                        &nbsp;&nbsp; <a id="delete-wpm-background"
                                        class="wpm-delete-media-button button submit-delete"
                                        data-id="background">Удалить</a>
                    </p>
                </div>
                <div class="wpm-background-preview-wrap">
                    <div class="wpm-background-preview-box preview-box">
                        <img
                            src="<?php echo $design_options['main']['background_image']['url']; ?>"
                            title="" alt=""
                            id="wpm-background-preview">
                    </div>
                </div>
            </div>
            <div class="wpm-control-row">
                <label>Выравнивание изображения</label><br>
                <?php
                $background_position = array(
                    'left top' => 'сверху слева',
                    'right top' => 'сверху справа',
                    'center top' => 'сверху по центру',
                    'left bottom' => 'снизу слева',
                    'right bottom' => 'снизу справа',
                    'center bottom' => 'снизу по центру'
                );
                $html = '';
                foreach ($background_position as $key => $value) {
                    if ($design_options['main']['background_image']['position'] == $key)
                        $html .= "<option value='$key' selected>$value</option>";
                    else
                        $html .= "<option value='$key'>$value</option>";
                }
                $html = '<select name="design_options[main][background_image][position]">' . $html . '</select>';
                echo $html;
                ?>
            </div>
            <div class="wpm-control-row">
                <label>Повторение изображения</label><br>
                <?php
                $background_repeat = array(
                    'no-repeat' => 'не повторять',
                    'repeat' => 'повторять',
                    'repeat-x' => 'повторять по горизонтали',
                    'repeat-y' => 'повторять по вертикали'
                );
                $html = '';
                foreach ($background_repeat as $key => $value) {
                    if ($design_options['main']['background_image']['repeat'] == $key)
                        $html .= "<option value='$key' selected>$value</option>";
                    else
                        $html .= "<option value='$key'>$value</option>";
                }
                $html = '<select name="design_options[main][background_image][repeat]">' . $html . '</select>';
                echo $html;
                ?>
            </div>
            <br/>

            <div class="wpm-control-row">
                <label><input type="checkbox"
                              name="design_options[main][background-attachment-fixed]" <?php echo $design_options['main']['background-attachment-fixed'] == 'on' ? 'checked' : ''; ?> >
                    &nbsp;Зафиксировать фон
                </label><br>
            </div>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_4" class="wpm-tab-content">
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона', 'key' => 'toolbar.background_color', 'default' => 'f9f9f9')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки', 'key' => 'toolbar.border_bottom_color', 'default' => 'e7e7e7')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконок', 'key' => 'toolbar.icon_color', 'default' => '868686')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконок при наведении', 'key' => 'toolbar.icon_hover_color', 'default' => '2e2e2e')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста', 'key' => 'toolbar.text_color', 'default' => '868686')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста при наведении', 'key' => 'toolbar.hover_color', 'default' => '2e2e2e')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона поиска', 'key' => 'toolbar.search_bg_color', 'default' => 'f9f9f9')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки поиска', 'key' => 'toolbar.search_border_color', 'default' => 'ededed')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона выпадающего меню', 'key' => 'toolbar.menu_bg_color', 'default' => 'efefef')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста-подсказки в полях форм', 'key' => 'toolbar.placeholder_color', 'default' => 'a9a9a9')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет заполняемого текста в полях форм', 'key' => 'toolbar.input_color', 'default' => '555555')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет кнопок', 'key' => 'toolbar.button_color', 'default' => wpm_get_design_option('buttons.sign_in.background_color'))) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет кнопок при наведении', 'key' => 'toolbar.button_hover_color', 'default' => wpm_get_design_option('buttons.sign_in.background_color_hover'))) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет кнопок при клике', 'key' => 'toolbar.button_active_color', 'default' => wpm_get_design_option('buttons.sign_in.background_color_hover'))) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста кнопок', 'key' => 'toolbar.button_text_color', 'default' => wpm_get_design_option('buttons.sign_in.text_color'))) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста кнопок при наведении', 'key' => 'toolbar.button_text_hover_color', 'default' => wpm_get_design_option('buttons.sign_in.text_color_hover'))) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста кнопок при клике', 'key' => 'toolbar.button_text_active_color', 'default' => wpm_get_design_option('buttons.sign_in.text_color_hover'))) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста «Закрыть»', 'key' => 'toolbar.close_text_color', 'default' => '868686')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконки «Закрыть»', 'key' => 'toolbar.close_icon_color', 'default' => 'ffffff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконки «Закрыть» при наведении', 'key' => 'toolbar.close_icon_hover_color', 'default' => 'ffffff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконки «Закрыть» при клике', 'key' => 'toolbar.close_icon_active_color', 'default' => 'ffffff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона иконки «Закрыть»', 'key' => 'toolbar.close_icon_bg_color', 'default' => 'c1c1c1')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона иконки «Закрыть» при наведении', 'key' => 'toolbar.close_icon_bg_hover_color', 'default' => 'd4d4d4')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона иконки «Закрыть» при клике', 'key' => 'toolbar.close_icon_bg_active_color', 'default' => 'b4b4b4')) ?>
            <br>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_5" class="wpm-tab-content">
            <div class="wpm-control-row">
                <label>
                    <input type="checkbox"
                           name="main_options[header][visible]" <?php if ($main_options['header']['visible'] == 'on') echo 'checked'; ?>>Включить шапки для страниц
                </label>

                <input type="hidden" id="wpm-design-headers-priority"
                       name="main_options[header_bg][priority]"
                       value="<?php echo wpm_get_option('header_bg.priority', 'default') ?: 'default'; ?>">
            </div>
            <br>

            <div class="wpm-control-row">
                <select id="users-level-for-header"
                        class="users-level"><?php echo wpm_get_levels_select(); ?></select>
                <a class="button button-primary page-header-add" data-action="add">Добавить
                    шапку для уровня доступа</a>
            </div>
            <br>

            <div id="tabs-level-3" tab-id="headers-tabs"
                 class="tabs-level-3 headers-design-tabs wpm-inner-tabs-nav">

                <?php
                $page_headers = array_filter(explode(',', wpm_get_option('header_bg.priority', 'default,pincodes')));

                if(empty($page_headers)) {
                    $page_headers = explode(',', 'default,pincodes');
                }

                if (!empty($page_headers)) {
                    echo '<ul>';
                    foreach ($page_headers as $item) {
                        $wpm_term = get_term($item, 'wpm-levels');
                        if ($item == 'default') { ?>
                            <li class="ui-state-default ui-state-disabled" header-id="default">
                                <a href='#header-tab-default'>По умолчанию</a></li>
                        <?php } elseif($item != 'pincodes') { ?>
                            <li class="ui-state-default" header-id="<?php echo $item; ?>"><a
                                    href='#header-tab-<?php echo $item; ?>'><?php echo $wpm_term->name; ?></a>
                            </li>
                        <?php } ?>
                    <?php }
                    echo '</ul>';

                    foreach ($page_headers as $item) {
                        if ($item == 'default') { ?>
                            <div id="header-tab-default">
                                <div class="wpm-control-row" class="wpm-inner-tab-content">
                                    <p>
                                        <label>
                                            <input type="hidden" name="main_options[header_bg][default][hide_logo]" value="off">
                                            <input type="checkbox"
                                                   name="main_options[header_bg][default][hide_logo]"
                                                <?php echo wpm_option_is("header_bg.default.hide_logo", 'on') ? 'checked' : ''; ?>>
                                            Скрыть логотип
                                        </label>
                                    </p>
                                    <?php
                                    wpm_render_partial('gallery-image-upload', 'admin', array(
                                            'name' => 'main_options[header_bg][default][url]',
                                            'value' => wpm_get_option('header_bg.default.url'),
                                            'id' => 'header_url_default',
                                            'previewClass' => 'wpm-header-img-preview'
                                    ));
                                   ?>
                                </div>
                                <?php wpm_render_partial('header-link', 'admin', array('name' => 'default')) ?>
                            </div>
                        <?php } elseif($item != 'pincodes') { ?>
                            <div id="header-tab-<?php echo $item; ?>"
                                 class="wpm-inner-tab-content">
                                <div class="wpm-control-row">
                                    <p>
                                        <label>
                                            <input type="checkbox"
                                                   value="disabled"
                                                   name="main_options[header_bg][<?php echo $item; ?>][disabled]"
                                                <?php echo $main_options['header_bg'][$item]['disabled'] == 'disabled' ? 'checked' : ''; ?>>
                                            Временно отключить эту шапку
                                        </label>
                                        <span class="trash">
                                            <a class="page-header-remove"
                                               header-id="<?php echo $item; ?>">Удалить  шапку</a>
                                        </span>
                                    </p>
                                    <p>
                                        <label>
                                            <input type="hidden" name="main_options[header_bg][<?php echo $item; ?>][hide_logo]" value="off">
                                            <input type="checkbox"
                                                   name="main_options[header_bg][<?php echo $item; ?>][hide_logo]"
                                                <?php echo wpm_option_is("header_bg.{$item}.hide_logo", 'on') ? 'checked' : ''; ?>>
                                            Скрыть логотип
                                        </label>
                                    </p>
                                    <?php
                                    wpm_render_partial('gallery-image-upload', 'admin', array(
                                            'name' => ('main_options[header_bg]['.$item.'][url]'),
                                            'value' => wpm_get_option('header_bg.'.$item.'.url'),
                                            'id' => ('header_url_' . $item),
                                            'previewClass' => 'wpm-header-img-preview'
                                    ));
                                    ?>
                                </div>
                                <?php wpm_render_partial('header-link', 'admin', array('name' => $item)) ?>
                            </div>
                        <?php } ?>
                    <?php }

                } ?>
                <?php wpm_render_partial('settings-save-button', 'common'); ?>
            </div>
        </div>
        <div id="mbl_inner_tab_3_6" class="wpm-tab-content">
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконок', 'key' => 'breadcrumbs.icon_color', 'default' => '8e8e8e')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текстов', 'key' => 'breadcrumbs.color', 'default' => '8e8e8e')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконки активной крошки', 'key' => 'breadcrumbs.icon_active_color', 'default' => '8e8e8e')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста активной крошки', 'key' => 'breadcrumbs.active_color', 'default' => '8e8e8e')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконки при наведении', 'key' => 'breadcrumbs.icon_hover_color', 'default' => '000000')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста при наведении', 'key' => 'breadcrumbs.hover_color', 'default' => '000000')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет линии под крошками', 'key' => 'breadcrumbs.border_color', 'default' => 'e7e7e7')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет разделителей', 'key' => 'breadcrumbs.separator_color', 'default' => '8e8e8e')) ?>
            <br>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_7" class="wpm-tab-content">
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет заголовка', 'key' => 'term_header.color', 'default' => '000000')) ?>
            <div class="wpm-control-row">
                <label>
                    <input type="checkbox"
                              name="design_options[term_header][bold]" <?php echo wpm_get_design_option('term_header.bold') == 'on' ? 'checked' : ''; ?> >
                    Жирность
                </label>
                &nbsp;&nbsp;&nbsp;
                <label>
                    <input type="checkbox"
                              name="design_options[term_header][bordered]" <?php echo wpm_get_design_option('term_header.bordered') == 'on' ? 'checked' : ''; ?> >
                    Подчеркнутость
                </label>
                &nbsp;&nbsp;&nbsp;
                <label>
                    <input type="checkbox"
                              name="design_options[term_header][italic]" <?php echo wpm_get_design_option('term_header.italic') == 'on' ? 'checked' : ''; ?> >
                    Курсив
                </label>

            </div>
            <div class="wpm-control-row">
                Размер заголовка &nbsp;
                <select
                    name="design_options[term_header][font_size]">
                    <?php
                    for ($i = 14; $i < 26; $i++) {
                        if ($i != wpm_get_design_option('term_header.font_size', '20')) {
                            echo '<option value="' . $i . '">' . $i . 'px</option>';
                        } else {
                            echo '<option selected value="' . $i . '">' . $i . 'px</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="wpm-control-row">
                <label>
                    <input type="checkbox"
                              name="design_options[term_header][hide]" <?php echo wpm_get_design_option('term_header.hide') == 'on' ? 'checked' : ''; ?> >
                    Не показывать заголовок
                </label>
            </div>

            <br><br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет подзаголовка', 'key' => 'term_subheader.color', 'default' => '949494')) ?>
            <div class="wpm-control-row">
                <label>
                    <input type="checkbox"
                              name="design_options[term_subheader][bold]" <?php echo wpm_get_design_option('term_subheader.bold') == 'on' ? 'checked' : ''; ?> >
                    Жирность
                </label>
                &nbsp;&nbsp;&nbsp;
                <label>
                    <input type="checkbox"
                              name="design_options[term_subheader][bordered]" <?php echo wpm_get_design_option('term_subheader.bordered') == 'on' ? 'checked' : ''; ?> >
                    Подчеркнутость
                </label>
                &nbsp;&nbsp;&nbsp;
                <label>
                    <input type="checkbox"
                              name="design_options[term_subheader][italic]" <?php echo wpm_get_design_option('term_subheader.italic') == 'on' ? 'checked' : ''; ?> >
                    Курсив
                </label>

            </div>
            <div class="wpm-control-row">
                Размер подзаголовка &nbsp;
                <select
                    name="design_options[term_subheader][font_size]">
                    <?php
                    for ($i = 14; $i < 26; $i++) {
                        if ($i != wpm_get_design_option('term_subheader.font_size', '16')) {
                            echo '<option value="' . $i . '">' . $i . 'px</option>';
                        } else {
                            echo '<option selected value="' . $i . '">' . $i . 'px</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="wpm-control-row">
                <label>
                    <input type="checkbox"
                              name="design_options[term_subheader][hide]" <?php echo wpm_get_design_option('term_subheader.hide') == 'on' ? 'checked' : ''; ?> >
                    Не показывать подзаголовок
                </label>
            </div>

            <br>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_8" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[folders][show_description]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[folders][show_description]"
                        value="1"
                        <?php if (wpm_get_option('folders.show_description', 1)) : ?>
                            checked="checked"
                        <?php endif; ?>
                    >
                    Отображать описание рубрик
                </label>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[folders][description_expand]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[folders][description_expand]"
                        value="1"
                        <?php if (wpm_get_option('folders.description_expand')) : ?>
                            checked="checked"
                        <?php endif; ?>
                    >
                    Развернуть описание рубрик по-умолчанию
                </label>
            </div>

            <h3>Свернутая кнопка «подробнее»:</h3>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона', 'key' => 'more_button.collapsed.bg_color', 'default' => 'ffffff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста', 'key' => 'more_button.collapsed.color', 'default' => 'acacac')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконки', 'key' => 'more_button.collapsed.icon_color', 'default' => 'acacac')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки', 'key' => 'more_button.collapsed.border_color', 'default' => 'c1c1c1')) ?>
            <br><br>
            <h3>Развернутая кнопка «подробнее»:</h3>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона', 'key' => 'more_button.expanded.bg_color', 'default' => 'fbfbfb')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста', 'key' => 'more_button.expanded.color', 'default' => 'acacac')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконки', 'key' => 'more_button.expanded.icon_color', 'default' => 'acacac')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки', 'key' => 'more_button.expanded.border_color', 'default' => 'e3e3e3')) ?>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_9" class="wpm-tab-content">
            <div class="wpm-control-row">
                Размер шрифта &nbsp; <select
                    name="design_options[term_font_size]">
                    <?php
                    for ($i = 14; $i < 26; $i++) {
                        if ($i != wpm_get_design_option('term_font_size', 17)) {
                            echo '<option value="' . $i . '">' . $i . 'px</option>';
                        } else {
                            echo '<option selected value="' . $i . '">' . $i . 'px</option>';
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="wpm-row">
                <div class="wpm-control-row">
                    <p>Фоновое изображение по-умолчанию</p>
                </div>
                <input type="hidden"
                       id="wpm_term_bg_url"
                       name="main_options[folders][bg_url]"
                       value="<?php echo wpm_get_option('folders.bg_url'); ?>">

                <input type="hidden"
                       id="wpm_term_bg_url_original"
                       name="main_options[folders][bg_url_original]"
                       value="<?php echo wpm_get_option('folders.bg_url_original', wpm_get_option('folders.bg_url')); ?>">

                <div class="wpm-control-row">
                    <p>
                        <button type="button" class="wpm-media-upload-button button"
                                data-id="term_bg_url">Загрузить</button>
                        <a
                              id="delete-wpm-favicon"
                              class="wpm-delete-media-button button submit-delete"
                              data-id="term_bg_url">Удалить</a>
                        <?php wpm_render_partial('crop-button', 'admin', array('key' => wpm_get_option('folders.bg_url'), 'id' => 'term_bg_url', 'w' => 345, 'h' => 260)) ?>
                    </p>
                </div>
                <div class="wpm-term_bg_url-preview-wrap inactive">
                    <div class="wpm-term_bg_url-preview-box">
                        <img
                            src="<?php echo wpm_get_option('folders.bg_url'); ?>"
                            title=""
                            alt=""
                            id="wpm-term_bg_url-preview">
                    </div>
                </div>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[folders][comments_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[folders][comments_show]"
                        value="1"
                        <?php echo wpm_get_option('folders.comments_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать количество комментариев
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[folders][views_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[folders][views_show]"
                        value="1"
                        <?php echo wpm_get_option('folders.views_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать количество просмотров
                </label>
            </div>
            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[folders][access_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[folders][access_show]"
                        value="1"
                        <?php echo wpm_get_option('folders.access_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать доступность
                </label>
            </div>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет названия рубрики', 'key' => 'folders.color', 'default' => 'ffffff')) ?>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_10" class="wpm-tab-content">
            <div class="wpm-row">
                <div class="wpm-control-row">Расположение материалов:</div>
                <label>
                    <input type="radio"
                           name="main_options[main][posts_row_nb]"
                           value="2"
                        <?php echo wpm_option_is('main.posts_row_nb', 2, 2) ? 'checked="checked"' : ''; ?>
                    >
                    2 колонки
                </label>
                &nbsp;&nbsp;
                <label>
                    <input type="radio"
                           name="main_options[main][posts_row_nb]"
                           value="1"
                        <?php echo wpm_option_is('main.posts_row_nb', 1, 2) ? 'checked="checked"' : ''; ?>
                    >
                    1 колонка
                </label>
            </div>

            <div class="wpm-row">
                <div class="wpm-control-row">
                    <p>Фоновое изображение по-умолчанию</p>
                </div>
                <input type="hidden"
                       id="wpm_post_bg_url"
                       name="main_options[materials][bg_url]"
                       value="<?php echo wpm_get_option('materials.bg_url'); ?>">

                <input type="hidden"
                       id="wpm_post_bg_url_original"
                       name="main_options[materials][bg_url_original]"
                       value="<?php echo wpm_get_option('materials.bg_url_original', wpm_get_option('materials.bg_url')); ?>">

                <div class="wpm-control-row">
                    <p>
                        <button type="button" class="wpm-media-upload-button button"
                                data-id="post_bg_url"><?php _e('Загрузить', 'wpm'); ?></button>
                        <a
                              id="delete-wpm-favicon"
                              class="wpm-delete-media-button button submit-delete"
                              data-id="post_bg_url"><?php _e('Удалить', 'wpm'); ?></a>
                        <?php wpm_render_partial('crop-button', 'admin', array('key' => wpm_get_option('materials.bg_url'), 'id' => 'post_bg_url', 'w' => 295, 'h' => 220)) ?>
                    </p>
                </div>
                <div class="wpm-post_bg_url-preview-wrap inactive">
                    <div class="wpm-post_bg_url-preview-box">
                        <img
                            src="<?php echo wpm_get_option('materials.bg_url'); ?>"
                            title=""
                            alt=""
                            id="wpm-post_bg_url-preview">
                    </div>
                </div>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[materials][number_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[materials][number_show]"
                        value="1"
                        <?php echo wpm_get_option('materials.number_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать порядковый номер материала
                </label>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[materials][content_type_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[materials][content_type_show]"
                        value="1"
                        <?php echo wpm_get_option('materials.content_type_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать тип контента
                </label>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[materials][homework_status_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[materials][homework_status_show]"
                        value="1"
                        <?php echo wpm_get_option('materials.homework_status_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать статус ДЗ
                </label>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[materials][date_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[materials][date_show]"
                        value="1"
                        <?php echo wpm_get_option('materials.date_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать дату
                </label>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[materials][comments_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[materials][comments_show]"
                        value="1"
                        <?php echo wpm_get_option('materials.comments_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать комментарии
                </label>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[materials][views_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[materials][views_show]"
                        value="1"
                        <?php echo wpm_get_option('materials.views_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать количество просмотров
                </label>
            </div>

            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[materials][access_show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[materials][access_show]"
                        value="1"
                        <?php echo wpm_get_option('materials.access_show', 1) ? 'checked="checked"' : ''; ?>
                    >
                    Отображать доступность
                </label>
            </div>

            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет индикаторов', 'key' => 'materials.indicator_color', 'default' => 'ffffff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет инонок «тип контента»', 'key' => 'materials.filetype_color', 'default' => 'ffffff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона иконок «тип контента»', 'key' => 'materials.filetype_bg_color', 'default' => '000000')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона блока с описанием', 'key' => 'materials.desc_bg_color', 'default' => 'fafafa')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки блока с описанием', 'key' => 'materials.desc_border_color', 'default' => 'eaeaea')) ?>
            <br>
            <h4 style="margin-bottom: 0">Доступ открыт:</h4>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона блока с описанием при наведении', 'key' => 'materials.open_hover_desc_bg_color', 'default' => 'dfece0')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки блока с описанием при наведении', 'key' => 'materials.open_hover_desc_border_color', 'default' => 'cedccf')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста на кнопке', 'key' => 'materials.open_button_color', 'default' => 'fff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона кнопки', 'key' => 'materials.open_button_bg_color', 'default' => 'a0b0a1')) ?>
            <br>
            <h4 style="margin-bottom: 0">Доступ закрыт:</h4>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона блока с описанием при наведении', 'key' => 'materials.closed_hover_desc_bg_color', 'default' => 'eed5d5')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки блока с описанием при наведении', 'key' => 'materials.closed_hover_desc_border_color', 'default' => 'ddc4c4')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста на кнопке', 'key' => 'materials.closed_button_color', 'default' => 'fff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона кнопки', 'key' => 'materials.closed_button_bg_color', 'default' => 'd29392')) ?>

            <br>
            <h4 style="margin-bottom: 0">Не пройденный урок автотренинга:</h4>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона блока с описанием при наведении', 'key' => 'materials.inaccessible_hover_desc_bg_color', 'default' => 'd8d8d8')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки блока с описанием при наведении', 'key' => 'materials.inaccessible_hover_desc_border_color', 'default' => 'bebebe')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста на кнопке', 'key' => 'materials.inaccessible_button_color', 'default' => 'fff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона кнопки', 'key' => 'materials.inaccessible_button_bg_color', 'default' => '838788')) ?>


            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_11" class="wpm-tab-content">
            <h3>Контент:</h3>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконок на вкладках', 'key' => 'material_inner.tab_icon_color', 'default' => '9b9b9b')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста на вкладках', 'key' => 'material_inner.tab_color', 'default' => '9b9b9b')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконок на активных вкладках', 'key' => 'material_inner.active_tab_icon_color', 'default' => '555555')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста на активных вкладках', 'key' => 'material_inner.active_tab_color', 'default' => '555555')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона контента', 'key' => 'material_inner.content_bg_color', 'default' => 'ffffff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки контента', 'key' => 'material_inner.content_border_color', 'default' => 'e3e3e3')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона неактивной вкладки', 'key' => 'material_inner.inactive_tab_bg_color', 'default' => 'efefef')) ?>
            <br><br>
            <h3>Переключение между уроками:</h3>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста', 'key' => 'material_inner.lesson_status_color', 'default' => '9b9b9b')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста при наведении', 'key' => 'material_inner.lesson_status_hover_color', 'default' => '555555')) ?>
            <br><br>
            <h3>Комментарии:</h3>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконок на вкладках', 'key' => 'material_comments.tabs_icon_color', 'default' => '9b9b9b')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста на вкладках', 'key' => 'material_comments.tabs_color', 'default' => '9b9b9b')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет иконок на активных вкладках', 'key' => 'material_comments.tabs_active_icon_color', 'default' => '555555')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет текста на активных вкладках', 'key' => 'material_comments.tabs_active_color', 'default' => '555555')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона контента', 'key' => 'material_comments.bg_color', 'default' => 'ffffff')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки контента', 'key' => 'material_comments.border_color', 'default' => 'e3e3e3')) ?>
            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона неактивной вкладки', 'key' => 'material_comments.inactive_tab_bg_color', 'default' => 'efefef')) ?>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_12" class="wpm-tab-content">
            <div class="wpm-control-row">
                <label><input type="checkbox"
                              name="main_options[footer][visible]" <?php if ($main_options['footer']['visible'] == 'on') echo 'checked'; ?>>Отображать подвал
                </label>
            </div>
            <div class="wpm-control-row">
                <?php wp_editor($main_options['footer']['content'], 'wpm_footer', array('textarea_name' => 'main_options[footer][content]', 'editor_height' => 300)); ?>
            </div>

            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона', 'key' => 'footer_options.background', 'default' => 'f9f9f9')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет рамки', 'key' => 'footer_options.border', 'default' => 'e7e7e7')) ?>
            <br>
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="design_options[footer_options][transparent]" value="0">
                    <input type="checkbox"
                              name="design_options[footer_options][transparent]"
                              <?php if (wpm_get_design_option('footer_options.transparent', '0') == '1') echo 'checked'; ?>
                              value="1"
                    >Сделать прозрачным
                </label>
            </div>


            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_3_14" class="wpm-tab-content">
            <div class="wpm-row">
                <label>
                    <input type="hidden" name="main_options[progress][show]" value="0">
                    <input
                        type="checkbox"
                        name="main_options[progress][show]"
                        id="wpm-show-progress"
                        value="1"
                        <?php if (wpm_get_option('progress.show', 1)) : ?>
                            checked="checked"
                        <?php endif; ?>
                    >
                    Показывать прогресс курса
                </label>
            </div>

            <div
                id="wpm-progress-options"
                <?php if (!wpm_get_option('progress.show', 1)) : ?>
                    style="display: none;"
                <?php endif; ?>
            >
                <div class="wpm-row">
                    <label>
                        <input type="hidden" name="main_options[progress][folders]" value="0">
                        <input
                            type="checkbox"
                            name="main_options[progress][folders]"
                            value="1"
                            <?php if (wpm_get_option('progress.folders', 1)) : ?>
                                checked="checked"
                            <?php endif; ?>
                        >
                        На папках с файлами
                    </label>
                </div>
                <div class="wpm-row">
                    <label>
                        <input type="hidden" name="main_options[progress][parent_folders]" value="0">
                        <input
                            type="checkbox"
                            name="main_options[progress][parent_folders]"
                            value="1"
                            <?php if (wpm_get_option('progress.parent_folders', 1)) : ?>
                                checked="checked"
                            <?php endif; ?>
                        >
                        На родительских папках
                    </label>
                </div>
                <div class="wpm-row">
                    <label>
                        <input type="hidden" name="main_options[progress][subfolders]" value="0">
                        <input
                            type="checkbox"
                            name="main_options[progress][subfolders]"
                            value="1"
                            <?php if (wpm_get_option('progress.subfolders', 1)) : ?>
                                checked="checked"
                            <?php endif; ?>
                        >
                        На подпапках
                    </label>
                </div>
                <div class="wpm-row">
                    <label>
                        <input type="hidden" name="main_options[progress][materials]" value="0">
                        <input
                            type="checkbox"
                            name="main_options[progress][materials]"
                            value="1"
                            <?php if (wpm_get_option('progress.materials', 1)) : ?>
                                checked="checked"
                            <?php endif; ?>
                        >
                        На странице списка материалов
                    </label>
                </div>
                <div class="wpm-row">
                    <label>
                        <input type="hidden" name="main_options[progress][content]" value="0">
                        <input
                            type="checkbox"
                            name="main_options[progress][content]"
                            value="1"
                            <?php if (wpm_get_option('progress.content', 1)) : ?>
                                checked="checked"
                            <?php endif; ?>
                        >
                        Под контентом в открытом файле
                    </label>
                </div>
            </div>

            <br>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет линии прогресса', 'key' => 'progress.line_color', 'default' => '65bf49')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет фона', 'key' => 'progress.bg_color', 'default' => 'f5f5f5')) ?>
            <?php wpm_render_partial('options/color-row', 'admin', array('label' => 'Цвет процентов', 'key' => 'progress.text_color', 'default' => '4a4a4a')) ?>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
    </div>
</div>