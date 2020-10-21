<?php

/**
 * WPM register form
 */
function wpm_register_form()
{
    wpm_render_partial('register-form');
}


/**
 * Ajax create new User
 */

function wpm_ajax_register_user()
{
    $error = false;
    $registered = false;
    $message = '';
    $form = array();

    foreach ($_POST['fields'] as $item) {
        $form[$item['name']] = trim($item['value']);
    }

    if (!array_key_exists('name', $form)) {
        $form['name'] = '';
    }

    if (!array_key_exists('surname', $form)) {
        $form['surname'] = '';
    }

    if (!array_key_exists('custom1', $form)) {
        $form['custom1'] = '';
    }
    if (!array_key_exists('custom2', $form)) {
        $form['custom2'] = '';
    }
    if (!array_key_exists('custom3', $form)) {
        $form['custom3'] = '';
    }

    if (!array_key_exists('patronymic', $form)) {
        $form['patronymic'] = '';
    }

    if (!array_key_exists('phone', $form)) {
        $form['phone'] = '';
    }

    $index = wpm_search_key_id($form['code']);

    // check if user key exist
    if ($index == null) {
        // if not exist
        $error = true;
        $message .= (__('Неверный ключ', 'mbl') . '<br>');

        wpm_registration_result($message, $registered);

    } else if($index['key_info']['status'] == 'used') {
        $message .= __('Этот код доступа уже используется', 'mbl');
        $error = true;
        wpm_registration_result($message, $registered);
    }

    if (!validate_username($form['login'])) {
        $message .= __('Некорректный логин. Для логина разрешены только буквы латинского алфавита и цифры', 'mbl');
        wpm_registration_result($message, $registered);
    }


    // check if username exist
    if (username_exists($form['login'])) {
        $message .= __('Этот логин уже используется', 'mbl');
        wpm_registration_result($message, $registered);
    }
    // check if email exist
    if (email_exists($form['email'])) {
        $message .= __('Этот email уже используется', 'mbl');
        wpm_registration_result($message, $registered);
    }

    if ($error) {
        wpm_registration_result($message, $registered);
    } else {

        $user_id = wp_insert_user(
            array(
                'user_login' => $form['login'],
                'user_pass'  => $form['pass'],
                'user_email' => $form['email'],
                'first_name' => wpm_array_get($form, 'first_name'),
                'last_name'  => wpm_array_get($form, 'last_name'),
                'role'       => 'customer'
            )
        );

        $registered = wpm_register_user(array(
            'user_id' => $user_id,
            'user_data' => $form,
            'index' => $index,
            'send_email' => true
        ));

        if ($registered) {
            $message = __('Спасибо за регистрацию!', 'mbl') . '<br>' . __('Сообщение с подтверждением отправлено на указанный адрес', 'mbl');
            wpm_registration_result($message, $registered);
        }
    }
    //On success
}

function wpm_register_user_hook($user_id)
{
    $form = array(
        'login' => $_POST['user_login'],
        'pass' => $_POST['pass'],
        'email' => $_POST['email'],
        'first_name' => $_POST['first_name'],
        'surname' => $_POST['last_name'],
        'custom1' => $_POST['custom1'],
        'custom2' => $_POST['custom2'],
        'custom3' => $_POST['custom3'],
        'phone' => '',
        'name' => '',
        'patronymic' => '',
    );

    wpm_register_user(array(
        'user_id' => $user_id,
        'user_data' => $form,
        'index' => '',
        'send_email' => true
    ));;
}

