<div class="wpm-tab-content">
    <div class="wpm-inner-tabs" tab-id="h-tabs-4">
        <ul class="wpm-inner-tabs-nav">
            <li><a href="#mbl_inner_tab_4_1">Материалы</a></li>
            <li><a href="#mbl_inner_tab_4_2">Домашние задания</a></li>
        </ul>
        <div id="mbl_inner_tab_4_1" class="wpm-tab-content">
            <div class="wpm-control-row">
                <label>
                    <input type="hidden" name="design_options[page][show_all]" value="0">
                    <input type="checkbox"
                              name="design_options[page][show_all]"
                        <?php if (wpm_get_design_option('page.show_all') == 'on') echo 'checked'; ?>>
                    Отображать все уроки автотренингов
                </label>
            </div>
            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
        <div id="mbl_inner_tab_4_2" class="wpm-tab-content">
            <div class="wpm-row">
                <dl>
                    <dt>Выберите порядок сортировки комментариев к домашним заданиям:</dt>
                    <dd>
                        <label for="comments_order_asc">
                            <input type="radio" name="main_options[main][comments_order]"
                                   id="comments_order_asc"
                                   value="asc" <?php echo ($main_options['main']['comments_order'] == 'asc' || !$main_options['main']['comments_order']) ? 'checked' : ''; ?> />
                            От более ранних к более поздним
                        </label>
                    </dd>
                    <dd>
                        <label for="comments_order_desc">
                            <input type="radio" name="main_options[main][comments_order]"
                                   id="comments_order_desc"
                                   value="desc" <?php echo $main_options['main']['comments_order'] == 'desc' ? 'checked' : ''; ?> />
                            От более поздних до более ранних
                        </label>
                    </dd>
                </dl>
            </div>

            <?php wpm_render_partial('settings-save-button', 'common'); ?>
        </div>
    </div>
</div>