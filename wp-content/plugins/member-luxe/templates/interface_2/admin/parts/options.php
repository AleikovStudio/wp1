<?php wpm_render_partial('options/js', 'admin') ?>
<div class="wrap wpm-options-page">
    <div id="icon-options-general" class="icon32"></div>
    <div class="wpm-admin-page-header">
        <h2>Настройки</h2>
    </div>
    <?php $default_design_options = get_option('wpm_design_options_default'); ?>
    <form name="wpm-settings-form" method="post" action="">
        <div class="options-wrap wpm-ui-wrap">
            <div id="wpm-options-tabs" tab-id="vertical-menu-1" class="wpm-tabs wpm-tabs-vertical">
                <ul class="tabs-nav">
                    <li><a href="#tab-1">Общие</a></li>
                    <li><a href="#tab-2">Страницы</a></li>
                    <li><a href="#tab-3">Внешний вид</a></li>
                    <li><a href="#tab-4">Автотренинги</a></li>
                    <li><a href="#tab-5">Письма</a></li>
                    <li><a href="#tab-6">Защита</a></li>
                    <li><a href="#tab-7">Массовые операции</a></li>
                </ul>
                <div id="tab-1" class="tab">
                    <?php wpm_render_partial('options/tab-1', 'admin', compact('main_options', 'design_options')) ?>
                </div>
                <div id="tab-2" class="tab">
                    <?php wpm_render_partial('options/tab-2', 'admin', compact('main_options', 'design_options')) ?>
                </div>
                <div id="tab-3" class="tab">
                    <?php wpm_render_partial('options/tab-3', 'admin', compact('main_options', 'design_options')) ?>
                </div>
                <div id="tab-4" class="tab">
                    <?php wpm_render_partial('options/tab-4', 'admin', compact('main_options', 'design_options')) ?>
                </div>
                <div id="tab-5" class="tab">
                    <?php wpm_render_partial('options/tab-5', 'admin', compact('main_options', 'design_options')) ?>
                </div>
                <div id="tab-6" class="tab">
                    <?php wpm_render_partial('options/tab-6', 'admin', compact('main_options', 'design_options')) ?>
                </div>
                <div id="tab-7" class="tab">
                    <?php wpm_render_partial('options/tab-7', 'admin', compact('main_options', 'design_options')) ?>
                </div>
            </div>
        </div>
    </form>
</div>