function wpm_register_user($args)
{
    $registered = false;

    if (!is_wp_error($args['user_id'])) {

        if($args['send_email']){
            wpm_send_registration_email('admin', $args['user_data']);
            wpm_send_registration_email('user', $args['user_data']);
        }

        // update user meta

        update_user_meta($args['user_id'], 'surname', $args['user_data']['surname']);
        if(isset($args['user_data']['custom1'])){
            update_user_meta($args['user_id'], 'custom1', $args['user_data']['custom1']);
        }
        if(isset($args['user_data']['custom2'])){
            update_user_meta($args['user_id'], 'custom2', $args['user_data']['custom2']);
        }
        if(isset($args['user_data']['custom3'])){
            update_user_meta($args['user_id'], 'custom3', $args['user_data']['custom3']);
        }

        if(isset($args['user_data']['phone'])){
            update_user_meta($args['user_id'], 'phone', $args['user_data']['phone']);
        }

        if (!empty($args['index'])) {
            $duration = $args['index']['key_info']['duration'];
            $units = array_key_exists('units', $args['index']['key_info']) ? $args['index']['key_info']['units'] : 'months';
            $date_start = date('d-m-Y', time());
            $date_end = date('d-m-Y', strtotime("+$duration " . $units));

            if(isset($args['user_data']['code'])) {
                $termKeyData = array(
                    'user_id' => $args['user_id'],
                    'status'     => 'used',
                    'date_start' => $date_start,
                    'date_end'   => $date_end,
                    'key_type'   => ($args['index']['is_deleted'] ? 'wpm_keys_basket' : 'wpm_term_keys') 
                );
                MBLTermKeysQuery::update($termKeyData, array('key' => $args['user_data']['code']));
            }
        }

        mbl_subscription_user_register($args['user_id']);

        $registered = true;
    }

    return $registered;
}

//add_action('wp_ajax_wpm_ajax_register_user_action', 'wpm_ajax_register_user'); // ajax for logged in users
add_action('wp_ajax_nopriv_wpm_ajax_register_user_action', 'wpm_ajax_register_user'); // ajax for not logged in users


/**/

function wpm_send_registration_email($receiver, $form )
{
    $user_name = (isset($form['first_name'])) ? $form['first_name'] : '';
    $user_login = $form['login'];
    $user_email = $form['email'];
    $user_pass = $form['pass'];

    add_filter('wp_mail_content_type', 'wpm_register_set_content_type');
    $main_options = get_option('wpm_main_options');

    if ($receiver == 'user') {
        $start_page = '<a href="' . get_permalink($main_options['home_id']) . '">' . get_permalink($main_options['home_id']) . '</a>';
        MBLMail::fromDB('registration', $user_email, compact('user_name', 'user_login', 'user_pass', 'start_page'));
    } elseif ($receiver == 'admin') {
        MBLMail::registration(get_option('admin_email'), compact('user_login', 'user_email'));
    }
}

/**/


function wpm_registration_result($message, $registered)
{
    echo json_encode(array(
        'message' => $message,
        'registered' => $registered
    ));
    die();
}


/**
 *
 */
function wpm_ajax_login_form()
{
    wpm_render_partial('login-form');
}

function wpm_ajax_login_init()
{
    // Enable the user with no privileges to run ajax_login() in AJAX
    add_action('wp_ajax_nopriv_wpm_ajaxlogin', 'wpm_ajax_login');
}

// Execute the action only if the user isn't logged in
add_action('init', 'wpm_ajax_login_init');

function wpm_ajax_login()
{

    // First check the nonce, if it fails the function will break
    check_ajax_referer('wpm-ajax-login-nonce', 'security');

    // Nonce is checked, get the POST data and sign user on
    $info = array();
    $info['user_login'] = $_POST['username'];
    $info['user_password'] = $_POST['password'];
    if (wpm_array_get($_POST, 'remember')) {
        $info['remember'] = true;
    }

    $user_signon = wp_signon($info, false);
    if (is_wp_error($user_signon)) {
        $message = wpm_array_get($user_signon->errors, 'wpm_inactive.0', __('Логин или пароль неправильные. Попробуйте ввести еще раз, возможно включен CapsLock.', 'mbl'));
        echo json_encode(array('loggedin' => false, 'message' => $message));
    } else {
        echo json_encode(array('loggedin' => true, 'message' => __('Вход выполнен, переадресация...', 'mbl')));
    }
    die();
}

