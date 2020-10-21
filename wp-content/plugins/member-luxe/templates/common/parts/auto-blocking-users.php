<?php $page = isset($page) ? $page : 1; ?>
<?php $users = wpm_array_get($filter, 'users', array()); ?>
<?php $totalPages = ceil(count($users) / MBLStatsBlocker::getPerPage()); ?>
<div class="responses">
    <?php if (!empty($filter['users'])) : ?>
        <table class="wp-list-table widefat fixed pages">
            <thead>
            <tr>
                <th class="column-primary column-user"><?php _e('Пользователь', 'wpm'); ?></th>
                <th class="column-date-blocked"><?php _e('Дата блокировки', 'wpm'); ?></th>
                <th class="column-exclude"><?php _e('Исключения', 'wpm'); ?></th>
                <th class="column-actions"></th>
                <th class="column-actions"></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th class="column-primary column-user"><?php _e('Пользователь', 'wpm'); ?></th>
                <th class="column-date-blocked"><?php _e('Дата блокировки', 'wpm'); ?></th>
                <th class="column-exclude"><?php _e('Исключения', 'wpm'); ?></th>
                <th class="column-actions"></th>
                <th class="column-actions"></th>
            </tr>
            </tfoot>
            <?php
            $i = 0;

            foreach (MBLStatsBlocker::sliceForPage($users, $page) as $userId => $time) {
                $alternative = (++$i % 2) ? 'alternate ' : '';

                $user = get_user_by('id', $userId);
                if(!$user) {
                    continue;
                }

                $user_profile_url = admin_url('/user-edit.php?user_id=' . $user->ID);
                $user_name = $user->display_name . ($user->user_login != $user->display_name ? ' (' . $user->user_login . ')' : '');
                $user = '<a href="' . $user_profile_url . '">' . $user_name . '</a>';
                $logins = MBLStatsAdmin::getUserLogins($userId);
                ?>
                <tr class="status-publish hentry iedit <?php echo $alternative ?>">
                    <td data-colname="<?php _e('Пользователь', 'wpm'); ?>"
                        class="column-primary column-title">
                        <?php echo $user; ?>
                        <button type="button" class="toggle-row"><span
                                    class="screen-reader-text"><?php _e('Show more details'); ?></span>
                        </button>
                    </td>
                    <td data-colname="<?php _e('Дата блокировки', 'wpm'); ?>"
                        class="column-date-blocked">
                        <?php echo date_i18n('d.m.Y H:i', $time + (get_option('gmt_offset') * HOUR_IN_SECONDS)); ?>
                    </td>
                    <td data-colname="<?php _e('Исключения', 'wpm'); ?>" class="column-exclude">
                        <a href="#" data-exclude="<?php echo $userId; ?>"><?php echo __('Добавить', 'wpm'); ?></a>
                    </td>
                    <td class="column-actions">
                        <a href="<?php echo admin_url('/users.php?action=wpm_activate_single_account&user=' . $userId . '&filter_id=' . $id . '&ret=' . urlencode($current_url . '#filter_pane_' . $id)); ?> "><?php _e('Снять блок', 'wpm'); ?></a>
                    </td>
                    <td class="column-actions">
                        <?php if (!empty($logins)) : ?>
                            <a href="#"
                               data-toggle-row="<?php echo $id . '-' . $userId ?>"><?php _e('Подробнее', 'wpm'); ?></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr id="details-row-<?php echo $id . '-' . $userId ?>">
                    <td colspan="5" class="details-row">
                        <div style="display: none">
                            <?php if (!empty($logins)) : ?>
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
                                    <?php foreach ($logins as $k => $login) : ?>
                                        <tr>
                                            <td class="check-column"><?php echo $k + 1; ?></td>
                                            <td class="check-column"><?php echo date_i18n('d.m.Y H:i', strtotime($login->logged_in_at) + (get_option('gmt_offset') * HOUR_IN_SECONDS)); ?></td>
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
            <div class="tablenav-pages users-block-paginator">
                <?php echo MBLStatsBlocker::getPaginator($users, $page); ?>
            </div>
            <br class="clear">
        </div>
    <?php else : ?>
        <p><?php _e('Нет записей', 'wpm') ?></p>
    <?php endif; ?>

</div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var $currentPage = <?php echo $page; ?>;
        $('.users-block-paginator [data-page]').on('click', function () {
            var $this = $(this),
                $page = $this.data('page'),
                $paginator = $this.closest('.users-block-paginator'),
                $wrapper = $this.closest('.page-content-wrap'),
                $wrapperHeight = $wrapper.height(),
                $marginTop = Math.floor($wrapperHeight/2) - 50;

            if($page == 'prev' ) {
                $page = Math.max(1, $currentPage - 1);
            } else if($page == 'next') {
                $page = Math.min(<?php echo $totalPages; ?>, $currentPage + 1);
            } else {
                $page = parseInt($page);
            }

            $wrapper.height($wrapperHeight);
            $wrapper.html('<div class="wpm-inline-loader" style="width: 50px; margin: auto; position: relative; top: ' + $marginTop + 'px"></div>');

            $.post(ajaxurl, {
                action : 'wpm_get_auto_blocking_users',
                page : $page,
                id : $wrapper.data('id'),
                url : '<?php echo $current_url; ?>'
            }, function (data) {
                $wrapper.html(data);
                $paginator.find('[data-current]').html($page);
                $currentPage = $page;
            });

            return false;
        })
    })
</script>

