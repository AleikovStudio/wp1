<?php

function wpm_options()
{
    wp_enqueue_media();
    include_once('js/admin-js.php');

    //--------------

    include_once('js/wpm-admin-js.php');

    //--------------

    $isInterfaceUpdate = false;

    if (isset($_POST['main_options'])) {

        $isInterfaceUpdate = wpm_array_get($_POST, 'main_options.interface_version') != wpm_get_option('interface_version');

        $main_options = get_option('wpm_main_options');

        $new_options = $_POST['main_options'];

        if ($new_options['main']['opened'] == 'on') {
            $new_options['main']['opened'] = true;
        } else {
            $new_options['main']['opened'] = false;
        }
        if (!isset($new_options['header']['visible'])) {
            $new_options['header']['visible'] = 'off';
        }

        if ($new_options['make_home_start'] == 'on') {
            update_option('page_on_front', $new_options['home_id']);
            update_option('show_on_front', 'page');
        } else {
            $new_options['make_home_start'] = false;
            if($main_options['make_home_start']){
                update_option('page_on_front', '');
                update_option('show_on_front', 'posts');
            }
        }

        if (!array_key_exists('registration_form', $new_options)) {
            $new_options['registration_form'] = array(
                'name' => 'off',
                'surname' => 'off',
                'patronymic' => 'off',
                'phone' => 'off'
            );
        } else {
            if (!array_key_exists('name', $new_options['registration_form'])) {
                $new_options['registration_form']['name'] = 'off';
            }

            if (!array_key_exists('surname', $new_options['registration_form'])) {
                $new_options['registration_form']['surname'] = 'off';
            }

            if (!array_key_exists('patronymic', $new_options['registration_form'])) {
                $new_options['registration_form']['patronymic'] = 'off';
            }

            if (!array_key_exists('phone', $new_options['registration_form'])) {
                $new_options['registration_form']['phone'] = 'off';
            }

            if (!array_key_exists('custom1', $new_options['registration_form'])) {
                $new_options['registration_form']['custom1'] = 'off';
            }
            if (!array_key_exists('custom2', $new_options['registration_form'])) {
                $new_options['registration_form']['custom2'] = 'off';
            }
            if (!array_key_exists('custom3', $new_options['registration_form'])) {
                $new_options['registration_form']['custom3'] = 'off';
            }

        }


        if (isset($new_options['header']['content'])) {
            $new_options['header']['content'] = stripslashes(wp_filter_post_kses($new_options['header']['content']));
        }

        if (isset($new_options['footer']['content'])) {
            $new_options['footer']['content'] = stripslashes(wp_filter_post_kses($new_options['footer']['content']));
        }

        if (isset($new_options['login_content']['content'])) {
            $new_options['login_content']['content'] = stripslashes(wp_filter_post_kses($new_options['login_content']['content']));
        }

        if (isset($new_options['auto_subscriptions']['justclick']['user_rps_key'])) {
            $new_options['auto_subscriptions']['justclick']['user_rps_key'] = stripcslashes($new_options['auto_subscriptions']['justclick']['user_rps_key']);
        }

        if(isset($new_options['letters']['type'])) {
            switch($new_options['letters']['type']) {
                case 'mandrill':
                    $new_options['letters']['mandrill_is_on'] = 'on';
                    $new_options['letters']['ses_is_on'] = 'off';
                    break;
                case 'ses':
                    $new_options['letters']['ses_is_on'] = 'on';
                    $new_options['letters']['mandrill_is_on'] = 'off';
                    break;
                default:
                    $new_options['letters']['ses_is_on'] = 'off';
                    $new_options['letters']['mandrill_is_on'] = 'off';
                    break;
            }
        }
        $main_options = array_merge($main_options, $new_options);

        $default_main_options = get_option('wpm_main_options');

        $letters = array(
            'registration',
            'response_review',
            'response_status',
            'response_admin',
            'comment_subscription',
            'registration_to_admin'
        );

        foreach ($letters AS $letter) {
            if(empty($main_options['letters'][$letter]['title'])) {
                $main_options['letters'][$letter]['title'] = $default_main_options['letters'][$letter]['title'];
            }
            if(empty($main_options['letters'][$letter]['content'])) {
                $main_options['letters'][$letter]['content'] = $default_main_options['letters'][$letter]['content'];
            }
        }

        update_option('wpm_main_options', $main_options);
    }
    if(isset($_POST['translations']) && wpm_is_interface_2_0()) {
        MBLTranslator::saveTranslations(wpm_array_get($_POST, 'translations', array()));
    }
    if (isset($_POST['design_options']) && !$isInterfaceUpdate) {
        $design_options = get_option('wpm_design_options');
        $design_options = array_merge($design_options, $_POST['design_options']);
        update_option('wpm_design_options', $design_options);
    }

    $main_options = get_option('wpm_main_options');
    $design_options = get_option('wpm_design_options');


    if (!array_key_exists('name', $main_options['registration_form'])) {
        $main_options['registration_form']['name'] = 'off';
    }

    if (!array_key_exists('surname', $main_options['registration_form'])) {
        $main_options['registration_form']['surname'] = 'off';
    }

    if (!array_key_exists('patronymic', $main_options['registration_form'])) {
        $main_options['registration_form']['patronymic'] = 'off';
    }

    if (!array_key_exists('phone', $main_options['registration_form'])) {
        $main_options['registration_form']['phone'] = 'off';
    }

    if (!array_key_exists('custom1', $main_options['registration_form'])) {
        $main_options['registration_form']['custom1'] = 'off';
    }
    if (!array_key_exists('custom2', $main_options['registration_form'])) {
        $main_options['registration_form']['custom2'] = 'off';
    }
    if (!array_key_exists('custom3', $main_options['registration_form'])) {
        $main_options['registration_form']['custom3'] = 'off';
    }

    $headerKey = wpm_is_interface_2_0() ? 'header_bg' : 'headers';
    if (empty($main_options[$headerKey]['priority'])) {
        $main_options[$headerKey]['priority'] = 'default,pincodes';
    }

    wpm_render_partial('options', 'admin', compact('main_options', 'design_options'));
}


