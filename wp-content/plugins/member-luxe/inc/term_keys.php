<?php

function wpm_is_pin_code_page()
{
    global $wp_query;

    return $wp_query->query_vars
           && (isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'wpm-page')
           && (isset($wp_query->query_vars['wpm-page']) && $wp_query->query_vars['wpm-page'] == 'pin-code')
           && wpm_is_pin_code_page_enabled();
}

function wpm_is_pin_code_page_enabled()
{
    $main_options = get_option('wpm_main_options');

    return isset($main_options['pincode_page'])
           && (isset($main_options['pincode_page']['lvl']) && $main_options['pincode_page']['lvl'] !== '')
           && (isset($main_options['pincode_page']['term_key']) && $main_options['pincode_page']['term_key'] !== '');
}

function wpm_is_pin_code_page_lvl($term_id)
{
    $main_options = get_option('wpm_main_options');

    return isset($main_options['pincode_page'])
           && isset($main_options['pincode_page']['lvl'])
           && $main_options['pincode_page']['lvl'] == $term_id;
}

function wpm_is_pin_code_page_term_key($key)
{
    $main_options = get_option('wpm_main_options');

    return isset($main_options['pincode_page'])
           && isset($main_options['pincode_page']['term_key'])
           && $main_options['pincode_page']['term_key'] == $key;
}

function wpm_tk_get_term_units($data)
{
    $units = array_key_exists('units', $data) ? $data['units'] : 'months';

    return $data['duration'] . '_' . $units;
}

function wpm_get_term_keys_options_for_pin_code_page($levels = null)
{
    if ($levels === null) {
        $levels = get_terms('wpm-levels', array());
    }

    $html = '';

    $termIds = array();

    foreach ($levels as $level) {
        $termIds[] = $level->term_id;
    }

    $keys = MBLTermKeysQuery::getKeysCountByPeriods($termIds);

    foreach ($keys as $key) {
        $units = wpm_array_get($key, 'units', 'months');
        $units_msg = $units == 'days' ? 'дн.' : 'мес.';
        $termId = wpm_array_get($key, 'term_id');

        $duration = wpm_array_get($key, 'duration');
        $html .= sprintf(
            '<option value="%d_%s_%s" data-main="%d" %s>%d %s - %d</option>',
            $termId,
            $duration,
            $units,
            $termId,
            (wpm_is_pin_code_page_term_key($termId . '_' . $duration . '_' . $units) ? 'selected="selected"' : ''),
            $duration,
            $units_msg,
            wpm_array_get($key, 'nb')
        );
    }

    return $html;
}

function wpm_get_available_pin_code()
{
    $key = wpm_get_option('pincode_page.term_key');

    list($term_id, $duration, $units) = explode('_', $key);

    $code = false;

    $termKey = MBLTermKeysQuery::findOne(array(
        'sent' => 0,
        'status' => 'new',
        'term_id' => $term_id,
        'units' => $units,
        'duration' => $duration,
        'key_type' => 'wpm_term_keys'
    ));

    if($termKey) {
        $code = $termKey['key'];
        $termKey['sent'] = 1;

        MBLTermKeysQuery::updateKey($termKey);
    }

    return $code;
}

function wpm_get_pin_code_page_url()
{
    $virtualPost = new stdClass();
    $virtualPost->post_type = 'wpm-page';
    $virtualPost->post_status = 'publish';
    $virtualPost->post_title = 'Получение пин-кода';
    $virtualPost->ID = 1;

    $link = get_permalink($virtualPost);

    return preg_match('/\?/', $link)
        ? $link . '=pin-code'
        : $link . 'pin-code';
}

function wpm_get_pin_code()
{
    $result = array();

    if (wpm_is_pin_code_page_enabled()) {
        $userWpmPc = get_user_option('wpm_pc');
        if (isset($_SESSION['wpm_pc']) && $_SESSION['wpm_pc'] && $_SESSION['wpm_pc'] != '') {
            $result['success'] = true;
            $result['code'] = $_SESSION['wpm_pc'];
        } elseif (is_user_logged_in() && $userWpmPc && $userWpmPc != '') {
            $result['success'] = true;
            $result['code'] = $userWpmPc;
        } else {
            $code = wpm_get_available_pin_code();
            if($code !== false) {
                $result['success'] = true;
                $result['code'] = $code;
                $_SESSION['wpm_pc'] = $code;
                if(is_user_logged_in()) {
                    update_user_option(get_current_user_id(), 'wpm_pc', $code);
                }
            } else {
                $result['error'] = 'Нет свободных бесплатных пин-кодов';
            }
        }
    } else {
        $result['error'] = 'Страница раздачи пин-кодов отключена';
    }

    echo json_encode($result);
    die();
}

