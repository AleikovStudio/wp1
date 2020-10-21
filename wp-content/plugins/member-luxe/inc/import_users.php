<?php
/**
 */


add_action('wp_ajax_wpm_import_users_action', 'wpm_ajax_import_users');

function wpm_ajax_import_users()
{

    $emails = $_POST['emails'];
    $term_id = $_POST['term_id'];
    $duration = $_POST['duration'];
    $units = $_POST['units'];

    $result = array(
        'message'     => '',
        'error'       => false,
        'emails'      => array(),
        'fails'       => array(),
        'count'       => '',
        'count_fails' => ''
    );

    foreach ($emails as $email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // valid address
            if (email_exists($email)) { // user with address already exists
                $result['fails'][] = array(
                    'message' => 'Емейл уже существует',
                    'email'   => $email,
                    'status'  => 'exist'
                );
            } else { // email is ok, now generate login and password
                $login = wpm_email_to_login($email);
                $pass = wp_generate_password();

                $user_data = array(
                    'user_login' => $login,
                    'user_pass'  => $pass,
                    'user_email' => $email,
                    'role'       => 'customer'
                );

                $user_id = wp_insert_user($user_data);

                if (!is_wp_error($user_id)) {

                    // generate code for user
                    $code = wpm_add_one_user_key($term_id, $duration, $units);

                    // check if code was generated
                    $index = wpm_search_key_id($code);

                    $form = array(
                        'login'      => $login,
                        'pass'       => $pass,
                        'email'      => $email,
                        'first_name' => '',
                        'surname'    => '',
                        'phone'      => '',
                        'name'       => '',
                        'patronymic' => '',
                        'custom1'       => '',
                        'custom2'       => '',
                        'custom3'       => ''
                    );
                    wpm_register_user(array(
                        'user_id' => $user_id,
                        'user_data' => $form,
                        'index' => $index,
                        'send_email' => false
                    ));

                    wpm_add_code_to_user($user_id, $code);

                    $result['emails'][] = array(
                        'email'   => $email,
                        'code'    => $code,
                        'message' => 'Зарегистрирован',
                        'user_id' => $user_id,
                        'status'  => 'added'
                    );

                    wpm_send_email_with_mass_registration($user_data, $term_id);

                } else {
                    $result['fails'][] = array(
                        'message' => 'Не удалось зарегистрировать',
                        'email'   => $email,
                        'status'  => 'not_added'

                    );
                }

            }

        } else {
            // invalid address
            $result['fails'][] = array(
                'message' => 'Некорректный емейл',
                'email'   => $email,
                'status'  => 'Пользователь не зарегистрирован'
            );
            break;
        }
    }

    $result['message'] = 'Пользователи успешно зарегистрированы!';
    $result['count'] = count($result['emails']);
    $result['count_fails'] = count($result['fails']);

    echo json_encode($result);
    die();

}

function wpm_ajax_user_registration_progress($count)
{
    $result = array(
        'progress' => $count
    );
    // echo json_encode($result);
    // die();
}

// Generate key from single user

function wpm_add_one_user_key($term_id, $duration, $units)
{
    $count = 1;
    $term_keys = wpm_get_term_keys($term_id);

    $new_keys = wpm_generate_keys(array('count' => $count, 'duration' => $duration, 'date_start' => '', 'date_end' => '', 'units' => $units));
    if (empty($term_keys)) $term_keys = array();
    $term_keys = array_merge($term_keys, $new_keys);
    $result['updated'] = wpm_set_term_keys($term_id, $term_keys);

    return $new_keys[0]['key'];
}

// Generate user's login from email

function wpm_email_to_login($email)
{
    $login = explode('@', $email);
    $login = $login[0];
    if (username_exists($login)) {
        $i = 1;
        while (username_exists($login . $i)) {
            $i++;
        }
        $login = $login . $i;
    }
    return $login;
}

// Parse emails

add_action('wp_ajax_wpm_parse_emails_action', 'wpm_parse_emails');

function wpm_parse_emails_and_check_users()
{
    $result = array(
        'emails'               => array(),
        'email_registered'     => array(),
        'email_not_registered' => array(),
        'registered'           => array(),
        'count_registered'     => '',
        'not_registered'       => array(),
        'count_not_registered' => ''
    );

    $str = $_POST['emails'];
    $emails = array();

    $res = preg_split("/[\s,]+/", $str);

    if ($res) {
        foreach ($res as $item) {
            // check if valid email
            if (filter_var($item, FILTER_VALIDATE_EMAIL) && !in_array($item, $emails)) {
                // if valid, add to array
                $emails[] = $item;
            }
        }
        $result['emails'] = $emails;
    }

    foreach ($emails as $email) {
        if (email_exists($email)) { // user with address already exists
            $result['registered'][] = array(
                'message' => 'Зарегистрирован',
                'email'   => $email,
                'status'  => 'registered'
            );
            $result['email_registered'][] = $email;

        } else {
            $result['not_registered'][] = array(
                'message' => 'Не зарегистрирован',
                'email'   => $email,
                'status'  => 'not_registered'
            );
            $result['email_not_registered'][] = $email;
        }
    }
    $result['count_registered'] = count($result['registered']);
    $result['count_not_registered'] = count($result['not_registered']);

    // return emails from string
    echo json_encode($result);
    die();
}