function wpm_ajax_save_options()
{
    $result = array(
        'status' => '',
        'message' => ''
    );
    //$result['message'] = $_POST['form_data'];


    $form_data = $_POST['form_data'];
    $options_name = $_POST['options_name'];
    $options = get_option($options_name);


    foreach ($form_data as $key => $value) {
        $path = explode('->', $value['name']);
        wpm_set_value($options, $path, $value['value']);
    }

    if (update_option($options_name, $options)) {
        $result['status'] = 'updated';
        $result['message'] = '<span class="success">' . __('Настройки сохранены', 'wpm') . '</span>';
    } else {
        $result['status'] = 'failed';
        $result['message'] = '<span class="faild">' . __('Настройки не обновились', 'wpm') . '</span>';
    }
    echo json_encode($result);
    //echo json_encode(array('status'=>'ok'));
    die();
}

add_action('wp_ajax_wpm_ajax_save_options_action', 'wpm_ajax_save_options'); // ajax for logged in users

/**
 * Sets an element of a multidimensional array from an array containing
 * the keys for each dimension.
 * @param array &$array The array to manipulate
 * @param array $path An array containing keys for each dimension [one][two]
 * @param mixed $value The value that is assigned to the element
 */
function wpm_set_value(&$array, $path, $value)
{
    $key = array_shift($path);
    if (empty($path)) {
        $array[$key] = stripslashes(wp_filter_post_kses(addslashes($value)));
    } else {
        if (!isset($array[$key]) || !is_array($array[$key])) {
            $array[$key] = array();
        }
        wpm_set_value($array[$key], $path, $value);
    }
}

//-------------

function wpm_get_levels_select()
{

    $taxonomies = array(
        'wpm-levels'
    );
    $args = array(
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
        'exclude' => array(),
        'exclude_tree' => array(),
        'include' => array(),
        'number' => '',
        'fields' => 'all',
        'slug' => '',
        'hierarchical' => true,
        'child_of' => 0,
        'get' => '',
        'name__like' => '',
        'description__like' => '',
        'pad_counts' => false,
        'offset' => '',
        'search' => '',
        'cache_domain' => 'core'
    );

    $terms = get_terms($taxonomies, $args);

    $html = '';
    foreach ($terms as $t) {
        $html .= '<option value="' . $t->term_id . '">' . $t->name . '</option>';
    }

    return $html;
}