add_action('wp_ajax_wpm_get_pin_code_action', 'wpm_get_pin_code');
add_action('wp_ajax_nopriv_wpm_get_pin_code_action', 'wpm_get_pin_code');

function wpm_get_term_keys($term_id, $key_name = 'wpm_term_keys', $fields = null)
{
    return MBLTermKeysQuery::find(array('term_id' => $term_id, 'key_type' => $key_name), $fields);
}

function wpm_get_term_key($key, $fields = null)
{
    return MBLTermKeysQuery::findOne(array('key' => trim($key)), $fields);
}

function wpm_get_term_keys_old($term_id, $key_name = 'wpm_term_keys')
{
    global $wpdb;
    $options_table = $wpdb->prefix . "options";

    $term_keys = get_option("{$key_name}_{$term_id}");

    if ($term_keys === false) {
        $term_keys = array();
    }

    $query = "SELECT option_value FROM {$options_table} WHERE option_name LIKE '{$key_name}\_add\_" . intval($term_id) . "\_%'";

    foreach ($wpdb->get_col($query) AS $value) {
        $val = maybe_unserialize($value);
        if (is_array($val)) {
            foreach ($val AS $v) {
                $term_keys[] = $v;
            }
        }
    }

    return $term_keys;
}

function wpm_set_term_keys($term_id, $keys, $key_name = 'wpm_term_keys', $replace = false)
{
    global $wpdb;

    $termKeysTable = $wpdb->prefix . "memberlux_term_keys";
    $commit = true;
    $wpdb->query('START TRANSACTION');

    if ($replace) {
        $ids = wpm_array_pluck($keys, 'id');
        $removeIds = array_diff(wpm_array_pluck(wpm_get_term_keys($term_id, $key_name, array('id')), 'id'), $ids);

        if (!empty($removeIds)) {
            $deleteSQL = "DELETE FROM {$termKeysTable} WHERE `id` IN (%s)";
            foreach (array_chunk($removeIds, 100) AS $removeChunk) {
                if ($wpdb->query(sprintf($deleteSQL, implode(',', $removeChunk))) === false) {
                    $commit = false;
                };
            }
        }
    }

    foreach ($keys AS $key) {
        $key['term_id'] = $term_id;
        $key['key_type'] = $key_name;
        if (MBLTermKeysQuery::updateKey($key) === false) {
            $commit = false;
        }
    }

    if($commit) {
        $wpdb->query('COMMIT');
    } else {
        $wpdb->query('ROLLBACK');
    }

    return $commit;
}

function wpm_set_term_keys_old($term_id, $keys, $key_name = 'wpm_term_keys')
{
    wpm_delete_term_keys_old($term_id, $key_name);

    $i = 0;

    foreach (array_chunk((array)$keys, 1000) AS $chunk) {
        if (!$i) {
            update_option("{$key_name}_" . intval($term_id), $chunk);
        } else {
            update_option("{$key_name}_add_" . intval($term_id) . "_" . $i, $chunk);
        }

        $i++;
    }

    return true;
}

function wpm_delete_term_keys($term_id, $key_name = 'wpm_term_keys')
{
    return MBLTermKeysQuery::delete(array('term_id' => $term_id, 'key_type' => $key_name));
}

function wpm_delete_term_keys_old($term_id, $key_name = 'wpm_term_keys')
{
    global $wpdb;
    $options_table = $wpdb->prefix . "options";

    delete_option("{$key_name}_{$term_id}");

    $query = "SELECT option_name FROM {$options_table} WHERE option_name LIKE '{$key_name}\_add\_" . intval($term_id) . "\_%'";

    foreach ($wpdb->get_col($query) AS $option) {
        delete_option($option);
    }
}

add_action('wp_ajax_wpm_migrate_term_keys_action', 'wpm_migrate_term_keys');

function wpm_migrate_term_keys()
{
    wpm_migrate_term_keys_schema();
    wpm_migrate_term_keys_values();

    $stats = get_option('wpm_term_key_migrate_073_stats');

    $total = array_sum(wpm_array_pluck($stats, 'total'));
    $migrated = array_sum(wpm_array_pluck($stats, 'migrated'));

    $progress = $total > 0
        ? (($migrated / $total) * 100)
        : 100;

    $result = array(
        'progress' => round($progress, 2)
    );

    if($progress == 100) {
        update_option('wpm_has_to_update_term_keys', 0);
        $result = array('done' => true);
    }

    echo json_encode($result);
    die();
}

function wpm_migrate_term_keys_schema()
{
    MBLTermKeysQuery::migrate();
}