function wpm_add_code_to_user($user_id, $code = '')
{
    wpm_update_user_key_dates($user_id, $code);

    $levelId = MBLTermKeysQuery::getTermIdByKey($code);

    if($levelId) {
        MBLSubscription::add($user_id, $levelId);
    }
}

/**
 * Check if users exists
 */

add_action('wp_ajax_wpm_parse_emails_and_check_users_action', 'wpm_parse_emails_and_check_users');

function wpm_parse_emails()
{
    $str = $_POST['emails'];
    $emails = array();

    $res = preg_split("/[\s,]+/", $str);

    if ($res) {
        foreach ($res as $item) {
            if (filter_var($item, FILTER_VALIDATE_EMAIL) && !in_array($item, $emails)) {
                $emails[] = $item;
            }
        }
    }

    $result = array(
        'count'  => count($emails),
        'emails' => $emails
    );
    // return emails from string
    echo json_encode($result);
    die();
}




/**
 * Bulk adding keys to users
 */

add_action('wp_ajax_wpm_bulk_add_key_to_user_action', 'wp_ajax_wpm_bulk_add_key_to_user');
function wp_ajax_wpm_bulk_add_key_to_user()
{
    $emails = $_POST['emails'];
    $term_id = $_POST['term_id'];
    $duration = $_POST['duration'];
    $units = $_POST['units'];

    $result = array(
        'message'     => '',
        'error'       => false,
        'emails'      => array(),
        'fails'       => array(),
        'count'       => '',
        'count_fails' => ''
    );

   foreach ($emails as $email) {
        if (email_exists($email)) { // user with address already exists
            $user = get_user_by('email', $email);
            // generate code for user
            $code = wpm_add_one_user_key($term_id, $duration, $units);

            // check if code was generated

            wpm_add_code_to_user($user->ID, $code);
            $result['emails'][] = array(
                'email'   => $email,
                'code'    => $code,
                'message' => 'Добавлен',
                'user_id' => $user->ID,
                'status'  => 'added'
            );

            wpm_send_email_about_new_key($user, $code, $term_id);

        } else { // email is ok, now generate login and password

            $result['fails'][] = array(
                'message' => 'Нет пользователя с таким емейлом',
                'email'   => $email,
                'status'  => 'Пользователь не зарегистрирован'
            );
            break;
        }
    }

    $result['message'] = 'Ключи успешно добавлены!';
    $result['count'] = count($result['emails']);
    $result['count_fails'] = count($result['fails']);

    echo json_encode($result);
    die();

}

function wpm_send_email_about_new_key($user, $code, $term_id)
{
    $main_options = get_option('wpm_main_options');

    $term_meta = get_option("taxonomy_term_$term_id");
    $term = get_term($term_id, 'wpm-levels');

    $term_name = $term->name;
    $email = $user->user_email;
    $user_name = $user->first_name;
    $user_login = $user->user_login;
    $start_page = '<a href="'.get_permalink($main_options['home_id']).'">'.get_permalink($main_options['home_id']).'</a>';

    MBLMail::newKey(
        $email,
        wpm_array_get($term_meta, 'mass_keys_title'),
        wpm_array_get($term_meta, 'mass_keys_message'),
        compact('user_name', 'user_login', 'start_page', 'term_name', 'code')
    );
}

function wpm_send_email_with_mass_registration($user_data, $term_id)
{
    $main_options = get_option('wpm_main_options');

    $term_meta = get_option("taxonomy_term_$term_id");
    $term = get_term($term_id, 'wpm-levels');

    $user_login = $user_data['user_login'];
    $user_pass = $user_data['user_pass'];
    $term_name = $term->name;
    $message = null;
    $user_name = '';

    if(isset($user_data['user_name']) && !empty($user_data['user_name'])){
        $user_name = $user_data['user_name'];
    }

    $start_page = '<a href="' . get_permalink($main_options['home_id']) . '">' . get_permalink($main_options['home_id']) . '</a>';

    if(!empty($term_meta['mass_users_message'])) {
        $message = $term_meta['mass_users_message'];
    } elseif (!empty($main_options['letters']['registration']['content'])) {
        $message = stripslashes($main_options['letters']['registration']['content']);
    }

    MBLMail::massRegistration(
        $user_data['user_email'],
        wpm_array_get($term_meta, 'mass_users_title'),
        $message,
        compact('user_name', 'user_login', 'user_pass', 'start_page', 'term_name')
    );
}
