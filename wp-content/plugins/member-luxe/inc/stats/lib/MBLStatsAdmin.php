<?php

class MBLStatsAdmin
{
    public static function render()
    {
        global $wpdb;

        $loginTable = MBLStats::getTable();
        $usersTable = $wpdb->prefix . "users";

        $page = max(intval(wpm_array_get($_GET, 'paged', 1)), 1);
        $perPage = 100;
        $offset = ($page - 1) * $perPage;

        $limit = "LIMIT {$offset}, {$perPage}";
        $order = "ORDER BY unique_logins DESC, nb_logins DESC, u.user_login ASC";
        $where = '';
        $userWhere = '';
        $from = wpm_array_get($_GET, 'date_from');
        $to = wpm_array_get($_GET, 'date_to');
        $search = wpm_array_get($_GET, 's', '');

        if ($from) {
            $dateFrom = DateTime::createFromFormat('d.m.Y', $from);
            if ($dateFrom) {
                $where .= " AND lt.logged_in_at >= '" . $dateFrom->format('Y-m-d H:i:s') . "'";
            }
        }
        if ($to) {
            $dateTo = DateTime::createFromFormat('d.m.Y', $to);
            if ($dateTo) {
                $where .= " AND lt.logged_in_at <= '" . $dateTo->format('Y-m-d H:i:s') . "'";
            }
        }

        if ($search) {
            $term = sanitize_text_field($search);
            $userWhere .= sprintf(" AND (u.user_login LIKE '%%%s%%' OR u.user_email LIKE '%%%s%%' OR u.display_name LIKE '%%%s%%')", $term, $term, $term);

            $userId = wpm_array_get(MBLTermKeysQuery::findOne(array('key' => $term), array('user_id')), 'user_id');

            if ($userId) {
                $userWhere .= " OR u.ID = {$userId}";
            }
        }

        $nbSubSelect = "SELECT COUNT(lt.id) FROM {$loginTable} lt WHERE lt.user_id = u.ID{$where}";
        $uniqueNbSubSelect = "SELECT COUNT(DISTINCT(lt.ip)) FROM {$loginTable} lt WHERE lt.user_id = u.ID{$where}";
        $filter_options = '';
        $users = $wpdb->get_results("SELECT u.*, ({$nbSubSelect}) as nb_logins, ({$uniqueNbSubSelect}) as unique_logins FROM {$usersTable} u WHERE ({$nbSubSelect})>0{$userWhere} {$order} {$limit}", OBJECT);
        $items = array();
        $adminsNb = 0;
        foreach ($users AS $user) {
            if(wpm_is_admin(get_user_by('ID', $user->ID))) {
                $adminsNb++;
                continue;
            }
            if ($user->nb_logins) {
                $user->logins = $wpdb->get_results("SELECT * FROM {$loginTable} lt WHERE user_id={$user->ID}{$where}", OBJECT);
            } else {
                $user->logins = array();
            }

            $items[] = $user;
        }

        $total_records = wpm_array_get($wpdb->get_row("SELECT COUNT(u.ID) as nb FROM {$usersTable} u WHERE ({$nbSubSelect})>0{$userWhere}", ARRAY_A), 'nb', 0) - $adminsNb;
        $total_pages = ceil($total_records / $perPage);
        $base_url = admin_url('edit.php?post_type=wpm-page&page=wpm-login-stats');
        $current_url = $base_url . $filter_options . '&paged=' . $page;

        if ($page == 1) {
            $prev_link = '';
        } else {
            $prev_link = $base_url . $filter_options . '&paged=' . ($page - 1);
        }
        if ($page == $total_pages) {
            $next_link = '';
        } else {
            $next_link = $base_url . $filter_options . '&paged=' . ($page + 1);
        }

        $first_link = $base_url . $filter_options . '&paged=1';
        $last_link = $base_url . $filter_options . '&paged=' . $total_pages;

        include(WP_PLUGIN_DIR . '/member-luxe/templates/admin/stats.php');
    }


    public static function getUserLogins($userId)
    {
        global $wpdb;

        $loginTable = MBLStats::getTable();

        return $wpdb->get_results("SELECT * FROM {$loginTable} lt WHERE user_id={$userId}", OBJECT);
    }
}