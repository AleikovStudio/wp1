<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2><?php _e('Статистика входов', 'wpm'); ?></h2>
    <div class="panel with-nav-tabs panel-default">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#stats_pane"><?php _e('Статистика', 'wpm'); ?></a></li>
                <?php foreach (MBLStatsBlocker::getFilters() as $id => $filter) : ?>
                    <?php $usersNb = count(wpm_array_get($filter, 'users', array())); ?>
                    <li><a href="#filter_pane_<?php echo $id; ?>"><?php echo $filter['name'] ?><?php echo $usersNb ? (' (' . $usersNb . ')') : ''; ?></a></li>
                <?php endforeach; ?>
                <li><a href="#filter_pane_new"><?php _e('Добавить автоблокировку', 'wpm'); ?></a></li>
                <?php $excludesNb = count(get_option('wpm_stats_block_excludes', array())) ?>
                <li><a class="wpm-excludes-pane" href="#filter_pane_excludes"><?php _e('Исключения', 'wpm'); ?><?php echo $excludesNb ? (' (' . $excludesNb . ')') : ''; ?></a></li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content clearfix">
                <div class="tab-pane active" id="stats_pane">
                    <div class="page-content-wrap">
                        <form id="users-filter" action="<?php echo $base_url ?>">
                            <input type="hidden" name="post_type" value="wpm-page">
                            <input type="hidden" name="page" value="wpm-login-stats">

                            <p class="search-box">
                                <label class="screen-reader-text" for="post-search-input"><?php _e('Поиск', 'wpm'); ?>
                                    :</label>
                                <input type="search" id="post-search-input" name="s" value="<?php echo $_GET['s']; ?>">
                                <input type="submit" name="" id="search-submit" class="button"
                                       value="<?php _e('Поиск', 'wpm'); ?>">
                            </p>
                            <br><br>

                            <div class="keys-nav-links tablenav top">
                                <div class="alignleft actions">
                                    <input type="text"
                                           autocomplete="off"
                                           placeholder="<?php _e('с', 'wpm'); ?>"
                                           id="date_from"
                                           readonly="true"
                                           name="date_from"
                                           value="<?php echo $from ?>"
                                           class="datepicker">
                                    <input type="text"
                                           autocomplete="off"
                                           placeholder="<?php _e('по', 'wpm'); ?>"
                                           id="date_to"
                                           readonly="true"
                                           name="date_to"
                                           value="<?php echo $to ?>"
                                           class="datepicker">
                                    <input type="submit" name="" id="post-query-submit" class="button"
                                           value="<?php _e('Фильтр', 'wpm'); ?>">
                                    <button type="button" id="reset-search"
                                            class="button"><?php _e('Очистить', 'wpm'); ?></button>
                                </div>
                                <div class="tablenav-pages">
                                    <span class="displaying-num"><?php echo $total_records; ?><?php _e('пользователей', 'wpm'); ?></span>
                                    <span class="pagination-links">
                            <a class="first-page disabled" title="<?php _e('Перейти на первую страницу', 'wpm'); ?>"
                               href="<?php echo $first_link; ?>">«</a>
                            <a class="prev-page" title="<?php _e('Перейти на предыдущую страницу', 'wpm'); ?>"
                               href="<?php echo $prev_link; ?>">‹</a>
                            <span class="paging-input">
                                <input class="current-page" title="<?php _e('Текущая страница', 'wpm'); ?>" type="text"
                                       name="paged"
                                       value="<?php echo $page; ?>" size="1"> <?php _e('из', 'wpm'); ?>
                                <span class="total-pages"><?php echo $total_pages; ?></span>
                            </span>
                            <a class="next-page" title="<?php _e('Перейти на следующую страницу', 'wpm'); ?>"
                               href="<?php echo $next_link; ?>">›</a>
                            <a class="last-page" title="<?php _e('Перейти на последнюю страницу', 'wpm'); ?>"
                               href="<?php echo $last_link; ?>">»</a>
                        </span>
                                </div>
                            </div>
                            <div class="responses">
                                <?php if (!empty($items)) : ?>
                                    <table class="wp-list-table widefat fixed pages">
                                        <thead>
                                        <tr>
                                            <th class="column-primary column-user"><?php _e('Пользователь', 'wpm'); ?></th>
                                            <th class="column-total"><?php _e('Всего заходов', 'wpm'); ?></th>
                                            <th class="column-unique"><?php _e('Уникальных', 'wpm'); ?></th>
                                            <th class="column-active"><?php _e('Активность', 'wpm'); ?></th>
                                            <th class="column-exclude"><?php _e('Исключения', 'wpm'); ?></th>
                                            <th class="column-actions"></th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th class="column-primary column-user"><?php _e('Пользователь', 'wpm'); ?></th>
                                            <th class="column-total"><?php _e('Всего заходов', 'wpm'); ?></th>
                                            <th class="column-unique"><?php _e('Уникальных', 'wpm'); ?></th>
                                            <th class="column-active"><?php _e('Активность', 'wpm'); ?></th>
                                            <th class="column-exclude"><?php _e('Исключения', 'wpm'); ?></th>
                                            <th class="column-actions"></th>
                                        </tr>
                                        </tfoot>
                                        <?php
                                        $i = 0;
                                        foreach ($items as $item) {
                                            $alternative = (++$i % 2) ? 'alternate ' : '';

                                            $user_profile_url = admin_url('/user-edit.php?user_id=' . $item->ID);
                                            $user_name = $item->display_name . ($item->user_login != $item->display_name ? ' (' . $item->user_login . ')' : '');
                                            $user = '<a href="' . $user_profile_url . '">' . $user_name . '</a>';
                                            ?>
                                            <tr class="status-publish hentry iedit <?php echo $alternative ?>">
                                                <td data-colname="<?php _e('Пользователь', 'wpm'); ?>"
                                                    class="column-primary column-title">
                                                    <?php echo $user; ?>
                                                    <button type="button" class="toggle-row"><span
                                                                class="screen-reader-text"><?php _e('Show more details'); ?></span>
                                                    </button>
                                                </td>
                                                <td data-colname="<?php _e('Всего заходов', 'wpm'); ?>"
                                                    class="column-total">
                                                    <?php echo $item->nb_logins; ?>
                                                </td>
                                                <td data-colname="<?php _e('Уникальных', 'wpm'); ?>" class="column-unique">
                                                    <?php echo $item->unique_logins; ?>
                                                </td>
                                                <td data-colname="<?php _e('Активность', 'wpm'); ?>" class="column-active">
                                                    <?php echo wpm_show_user_id_column_content('', 'wpm_status', $item->ID, urlencode($current_url)); ?>
                                                </td>
                                                <td data-colname="<?php _e('Исключения', 'wpm'); ?>" class="column-exclude">
                                                    <a href="#" data-exclude="<?php echo $item->ID; ?>"><?php echo wpm_is_excluded_from_block($item->ID) ? __('Убрать', 'wpm') : __('Добавить', 'wpm'); ?></a>
                                                </td>
                                                <td class="column-actions">
                                                    <?php if (!empty($item->logins)) : ?>
                                                        <a href="#"
                                                           data-toggle-row="<?php echo $item->ID ?>"><?php _e('Подробнее', 'wpm'); ?></a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr id="details-row-<?php echo $item->ID ?>">
                                                <td colspan="6" class="details-row">
                                                    <div style="display: none">
                                                        <?php if (!empty($item->logins)) : ?>
                                                            <table class="wp-list-table fixed wpm-logins">
                                                                <thead>
                                                                <tr>
                                                                    <th width="5%"><?php _e('№', 'wpm'); ?></th>
                                                                    <th><?php _e('Дата и время', 'wpm'); ?></th>
                                                                    <th><?php _e('IP', 'wpm'); ?></th>
                                                                    <th><?php _e('Страна', 'wpm'); ?></th>
                                                                    <th><?php _e('Браузер', 'wpm'); ?></th>
                                                                    <th><?php _e('ОС', 'wpm'); ?></th>
                                                                    <th><?php _e('Устройство', 'wpm'); ?></th>
                                                                </tr>
                                                                </thead>
                                                                <?php foreach ($item->logins as $k => $login) : ?>
                                                                    <tr>
                                                                        <td class="check-column"><?php echo $k + 1; ?></td>
                                                                        <td class="check-column"><?php echo date_i18n('d.m.Y H:i:s', strtotime($login->logged_in_at) + (get_option('gmt_offset') * HOUR_IN_SECONDS)); ?></td>
                                                                        <td class="check-column"><a target=«_blank»
                                                                                                    href="http://ipgeobase.ru/?address=<?php echo $login->ip; ?>"><?php echo $login->ip; ?></a>
                                                                        </td>
                                                                        <td class="check-column"><?php echo implode(' : ', array_filter(array($login->country_name, $login->country_code))); ?></td>
                                                                        <td class="check-column"><?php echo $login->browser; ?></td>
                                                                        <td class="check-column"><?php echo $login->os; ?></td>
                                                                        <td class="check-column">
                                                                            <?php echo implode(' : ', array_filter(array(wpm_stats_get_device_type($login->device), $login->brandname, $login->model))); ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </table>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }; ?>
                                    </table>
                                    <div class="tablenav bottom">
                                        <div class="tablenav-pages">
                                            <span class="displaying-num"><?php echo $total_records; ?><?php _e('пользователей', 'wpm'); ?></span>
                                            <span class="pagination-links">
                                    <a class="first-page disabled" title="<?php _e('Перейти на первую страницу', 'wpm'); ?>"
                                       href="<?php echo $first_link; ?>">«</a>
                                    <a class="prev-page" title="<?php _e('Перейти на предыдущую страницу', 'wpm'); ?>"
                                       href="<?php echo $prev_link; ?>">‹</a>
                                    <span class="paging-input">
                                        <?php echo $page; ?> <?php _e('из', 'wpm'); ?>
                                        <span class="total-pages"><?php echo $total_pages; ?></span>
                                    </span>
                                    <a class="next-page" title="<?php _e('Перейти на следующую страницу', 'wpm'); ?>"
                                       href="<?php echo $next_link; ?>">›</a>
                                    <a class="last-page" title="<?php _e('Перейти на последнюю страницу', 'wpm'); ?>"
                                       href="<?php echo $last_link; ?>">»</a>
                                </span>
                                        </div>
                                        <br class="clear">
                                    </div>
                                <?php else : ?>
                                    <p><?php _e('Нет записей', 'wpm') ?></p>
                                <?php endif; ?>
                            </div>
                        </form>
                        <div id="ajax-response"></div>
                    </div>
                </div>
                <?php foreach (MBLStatsBlocker::getFilters() as $id => $filter) : ?>
                    <div class="tab-pane auto-block" id="filter_pane_<?php echo $id; ?>">
                        <?php wpm_render_partial('auto-blocking-form', 'admin', array('filter' => $filter, 'id' => $id)); ?>
                        <br>
                        <h3><?php _e('Заблокированные пользователи', 'wpm'); ?></h3>
                        <div class="page-content-wrap" data-id="<?php echo $id; ?>">
                            <?php wpm_render_partial('auto-blocking-users', 'admin', array('filter' => $filter, 'id' => $id, 'current_url' => $current_url)); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="tab-pane auto-block" id="filter_pane_new">
                    <?php wpm_render_partial('auto-blocking-form', 'admin', array('filter' => null, 'id' => null)); ?>
                </div>
                <div class="tab-pane auto-block" id="filter_pane_excludes">
                    <?php wpm_render_partial('auto-blocking-excludes', 'admin'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function resetFilter(elem) {
        var $ = jQuery,
            $form = $(elem).closest('form');

        if ($('#post-search-input').val() != '') {
            $form.find('.keys-nav-links select').each(function () {
                $(this).val($(this).find('option:first').attr('value'));
            });
        }

    }

    function resetSearch() {
        jQuery('#post-search-input').val('');
    }

    jQuery(function ($) {
        $('[data-toggle-row]').on('click', function (e) {
            var $item = $(this),
                $id = $item.data('toggle-row'),
                $row = $('#details-row-' + $id + ' div:first');
            $row.slideToggle();
            return false;
        });
        $("#date_to").datepicker({dateFormat : 'dd.mm.yy'});
        $("#date_from").datepicker({dateFormat : 'dd.mm.yy'}).bind("change", function () {
            var minValue = $(this).val();
            minValue = $.datepicker.parseDate("dd.mm.yy", minValue);
            minValue.setDate(minValue.getDate() + 1);
            $("#date_to").datepicker("option", "minDate", minValue);
        });
        $('#reset-search').on('click', function () {
            $("#date_to").val('');
            $("#date_from").val('');
            $(this).closest('form').submit();
        });
        $('.nav-tabs li').on('click', function() {
            switchTab($(this).find('>a'));

            return false;
        });
        $('[data-exclude]').on('click', function () {
            $.post(
                ajaxurl,
                {
                    action  : 'wpm_stats_exclude',
                    user_id : $(this).data('exclude')
                },
                function (result) {
                    if (result.success) {
                        window.location.reload();
                    }
                },
                "json"
            );

            return false;
        });

        function switchTab($link) {
            if ($link.length) {
                var $li = $link.closest('li'),
                    $tabs = $li.closest('.nav-tabs'),
                    $selector = $link.attr('href'),
                    $pane = $($selector),
                    $tabContent = $pane.closest('.tab-content');

                $tabs.find('li').removeClass('active');
                $tabContent.find('.tab-pane').removeClass('active');
                window.location.hash = $link.attr('href');

                $li.addClass('active');
                $pane.addClass('active');
            }

        }

        if (window.location.hash != '') {
            switchTab($('a[href="' + window.location.hash + '"]'));
        }
    });
</script>