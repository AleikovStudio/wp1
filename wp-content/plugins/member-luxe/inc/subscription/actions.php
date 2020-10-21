<?php
add_action('wp_ajax_wpm_justclick_groups_select', 'wpm_justclick_groups_select');

function wpm_justclick_groups_select()
{
    $groups = MBLSubscription::direct('justclick', 'getAllGroups', array());
    $error = MBLSubscription::direct('justclick', 'getError');

    if (count($groups)) {
        $html = '<label>' . __('Группа контактов', 'wpm') . '<br>';
        $html .= '<select name="main_options[auto_subscriptions][justclick][rid]">'
            . '<option value=""></option>';
        foreach ($groups as $group) {
            $html .= '<option'
                . ' value="' . $group['rass_name'] . '"'
                . (wpm_option_is('auto_subscriptions.justclick.rid', $group['rass_name']) ? ' selected="selected"' : '')
                . '>' . $group['rass_title'] . '</option>';
        }
        $html .= '</select>';
        $html .= '</label>';
    } else {
        $html = sprintf('<span class="wpm_error">%s: %s</span>', __('Ошибка', 'wpm'), $error);
    }

    echo $html;
    die();
}

add_action('wp_ajax_wpm_justclick_groups_level_select', 'wpm_justclick_groups_level_select');

function wpm_justclick_groups_level_select()
{
    $termId = intval(wpm_array_get($_POST, 'term_id'));
    $html = '';

    if ($termId) {
        $options = get_option("taxonomy_term_{$termId}");
        $groups = MBLSubscription::direct('justclick', 'getAllGroups', array());
        $error = MBLSubscription::direct('justclick', 'getError');

        if (count($groups)) {
            $html .= '<label>' . __('Выбор группы', 'wpm') . '<br>';
            $html .= '<select name="term_meta[auto_subscriptions][justclick][rid]">'
                . '<option value=""></option>';
            foreach ($groups as $group) {
                $html .= '<option'
                    . ' value="' . $group['rass_name'] . '"'
                    . (wpm_array_get($options, 'auto_subscriptions.justclick.rid') == $group['rass_name'] ? ' selected="selected"' : '')
                    . '>' . $group['rass_title'] . '</option>';
            }
            $html .= '</select>';
            $html .= '</label>';
        } else {
            $html = sprintf('<span class="wpm_error">%s: %s</span>', __('Ошибка', 'wpm'), $error);
        }
    }

    echo $html;
    die();
}

add_action('wp_ajax_wpm_justclick_groups_del_level_select', 'wpm_justclick_groups_del_level_select');

function wpm_justclick_groups_del_level_select()
{
    $termId = intval(wpm_array_get($_POST, 'term_id'));
    $html = '';

    if ($termId) {
        $options = get_option("taxonomy_term_{$termId}");
        $groups = MBLSubscription::direct('justclick', 'getAllGroups', array());
        $error = MBLSubscription::direct('justclick', 'getError');

        if (count($groups)) {
            $del_rid = wpm_array_get($options, 'auto_subscriptions.justclick.del_rid', array());
            $html .= '<select name="term_meta[auto_subscriptions][justclick][del_rid][]" multiple="multiple" size="' . min(count($groups), 5) . '">';
            foreach ($groups as $group) {
                $isSelected = is_array($del_rid) && in_array($group['rass_name'], $del_rid);
                $html .= '<option'
                    . ' value="' . $group['rass_name'] . '"'
                    . ($isSelected ? ' selected="selected"' : '')
                    . '>' . $group['rass_title'] . '</option>';
            }
            $html .= '</select>';
        } else {
            $html = sprintf('<span class="wpm_error">%s: %s</span>', __('Ошибка', 'wpm'), $error);
        }
    }

    echo $html;
    die();
}

function wpm_levels_show_subscriptions()
{
    return (bool)count(wpm_array_filter(wpm_array_pluck(wpm_get_option('auto_subscriptions'), 'active'), 'on'));
}

//add_action('user_register', 'mbl_subscription_user_register', 100, 1);

function mbl_subscription_user_register($userId)
{
    $user = get_userdata($userId);

    if (in_array('customer', $user->roles)) {
        $levelId = wpm_array_get(MBLTermKeysQuery::getUserTermIds($userId), 0);
        if ($levelId) {
            MBLSubscription::add($userId, $levelId);
        }
    }
}

add_action('profile_update', 'mbl_subscription_user_update', 1, 1);

function mbl_subscription_user_update($userId)
{
    $user = get_userdata($userId);

    if (in_array('customer', $user->roles)) {
        MBLSubscription::update($userId);
    }
}