function wpm_disable_default_avatars( $value ) {
    return false;
//    return IS_PROFILE_PAGE ? false : $value;
}
add_filter( 'option_show_avatars', 'wpm_disable_default_avatars' );

/**
 *
 */
add_action('show_user_profile', 'wpm_show_extra_profile_fields');
add_action('edit_user_profile', 'wpm_show_extra_profile_fields');

function wpm_show_extra_profile_fields($user)
{
    global $wpdb;
    //if (!in_array('customer', $user->roles)) return;
    wp_enqueue_media(); // Include Wordpress Media Library

    $main_options = get_option('wpm_main_options');

    $terms_table = $wpdb->prefix . "terms";
    $term_taxonomy_table = $wpdb->prefix . "term_taxonomy";
    $autotraining_terms = $wpdb->get_results("SELECT a.*, b.count, b.parent
                                         FROM " . $terms_table . " AS a
                                         JOIN " . $term_taxonomy_table . " AS b ON a.term_id = b.term_id
                                         WHERE b.taxonomy='wpm-category';", OBJECT);

    $autotrainings = array();

    if (count($autotraining_terms)) {
        foreach ($autotraining_terms as $autotraining) {
            if (wpm_is_autotraining($autotraining->term_id)) {
                $autotrainings[] = $autotraining;
            }
        }
    }


    ?>
    <script>
        jQuery(function ($) {
            $('.add-new-key').click(function () {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: ajaxurl,
                    data: {
                        action: "wpm_add_key_to_user_action",
                        key: $('#user_key').val(),
                        user_id: "<?php echo $user->ID; ?>"
                    },
                    success: function (data) {

                        $('.result').html(data.message);
                        if (!data.error) setTimeout(function () {
                            location.reload();
                        }, 1000);

                    },
                    error: function (errorThrown) {
                        //console.log(errorThrown);
                    }
                });
            });
            $('.add-new-auto-training-access').click(function () {
                var tr = $(this).closest('tr'),
                    term_id = tr.find('[name="auto_training_access"]').val(),
                    level = tr.find('[name="auto_training_access_number"]').val();

                tr.closest('table').css({opacity:'0.5'});

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: ajaxurl,
                    data: {
                        action: "wpm_add_auto_training_access",
                        term_id: term_id,
                        level: level,
                        user_id: "<?php echo $user->ID; ?>"
                    },
                    success: function (data) {
                        if (!data.error) {
                            tr.find('.access-result').html(data.result);
                            tr.closest('table').css({opacity:'1'});
                        }
                    },
                    error: function (errorThrown) {
                    }
                });
            });

            $('#wpm_stats_block_exclude').on('click', function () {
                $.post(
                    ajaxurl,
                    {
                        action  : 'wpm_stats_exclude',
                        user_id : <?php echo $user->ID; ?>
                    },
                    function (result) {
                        if (result.success) {
                            window.location.reload();
                        }
                    },
                    "json"
                );

                return false;
            });

            // Upload media file ====================================

            var wpm_file_frame;
            var image_id = '';
            $('.wpm-media-upload-button').live('click', function (event) {
                image_id = $(this).attr('data-id');

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (wpm_file_frame) {
                    wpm_file_frame.open();
                    return;
                }

                // Create the media frame.
                wpm_file_frame = wp.media.frames.downloadable_file = wp.media({
                    title: '<?php _e('Выберите файл', 'wpm'); ?>',
                    button: {
                        text: '<?php _e('Использовать изображение', 'wpm'); ?>'
                    },
                    multiple: false
                });

                // When an image is selected, run a callback.
                wpm_file_frame.on('select', function () {
                    var attachment = wpm_file_frame.state().get('selection').first().toJSON();

                    $('#' + image_id).val(attachment.id);
                    $('input#wpm_' + image_id).val(attachment.sizes.thumbnail.url);
                    $('#wpm-' + image_id + '-preview').attr('src', attachment.sizes.thumbnail.url).show();
                    $('#delete-wpm-' + image_id).show();
                    $('.wpm-crop-media-button[data-id="' + image_id +'"]').show();
                });
                // Finally, open the modal.
                wpm_file_frame.open();
            });
            $('.wpm-delete-media-button').on('click', function () {
                image_id = $(this).attr('data-id');

                $('#avatar').val('');
                $('input#wpm_' + image_id).val('');
                $('#delete-wpm-' + image_id).hide();
                $('#wpm-' + image_id + '-preview').hide();
                $('.wpm-crop-media-button[data-id="' + image_id +'"]').hide();
            });

            // End upload media file ====================================
        });
    </script>

    <?php $blockedFilter = get_user_meta($user->ID, 'wpm_block_filter', true) ?>

    <?php if ($blockedFilter) : ?>
        <div id="wpm-blocked-reason">
            <?php _e('Заблокирован: фильтр', 'wpm'); ?> "<?php echo $blockedFilter; ?>" (x<?php echo get_user_meta($user->ID, 'wpm_blocks_count', true) ?: 1; ?>)
        </div>
    <?php endif; ?>

    <?php if (wpm_is_admin()) : ?>
        <h3><?php _e('Автоблокировка', 'wpm'); ?></h3>
        <?php if (!wpm_is_excluded_from_block($user->ID)) : ?>
            <a href="#" id="wpm_stats_block_exclude"><?php _e('Добавить в список исключений автоблокировки', 'wpm'); ?></a>
        <?php else : ?>
            <a href="#" id="wpm_stats_block_exclude"><?php _e('Удалить из списка исключений автоблокировки', 'wpm'); ?></a>
        <?php endif; ?>
        <br>
        <br>
    <?php endif; ?>

    <h3><?php _e('Дополнительная информация', 'wpm'); ?></h3>

    <?php wpm_render_partial('profile-avatar', 'admin', compact('user')) ?>

    <table class="form-table">
        <tr>
            <th><label for="surname">Отчество</label></th>
            <td>
                <input type="text" name="surname" id="surname"
                       value="<?php echo esc_attr(get_the_author_meta('surname', $user->ID)); ?>" class="regular-text"/><br/>
            </td>
        </tr>
        <tr>
            <th><label for="custom1"><?php echo $main_options['registration_form']['custom1_label'] ?></label></th>
            <td>
                <input type="text" name="custom1" id="custom1"
                       value="<?php echo esc_attr(get_the_author_meta('custom1', $user->ID)); ?>" class="regular-text"/><br/>
            </td>
        </tr>
        <tr>
            <th><label for="custom2"><?php echo $main_options['registration_form']['custom2_label'] ?></label></th>
            <td>
                <input type="text" name="custom2" id="custom2"
                       value="<?php echo esc_attr(get_the_author_meta('custom2', $user->ID)); ?>" class="regular-text"/><br/>
            </td>
        </tr>
        <tr>
            <th><label for="custom3"><?php echo $main_options['registration_form']['custom3_label'] ?></label></th>
            <td>
                <input type="text" name="custom3" id="custom3"
                       value="<?php echo esc_attr(get_the_author_meta('custom3', $user->ID)); ?>" class="regular-text"/><br/>
            </td>
        </tr>
    </table>

    <h3><?php _e('Код доступа', 'wpm'); ?><a id="activation"></a></h3>
    <table class="form-table">
        <tr>
            <th><label for="twitter">Ключи</label></th>
            <td>
                <p>
                    <span class="description"><?php _e('Добавить новый ключ', 'wpm'); ?></span><br>
                </p>

                <p>
                    <input type="text" id="user_key" value="" placeholder="Ваш ключ" class="regular-text">
                    <button type="button" class="button add-new-key"><?php _e('Добавить', 'wpm'); ?></button>
                    <span class="result"></span><br>
                </p>
                <p>
                    <span class="description"><?php _e('Ваши ключи', 'wpm'); ?></span><br>
                </p>
                <?php

                $html = wpm_user_keys($user, false, true);

                if (!empty($html)) echo '<ul class="user-keys-list">' . $html . '</ul>';

                ?>

                <script>
                    jQuery(document).ready(function () {
                        jQuery(document).on('click', '.remove-key', function () {
                            var elem = jQuery(this);
                            var key = elem.attr('data-key');

                            jQuery.ajax({
                                type: 'GET',
                                dataType: 'json',
                                url: ajaxurl,
                                data: {
                                    action: "wpm_move_key_to_ban_action",
                                    key: key,
                                    user: <?php echo $user->ID;?>
                                },
                                success: function (data) {
                                    if(!data.error){
                                        elem.parent('li').addClass('banned_key');
                                        location.reload();
                                    }
                                },
                                error: function (errorThrown) {
                                    //console.log(errorThrown);
                                }
                            });
                        });
                    });
                </script>
            </td>
        </tr>
    </table>
    <?php
    $user_ID = get_current_user_id();
    $user_data = get_userdata($user_ID);
    if (count(array_intersect(array('administrator', 'teacher', 'editor', 'manageruser'), $user_data->roles))) : ?>
    <table class="form-table">
            <tr>
                <th><label><?php _e('Настройки автотренингов', 'wpm'); ?></label></th>
                <td style="width:160px; vertical-align: baseline;">
                    <p>
                        <span class="description"><?php _e('Выбор автотренинга', 'wpm'); ?></span><br>
                    </p>
                    <p>
                        <select name="auto_training_access">
                            <?php foreach ($autotrainings AS $autotraining) : ?>
                                <option value="<?php echo $autotraining->term_id; ?>"><?php echo $autotraining->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>
                </td>
                <td>
                    <p>
                        <span class="description"><?php _e('Введите порядковый номер урока', 'wpm'); ?></span><br>
                    </p>
                    <p>
                        <input type="text" style="width: 40px" name="auto_training_access_number">
                        <button type="button" class="button add-new-auto-training-access"><?php _e('Сохранить', 'wpm'); ?></button>
                        <span class="access-result"></span><br>
                        <span class="description"><?php _e('Работает только один раз!', 'wpm'); ?></span><br>
                    </p>
                </td>
            </tr>
        </table>
    <?php endif; ?>
