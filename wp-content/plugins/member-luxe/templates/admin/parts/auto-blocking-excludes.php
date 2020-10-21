<?php $userIds = get_option('wpm_stats_block_excludes', array()); ?>
<div class="responses">
    <?php if (!empty($userIds)) : ?>
        <table class="wp-list-table widefat fixed pages">
            <thead>
            <tr>
                <th class="column-primary column-user"><?php _e('Пользователь', 'wpm'); ?></th>
                <th class="column-actions"></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <th class="column-primary column-user"><?php _e('Пользователь', 'wpm'); ?></th>
                <th class="column-actions"></th>
            </tr>
            </tfoot>
            <?php
            $i = 0;

            foreach ($userIds as $userId) {
                $alternative = (++$i % 2) ? 'alternate ' : '';

                $user = get_user_by('id', $userId);
                if(!$user) {
                    continue;
                }

                $user_profile_url = admin_url('/user-edit.php?user_id=' . $user->ID);
                $user_name = $user->display_name . ($user->user_login != $user->display_name ? ' (' . $user->user_login . ')' : '');
                $user = '<a href="' . $user_profile_url . '">' . $user_name . '</a>';
                ?>
                <tr class="status-publish hentry iedit <?php echo $alternative ?>">
                    <td data-colname="<?php _e('Пользователь', 'wpm'); ?>"
                        class="column-primary column-title">
                        <?php echo $user; ?>
                    </td>
                    <td class="column-actions">
                        <a href="#" data-exclude="<?php echo $userId; ?>"><?php echo __('Убрать', 'wpm'); ?></a>
                    </td>
                </tr>
                <?php
            }; ?>
        </table>
    <?php else : ?>
        <p><?php _e('Нет записей', 'wpm') ?></p>
    <?php endif; ?>
</div>

