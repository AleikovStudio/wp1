<?php

function wpm_send_mails_page()
{
    require_once('send_mails_page.php');
}

function wpm_replace_sender_templates($text, $sender_name)
{
    $text = preg_replace('/%FROM_NAME%/', wpm_preg_quote($sender_name), $text);
    return $text;
}

function wpm_preg_quote($str)
{
    return preg_replace('/(\$|\\\\)(?=\d)/', '\\\\\1', $str);
}

function wpm_replace_blog_templates($text)
{
    $blog_url = get_option('home');
    $blog_name = get_option('blogname');

    $text = preg_replace('/%BLOG_URL%/', wpm_preg_quote($blog_url), $text);
    $text = preg_replace('/%BLOG_NAME%/', wpm_preg_quote($blog_name), $text);
    return $text;
}

function wpm_get_all_levels($parent_id = 0, $nested_level = 0)
{
    $taxonomies = array(
        'wpm-levels'
    );

    $args = array(
        'orderby'           => 'name',
        'order'             => 'ASC',
        'hide_empty'        => false,
        'exclude'           => array(),
        'exclude_tree'      => array(),
        'include'           => array(),
        'number'            => '',
        'fields'            => 'all',
        'slug'              => '',
        'parent'            => $parent_id,
        'hierarchical'      => true,
        'child_of'          => 0,
        'get'               => '',
        'name__like'        => '',
        'description__like' => '',
        'pad_counts'        => false,
        'offset'            => '',
        'search'            => '',
        'cache_domain'      => 'core'
    );

    $levels = get_terms($taxonomies, $args);

    foreach ($levels AS $level) {
        $level->children = wpm_get_all_levels($level->term_id, $nested_level + 1);
        $level->nested_level = $nested_level;
    }
    return $levels;
}

function wpm_get_levels_options_for_emails($send_targets, $levels = null)
{
    if ($levels === null) {
        $levels = wpm_get_all_levels();
    }

    $html = '';

    foreach ($levels AS $level) {
        $html .= sprintf(
            '<option value="%d" %s>%s%s</option>',
            $level->term_id,
            (in_array($level->term_id, $send_targets) ? ' selected="yes"' : ''),
            ($level->nested_level ? implode('', array_fill(0, $level->nested_level, '&nbsp;&nbsp;')) : ''),
            $level->name
        );
        $html .= wpm_get_levels_options_for_emails($send_targets, $level->children);
    }

    return $html;
}

function wpm_get_term_units($data) {
    $units = array_key_exists('units', $data) ? $data['units'] : 'months';

    return $data['duration'] . '_' . $units;
}

function wpm_get_term_keys_options_for_emails($levels = null)
{
    if ($levels === null) {
        $levels = get_terms('wpm-levels', array());
    }

    $html = '';

    foreach ($levels AS $level) {
        $term_keys = wpm_get_term_keys($level->term_id);

        if (is_array($term_keys) && !empty($term_keys)) {
            $keys_by_period = array_count_values(array_map('wpm_get_term_units', $term_keys));

            foreach ($keys_by_period as $key => $value) {
                list($duration, $units) = explode('_', $key);
                $result[$key]['new'] = 0;
                $result[$key]['used'] = 0;

                foreach ($term_keys as $item) {
                    $status = $item['status'];

                    if(isset($item['sent']) && $item['sent']) {
                        $status = 'used';
                    }

                    if ($item['duration'] == $duration && wpm_mail_is_units_equal($item, $units)) {
                        $result[$key][$status]++;
                    }
                }
                $units_msg = $units == 'days' ? 'дн.' : 'мес.';

                $html .= sprintf(
                    '<option value="%d_%s" data-main="%d">%d %s - %d</option>',
                    $level->term_id,
                    $key,
                    $level->term_id,
                    $duration,
                    $units_msg,
                    $result[$key]["new"]
                );
            }
        }
    }

    return $html;
}

function wpm_mail_is_units_equal($key, $cur_units)
{
    $units = array_key_exists('units', $key) ? $key['units'] : 'months';

    return $cur_units == $units;
}

function wpm_get_users_by_levels(array $levels = array())
{
    $levelIds = array();

    foreach ($levels AS $level) {
        $levelIds[] = $level;
        $child = wpm_all_categories($level);
        if (!empty($child)) {
            $levelIds = array_merge($levelIds, $child);
        }
    }

    $levelIds = array_unique($levelIds);

    $termKeys = MBLTermKeysQuery::find('WHERE `key_type`=\'wpm_term_keys\' AND `term_id` IN (' . implode(',', $levelIds) . ')');
    $users = array();

    foreach ($termKeys AS $termKey) {
        if ($termKey['status'] == 'used' && time() < strtotime($termKey['date_end']) && !$termKey['is_banned']) {
            $user = get_userdata($termKey['user_id']);
            if ($user && wpm_get_user_status($termKey['user_id']) == 'active') {
                $users[] = $user->user_email;
            }
        }
    }

    return $users;
}

function wpm_get_term_keys_to_send($key)
{
    $result = array();
    $error = false;

    if ($key != '') {
        list($term_id, $duration, $units) = explode('_', $key);

        foreach (wpm_get_term_keys($term_id) AS $v) {
            $_units = array_key_exists('units', $v) ? $v['units'] : 'months';
            $isValid = $v['status'] == 'new'
                && (!isset($v['sent']) || !$v['sent'])
                && $v['duration'] == $duration
                && $_units == $units;

            if($isValid) {
                $result[] = $v;
            }
        }

    } else {
        $error = 'Не выбраны коды доступа';
    }

    return array($result, $error);
}

function wpm_send_mail($recipients = array(), $subject = '', $message = '', $sender_name = '', $sender_email = '', $term_keys = array(), $term_id = null, $attachments = array())
{
    $used_term_keys = array();
    $mailer = new MBLMailer();

    $mailer->setRecipients($recipients)
        ->setSubject($subject)
        ->setMessage($message)
        ->setSenderName($sender_name)
        ->setSenderEmail($sender_email)
        ->setAttachments($attachments);

    if (count($term_keys)) {
        $mailer->setCallback(function ($html) use (&$term_keys, &$used_term_keys) {
            $code = array_shift($term_keys);
            $html = preg_replace('/\[pin_code\]/', $code['key'], $html);
            $code['sent'] = 1;
            $used_term_keys[$code['key']] = $code;

            return $html;
        });
    }

    $result = $mailer->send();

    if (count($used_term_keys) && !is_null($term_id)) {
        wpm_set_term_keys($term_id, $used_term_keys);
    }

    return $result;
}

/**
 * @param WP_Error $error
 */
function wpm_mail_failed($error)
{
    wpm_mail_write_log($error->get_error_message());
}

function wpm_mail_write_log($message)
{
    $logDir = WP_PLUGIN_DIR . '/member-luxe/log';
    $logFile = $logDir . '/mail.error.log';

    if (!file_exists($logDir)) {
        mkdir($logDir);
    }

    if (is_dir($logDir) && is_writable($logDir)) {
        file_put_contents($logFile, $message . "\n", FILE_APPEND);
    }
}

function wpm_mail_content_type ()
{
    return "text/html";
}