<?php
}

add_action('personal_options_update', 'wpm_save_extra_profile_fields');
add_action('edit_user_profile_update', 'wpm_save_extra_profile_fields');

function wpm_save_extra_profile_fields($user_id)
{

    if (!current_user_can('edit_user', $user_id))
        return false;

    update_user_meta($user_id, 'surname', $_POST['surname']);
    update_user_meta($user_id, 'custom1', $_POST['custom1']);
    update_user_meta($user_id, 'custom2', $_POST['custom2']);
    update_user_meta($user_id, 'custom3', $_POST['custom3']);

    update_user_meta($user_id, 'avatar', $_POST['avatar']);

}

/**
 *
 */
function wpm_add_contact_methods($profile_fields)
{

    /* $user = wp_get_current_user();
     if (!in_array('customer', $user->roles)) return;*/
    // Add new fields
    $profile_fields['phone'] = 'Телефон';
    return $profile_fields;
}

add_filter('user_contactmethods', 'wpm_add_contact_methods');

/**
 *
 */
add_action('admin_init', 'wpm_user_profile');
function wpm_user_profile()
{
    $user = wp_get_current_user();
    if (!in_array('customer', $user->roles)) return;

    //removes the `profile.php` admin color scheme options
    remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
    add_action('admin_head', 'wpm_hide_personal_options');
}