function wpm_migrate_term_keys_values()
{
    global $wpdb, $EZSQL_ERROR;

    wpm_migrate_log("wpm_migrate_term_keys_values::start");

    $stats = get_option('wpm_term_key_migrate_073_stats');
    $options_table = $wpdb->prefix . "options";

    if (!$stats) {
        $stats = array(
            'wpm_term_keys' => array(
                'total' => intval($wpdb->get_var("SELECT COUNT(*) FROM {$options_table} WHERE option_name LIKE 'wpm_term_keys%'")),
                'migrated' => 0
            ),
            'wpm_keys_basket' => array(
                'total' => intval($wpdb->get_var("SELECT COUNT(*) FROM {$options_table} WHERE option_name LIKE 'wpm_keys_basket%'")),
                'migrated' => 0
            ),
        );
    }

    wpm_migrate_log("wpm_migrate_term_keys_values::migrate");

    foreach ($stats AS $keyName => $stat) {
        wpm_migrate_log($keyName . " - total: " . $stat['total'] . ", migrated: " . $stat['migrated']);
        if ($stat['total'] > $stat['migrated']) {
            $migrated = 0;
            $limit = 1;
            $offset = intval($stat['migrated']);

            $query = "SELECT option_name, option_value FROM {$options_table} WHERE option_name LIKE '{$keyName}%' LIMIT {$limit} OFFSET {$offset}";

            foreach ($wpdb->get_results($query, ARRAY_A) AS $res) {
                if ($res && isset($res['option_name']) && isset($res['option_value'])) {
                    $term_id = wpm_get_term_id_from_option_name($res['option_name'], $keyName);
                    $term_keys = maybe_unserialize($res['option_value']);
                    wpm_migrate_log("Term Id: {$term_id}, keys " . count($term_keys));
                    $update = null;

                    if(!$term_id) {
                        ++$migrated;
                    } elseif ($term_id && is_array($term_keys) && false !== $update = wpm_set_term_keys($term_id, $term_keys, $keyName)) {
                        ++$migrated;
                    } elseif ($update === false) {
                        $errorKey = max(count($EZSQL_ERROR) - 1, 0);
                        $error = wpm_array_get($EZSQL_ERROR, $errorKey . '.error_str', '');
                        $query = wpm_array_get($EZSQL_ERROR, $errorKey . '.query', '');
                        wpm_migrate_log('Set term keys ERROR: ' . $error . '; QUERY: ' . $query);
                    } else {
                        ++$migrated;
                    }
                }
            }
            wpm_migrate_log($keyName . " migrated: " . $migrated);
            $stats[$keyName]['migrated'] += $migrated;
        }
    }

    update_option('wpm_term_key_migrate_073_stats', $stats);
}

function wpm_migrate_log($message)
{
    $logDir = WP_PLUGIN_DIR . '/member-luxe/log';
    $logFile = $logDir . '/migration.log';

    if (!file_exists($logDir)) {
        mkdir($logDir);
    }

    if (is_dir($logDir) && is_writable($logDir)) {
        file_put_contents($logFile, $message . "\n", FILE_APPEND);
    }
}

function wpm_get_term_id_from_option_name($optionName, $key_name = 'wpm_term_keys')
{
    $term_id = false;

    if(preg_match("/{$key_name}(?:_add)?_([^_]+)/i",$optionName, $matches)) {
        if (isset($matches[1])) {
            $term_id = intval($matches[1]);
        }
    }

    return $term_id;
}

function wpm_get_terms($taxonomy, $args = array())
{
    if(version_compare(get_bloginfo('version'), '4.5', '>=')) {
        $args += array('taxonomy' => $taxonomy);
        return get_terms($args);
    } else {
        return get_terms($taxonomy, $args);
    }
}

add_action('wp_ajax_wpm_move_term_keys_action', 'wpm_move_term_keys');

function wpm_move_term_keys()
{
    $result = array();
    $termFrom = intval(wpm_array_get($_POST, 'term_from'));
    $durationFrom = intval(wpm_array_get($_POST, 'duration_from'));
    $unitsFrom = wpm_array_get($_POST, 'units_from');

    if(!$termFrom || ! $durationFrom || !in_array($unitsFrom, MBLTermKeysQuery::$units)) {
        return false;
    }

    $termTo = intval(wpm_array_get($_POST, 'term_to'));
    $durationTo = intval(wpm_array_get($_POST, 'duration_to'));
    $unitsTo = wpm_array_get($_POST, 'units_to');

    if(!$termTo || ! $durationTo || !in_array($unitsTo, MBLTermKeysQuery::$units)) {
        return false;
    }

    MBLTermKeysQuery::moveKeys($termFrom, $durationFrom, $unitsFrom, $termTo, $durationTo, $unitsTo);

    $result['html'] = wpm_get_keys_html_list(wpm_get_term_keys($termFrom), $termFrom);

    echo json_encode($result);
    die();
}
