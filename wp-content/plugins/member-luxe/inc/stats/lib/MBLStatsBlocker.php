<?php

class MBLStatsBlocker
{
    private static $perPage = 15;

    public static function getIntervals()
    {
        return array(
            '1 MINUTE' => __('1 минута'),
            '5 MINUTE' => __('5 минут'),
            '15 MINUTE' => __('15 минут'),
            '30 MINUTE' => __('30 минут'),
            '1 HOUR' => __('1 час'),
            '12 HOUR' => __('12 часов'),
            '1 DAY' => __('1 день'),
            '1 WEEK' => __('1 неделя'),
            '1 MONTH' => __('1 месяц'),
        );
    }

    public static function updateBlock($name, $interval, $entries, $id = null)
    {
        $result = array(
            'success' => false
        );

        $intervals = self::getIntervals();

        if ($name == '') {
            $result['error'] = 'Укажите название фильтра';
        } elseif (!array_key_exists($interval, $intervals)) {
            $result['error'] = 'Выберите интервал из списка';
        } elseif (!$entries) {
            $result['error'] = 'Укажите количество уникальных входов';
        } else {
            $filters = self::getFilters();
            if ($id === null) {
                $id = '' . time() . rand(0, 10000);
            }
            if (!isset($filters[$id])) {
                $filters[$id] = array(
                    'users' => array()
                );
            }

            $filters[$id]['name'] = $name;
            $filters[$id]['interval'] = $interval;
            $filters[$id]['entries'] = $entries;

            self::updateFilters($filters);
            $result['success'] = true;
        }

        return $result;
    }


    /**
     * @param WP_User|null $user
     * @return bool
     */
    public static function checkLogin($user)
    {
        global $wpdb, $current_user;

        $user = $user ?: $current_user;
        $userId = $user->ID;

        if (!in_array('customer', $user->roles)) {
            return false;
        }

        if (!$userId) {
            return false;
        }

        if (wpm_is_excluded_from_block($userId)) {
            return false;
        }

        $loginTable = MBLStats::getTable();
        $filters = self::getFilters();


        foreach ($filters as $id => $filter) {
            $where = "lt.logged_in_at > (DATE_SUB(NOW(), INTERVAL " . $filter['interval'] . "))";
            $lastBlock = get_user_meta($userId, 'wpm_last_block', true);

            if ($lastBlock) {
//                $lastBlockDate = date("Y-m-d H:i:s", $lastBlock);
                $where .= " AND lt.logged_in_at > '{$lastBlock}'";
            }

            $sql = "SELECT COUNT(DISTINCT(lt.ip)) as nb FROM {$loginTable} lt WHERE user_id={$userId} AND {$where}";
            $entries = wpm_array_get($wpdb->get_row($sql, ARRAY_A), 'nb', 0);

            if ($entries >= wpm_array_get($filter, 'entries', 0)) {

                update_user_meta($userId, 'wpm_status', 'inactive');
                update_user_meta($userId, 'wpm_block_filter', wpm_array_get($filter, 'name'));
                self::updateLastBlockTime($userId);
                update_user_meta($userId, 'wpm_blocks_count', intval(get_user_meta($userId, 'wpm_blocks_count', true)) + 1);

                $_filters = self::getFilters();
                $users = wpm_array_get($_filters[$id], 'users', array());
                $users[$userId] = time();
                $_filters[$id]['users'] = $users;

                self::updateFilters($_filters);
                break;
            }
        }

        return true;
    }

    public static function clearUserBlocks($userId)
    {
        $filters = self::getFilters();

        foreach ($filters as $id => $filter) {
            self::unblockUser($userId, $id);
        }

        update_user_meta($userId, 'wpm_status', 'active');
        delete_user_meta($userId, 'wpm_block_filter');
        self::updateLastBlockTime($userId);
    }

    public static function getFilters()
    {
        return get_option('wpm_stats_block_filters', array());
    }

    public static function updateFilters($filters)
    {
        update_option('wpm_stats_block_filters', $filters);
    }

    public static function unblockUser($userId, $filterId)
    {
        $filters = self::getFilters();

        if (isset($filters[$filterId]['users'][$userId])) {
            unset($filters[$filterId]['users'][$userId]);
            self::updateFilters($filters);
        }
        self::updateLastBlockTime($userId);
    }

    public static function removeFilter($id)
    {
        $filters = self::getFilters();

        if (isset($filters[$id])) {
            unset($filters[$id]);
        }

        self::updateFilters($filters);
    }

    public static function getPaginator($users, $page)
    {
        $perPage = self::getPerPage();
        $totalPages = ceil(count($users) / $perPage);

        return '<span class="displaying-num">' . count($users) . ' ' . __('пользователей', 'wpm') . '</span>
                <span class="pagination-links">
                    <a class="first-page disabled"
                       title="' . __('Перейти на первую страницу', 'wpm') . '"
                       data-page="1"
                       href="#">«</a>
                    <a class="prev-page" title="' . __('Перейти на предыдущую страницу', 'wpm') . '"
                       data-page="prev" 
                       href="#">‹</a>
                    <span class="paging-input">
                        <span data-current>' . $page . '</span> ' . __('из', 'wpm') . '
                        <span class="total-pages">' . $totalPages . '</span>
                    </span>
                    <a class="next-page" title="' . __('Перейти на следующую страницу', 'wpm') . '"
                       data-page="next"
                       href="#">›</a>
                    <a class="last-page" title="' . __('Перейти на последнюю страницу', 'wpm') . '"
                       data-page="' . $totalPages . '"
                       href="#">»</a>
                </span>';
    }

    public static function getPerPage()
    {
        return self::$perPage;
    }

    public static function sliceForPage($users, $page)
    {
        $start = ($page - 1) * self::getPerPage();

        return array_slice($users, $start, self::getPerPage(), true);
    }

    public static function updateLastBlockTime($userId)
    {
        global $wpdb;

        $time = wpm_array_get($wpdb->get_col("SELECT NOW();"), 0);
        update_user_meta($userId, 'wpm_last_block', $time);
    }
}