function wpm_hide_personal_options()
{
    echo "\n" . '<script type="text/javascript">jQuery(document).ready(function($) { $(\'form#your-profile > h3:first\').hide(); $(\'form#your-profile > table:first\').hide(); $(\'form#your-profile\').show(); });</script>' . "\n";
}


/**
 *
 */

function wpm_add_key_to_user()
{
    $user_id = $_POST['user_id'];
    $code = trim($_POST['key']);

    $user = wp_get_current_user();

    $result = array(
        'message' => '',
        'error' => false,
        'keys' => ''
    );

    $index = wpm_search_key_id($code);

    // check if user key exist
    if (empty($index)) {
        // if not exist
        $result['error'] = true;
        $result['message'] = __('Неверный ключ', 'mbl');
    } else {
        // if exist
        if ($index['key_info']['status'] == 'new') {
            wpm_update_user_key_dates($user_id, $code);
            $term_id = wpm_array_get($index, 'key_info.term_id');

            $result['message'] = __('Ключ добавлен', 'mbl');

            MBLSubscription::add($user_id, $term_id);
        } else {
            // if key is used
            $result['message'] = __('Этот ключ уже используется', 'mbl');
            $result['error'] = true;
        }
    }

    $result['keys'] = wpm_user_keys($user, $is_table = true, false);

    echo json_encode($result);
    die();
}

