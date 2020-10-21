<?php
require_once(dirname(__FILE__) . '/lib/MBLStats.php');
require_once(dirname(__FILE__) . '/lib/MBLStatsAdmin.php');
require_once(dirname(__FILE__) . '/lib/MBLStatsDetector.php');
require_once(dirname(__FILE__) . '/lib/MBLStatsBlocker.php');


add_action('after_setup_theme', 'wpm_stats_after_setup_theme');
function wpm_stats_after_setup_theme()
{
    MBLStats::updateLastSeen();
}

add_action('wp_login', 'wpm_stats_user_login');
function wpm_stats_user_login($user_login)
{
    $user = get_user_by('login', $user_login);
    MBLStats::saveUserLogin($user);
    MBLStatsBlocker::checkLogin($user);
}

add_action('wp_logout', 'wpm_stats_user_logout');
function wpm_stats_user_logout()
{
    MBLStats::saveUserLogout();
}

function wpm_stats_admin()
{
    MBLStatsAdmin::render();
}

function wpm_stats_get_device_type($device)
{
    $deviceTypes = array(
        'desktop'               => __('ПК', 'wpm'),
        'smartphone'            => __('Смартфон', 'wpm'),
        'tablet'                => __('Планшет', 'wpm'),
        'feature phone'         => __('Телефон', 'wpm'),
        'console'               => __('Консоль', 'wpm'),
        'tv'                    => __('ТВ', 'wpm'),
        'car browser'           => __('Браузер автомобиля', 'wpm'),
        'smart display'         => __('Смарт ТВ', 'wpm'),
        'camera'                => __('Фотоаппарат', 'wpm'),
        'portable media player' => __('Медиаплеер', 'wpm'),
        'phablet'               => __('Фаблет', 'wpm')
    );

    return isset($deviceTypes[$device]) ? $deviceTypes[$device] : '';
}

add_action('wp_ajax_wpm_add_block_filter_action', 'wpm_add_block_filter_action');
add_action('wp_ajax_nopriv_wpm_add_block_filter_action', 'wpm_add_block_filter_action');
function wpm_add_block_filter_action() {
    $name = htmlentities(strip_tags(wpm_array_get($_POST, 'filter.name', '')));
    $interval = htmlentities(strip_tags(wpm_array_get($_POST, 'filter.time', '')));
    $entries = intval(wpm_array_get($_POST, 'filter.entries', ''));
    $id = wpm_array_get($_POST, 'filter.id', '');
    if($id !== '') {
        $id = htmlentities(strip_tags($id));
    } else {
        $id = null;
    }

    $result = MBLStatsBlocker::updateBlock($name, $interval, $entries, $id);

    echo json_encode($result);
    die();
}

add_action('wp_ajax_wpm_remove_block_filter_action', 'wpm_remove_block_filter_action');
add_action('wp_ajax_nopriv_wpm_remove_block_filter_action', 'wpm_remove_block_filter_action');
function wpm_remove_block_filter_action() {
    $id = wpm_array_get($_POST, 'id', '');
    if($id !== '') {
        $id = htmlentities(strip_tags($id));
    } else {
        return false;
    }


    MBLStatsBlocker::removeFilter($id);

    die();
}

add_action('wp_ajax_wpm_get_auto_blocking_users', 'wpm_get_auto_blocking_users');
add_action('wp_ajax_nopriv_wpm_get_auto_blocking_users', 'wpm_get_auto_blocking_users');
function wpm_get_auto_blocking_users() {
    $id = wpm_array_get($_POST, 'id');
    $filter = wpm_array_get(MBLStatsBlocker::getFilters(), $id);
    $current_url = wpm_array_get($_POST, 'url');
    $page = wpm_array_get($_POST, 'page', 1);

    wpm_render_partial(
        'auto-blocking-users',
        'admin',
        array(
            'filter' => $filter,
            'id' => $id,
            'current_url' => $current_url,
            'page' => $page
        )
    );
    die();
}

function wpm_is_excluded_from_block($userId)
{
    return in_array($userId, get_option('wpm_stats_block_excludes', array()));
}

add_action('wp_ajax_wpm_stats_exclude', 'wpm_stats_exclude');

function wpm_stats_exclude()
{
    $userId = wpm_array_get($_POST, 'user_id');

    if(wpm_is_admin() && $userId) {
        $excludes = get_option('wpm_stats_block_excludes', array());

        if (!wpm_is_excluded_from_block($userId)) {
            $excludes[$userId] = $userId;
            $excludes = array_unique($excludes);
            MBLStatsBlocker::clearUserBlocks($userId);
        } else {
            unset($excludes[$userId]);
        }

        update_option('wpm_stats_block_excludes', $excludes);

        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false));
    }

    die();
}