add_action('wp_ajax_wpm_add_key_to_user_action', 'wpm_add_key_to_user');

function wpm_add_auto_training_access() {
    $user_id = intval(trim($_POST['user_id']));
    $term_id = intval(trim($_POST['term_id']));
    $level = intval(trim($_POST['level']));

    $training_access = get_user_meta($user_id, 'training_access', true);
    $training_access = (empty($training_access) && !is_array($training_access)) ? array() : $training_access;

    $new_training_access = array();

    foreach ($training_access AS $training_access_item) {
        if($training_access_item['term_id'] != $term_id) {
            $new_training_access[] = $training_access_item;
        }
    }

    array_push($new_training_access, array('level' => $level, 'term_id' => $term_id));
    update_user_meta($user_id, 'training_access', $new_training_access);

    echo json_encode(array('result' =>  __('Уровень автотренинга обновлен', 'wpm')));
    die();
}


add_action('wp_ajax_wpm_add_auto_training_access', 'wpm_add_auto_training_access');

function wpm_get_user_keys_info($user_id)
{
    $userKeys = MBLTermKeysQuery::findByUserId($user_id);

    return MBLTermKeysQuery::transformKeysToInfo($userKeys);
}

function wpm_update_user_key_dates($user_id, $code, $isBanned = false)
{
    $key = wpm_search_key_id($code);
    $term_id = $key['term_id'];
    $duration = $key['key_info']['duration'];
    $units = wpm_array_get($key, 'key_info.units', 'months');

    if ($key['is_deleted']) {
        $key['key_info']['date_start'] = date('d-m-Y', time());
        $key['key_info']['date_end'] = date('d-m-Y', strtotime("+$duration " . $units));
        $key['key_info']['status'] = 'used';

        MBLTermKeysQuery::updateKey($key['key_info']);
    } elseif($isBanned) {
        $date_start = strtotime($key['key_info']['date_start']);

        foreach (wpm_get_user_keys_info($user_id) AS $user_key) {
            $start = strtotime($user_key['key_info']['date_start']);
            if ($user_key['term_id'] == $term_id && $start > $date_start) {
                $user_key['key_info']['date_start'] = date('d-m-Y', strtotime("-$duration " . $units, $start));
                $user_key['key_info']['date_end'] = date('d-m-Y', strtotime("-$duration " . $units, strtotime($user_key['key_info']['date_end'])));
                MBLTermKeysQuery::updateKey($user_key['key_info']);
            }
        }
    } else {
        $date_start = time();

        foreach (wpm_get_user_keys_info($user_id) AS $user_key) {
            if ($user_key['term_id'] == $term_id && wpm_array_get($user_key, 'key_info.key_type') == 'wpm_term_keys' && !$user_key['is_deleted']) {
                $date_start = max($date_start, strtotime($user_key['key_info']['date_end']));
            }
        }

        $key['key_info']['date_registered'] = date('d-m-Y', time());
        $key['key_info']['date_start'] = date('d-m-Y', $date_start);
        $key['key_info']['date_end'] = date('d-m-Y', strtotime("+$duration " . $units, $date_start));
        $key['key_info']['status'] = 'used';
        $key['key_info']['user_id'] = $user_id;
        
        MBLTermKeysQuery::updateKey($key['key_info']);
    }
}

function wpm_move_key_to_ban ()
{
    $result = array(
        'message' => '',
        'error' => false
    );

    $key = MBLTermKeysQuery::findOne(array(
        'user_id' => intval($_GET['user']),
        'key' => trim($_GET['key'])
    ));

    if($key) {
        $key['is_banned'] = 1;

        MBLTermKeysQuery::updateKey($key);
    }

    echo json_encode($result);
    die();
}
add_action('wp_ajax_wpm_move_key_to_ban_action', 'wpm_move_key_to_ban');

function wpm_search_key_id($key)
{
    $data = MBLTermKeysQuery::findOne(array('key' => trim($key)));
    
    return MBLTermKeysQuery::transformKeyToInfo($data);
}

/*
* On login attempt check if user account is active
*/
function wpm_check_login($user, $username, $password)
{
    if(is_wp_error($user)) {
        return $user;
    }

    $meta = wpm_get_user_status($user->ID);

    if ($meta == 'inactive' && !in_array('administrator', $user->roles)) {
        return new WP_Error('wpm_inactive', 'Ваш аккаунт временно заблокирован.');
    } else {
        return $user;
    }
}
add_filter('authenticate', 'wpm_check_login', 100, 3);

/*
* Insert new items to the bulk actions dropdown on users.php
*/
function wpm_bulk_admin_footer()
{
    if(!current_user_can('activate_plugins')) {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('<option>').val('wpm_deactivate_account').text('Заблокировать').appendTo("select[name='action']");
            $('<option>').val('wpm_activate_account').text('Разблокировать').appendTo("select[name='action']");
        });
    </script>
<?php
}
add_action('admin_footer-users.php', 'wpm_bulk_admin_footer');

/*
* Perform bulk actions on form submit
*/
function wpm_users_bulk_action()
{
    if(!current_user_can('activate_plugins')) {
        return;
    }

    $wp_list_table = _get_list_table('WP_Users_List_Table');

    $action = $wp_list_table->current_action();
    $ret = wpm_array_get($_GET, 'ret', 'users');

    switch($action) {
        case 'wpm_deactivate_account':
            $user_ids = $_GET['users'];
            $deactivated = 0;
            foreach( $user_ids as $user_id ) {
                if(get_current_user_id() != $user_id){
                    update_user_meta($user_id, 'wpm_status', 'inactive');
                    $deactivated++;
                }
            }
            $sendback = add_query_arg( array('deactivated' => $deactivated ), $sendback );
            break;
        case 'wpm_activate_account':
            $user_ids = $_GET['users'];
            $activated = 0;
            foreach( $user_ids as $user_id ) {
                update_user_meta($user_id, 'wpm_status', 'active');
                delete_user_meta($user_id, 'wpm_block_filter');
                MBLStatsBlocker::clearUserBlocks($user_id);
                $activated++;
            }
            $sendback = add_query_arg( array('activated' => $activated ), $sendback );
            break;
        case 'wpm_activate_single_account':
            update_user_meta($_GET['user'], 'wpm_status', 'active');
            delete_user_meta($_GET['user'], 'wpm_block_filter');
            if($ret == 'users') {
                $sendback = add_query_arg( array('activated' => 1 ), $sendback );
            } else {
                $sendback = urldecode($ret);
            }
            $filterId = wpm_array_get($_GET, 'filter_id');
            if($filterId) {
                MBLStatsBlocker::unblockUser(intval($_GET['user']), $filterId);
            } else {
                MBLStatsBlocker::clearUserBlocks(intval($_GET['user']));
            }
            break;
        case 'wpm_deactivate_single_account':
            update_user_meta($_GET['user'], 'wpm_status', 'inactive');

            if($ret == 'users') {
                $sendback = add_query_arg( array('activated' => 1 ), $sendback );
            } else {
                $sendback = urldecode($ret);
            }
            break;
        default: return;
    }
    wp_redirect($sendback);
    exit();
}
add_action('load-users.php', 'wpm_users_bulk_action');

/*
* Display admin notice on activation and deactivation of accounts
*/
function custom_bulk_admin_notices()
{
    global $pagenow;

    if ($pagenow == 'users.php') {
        if (isset($_REQUEST['deactivated']) && (int) $_REQUEST['deactivated']) {
            $message = sprintf( _n( 'Пользовательский аккаунт заблокирован.', '%s пользовательских аккаунтов заблокировано.', $_REQUEST['deactivated'] ), number_format_i18n( $_REQUEST['deactivated'] ) );
            echo "<div class=\"updated\"><p>$message</p></div>";
        } elseif(isset($_REQUEST['activated']) && (int) $_REQUEST['activated']) {
            $message = sprintf( _n( 'Пользовательский аккаунт разблокирован.', '%s пользовательских аккаунтов разблокировано.', $_REQUEST['activated'] ), number_format_i18n( $_REQUEST['activated'] ) );
            echo "<div class=\"updated\"><p>$message</p></div>";
        }
    }
}
add_action('admin_notices', 'custom_bulk_admin_notices');

/*
* Display status of each account in the WordPress users table
*/
function wpm_add_user_id_column($columns)
{
    $columns['wpm_status'] = 'Состояние аккаунта';

    return $columns;
}
add_filter('manage_users_columns', 'wpm_add_user_id_column');


function wpm_show_user_id_column_content($value, $column_name, $user_id, $ret = 'users')
{
    $account_status = wpm_get_user_status($user_id);
    $user           = get_user_by('id', $user_id );

    if (!in_array('administrator', $user->roles) && 'wpm_status' == $column_name ) {
        return $account_status=='active'
            ? '<b style="color: #008000;">Активен</b> <br /><a href="' . admin_url('/users.php?action=wpm_deactivate_single_account&user=' . $user_id . '&ret=' . $ret) . '">Блокировать</a>'
            : '<b style="color: red;">Блокирован</b> <br /><a href="' . admin_url('/users.php?action=wpm_activate_single_account&user=' . $user_id . '&ret=' . $ret) . '">Снять блок</a>';
    }

    return $value;
}
add_action('manage_users_custom_column',  'wpm_show_user_id_column_content', 10, 3);

function wpm_pre_user_query($userQuery)
{
    if (!isset($_GET['s']) || trim($_GET['s']) == '' || stripos($_SERVER['REQUEST_URI'], 'users.php') === false) {
        return;
    }

    $term = sanitize_text_field(trim($_GET['s']));
    $userId = wpm_array_get(MBLTermKeysQuery::findOne(array('key' => $term), array('user_id')), 'user_id');

    if ($userId) {
        $extraSql = " OR ID = {$userId}";

        $addAfter = 'WHERE ';
        $addPosition = strpos($userQuery->query_where, $addAfter) + strlen($addAfter);

        $userQuery->query_where = substr($userQuery->query_where, 0, $addPosition) . '(' . substr($userQuery->query_where, $addPosition) . ')' . $extraSql;
    }
}
add_action('pre_user_query', 'wpm_pre_user_query', 100);