<?php


function wpm_autotraining_page()
{
    global $wpdb;
    wp_enqueue_script('admin-comments');
    wpm_enqueue_style('homework_response_css', plugins_url('../css/homework-response.css', __FILE__));
    wpm_enqueue_style('fontawesome', plugins_url('../plugins/file-upload/css/fontawesome/css/font-awesome.min.css', __FILE__));
    wpm_enqueue_style('wpm-fancybox', plugins_url('/member-luxe/js/fancybox/jquery.fancybox.css'));
    $response_table = $wpdb->prefix . "memberlux_responses";
    $users_table = $wpdb->prefix . "users";

    $status = isset($_GET["status"]) ? $_GET["status"] : 'opened';
    $filter_options = "&status=$status";

    if (in_array($status, array('approved', 'accepted'))) {
        $condition = " response_status IN('approved','accepted') AND is_archived=0";
    } elseif($status == 'archive') {
        $condition = " is_archived=1";
    } else {
        $condition = " response_status ='{$status}' AND is_archived=0";
    }

    if (isset($_GET['m']) && !empty($_GET['m'])) {
        $condition .= " AND YEAR(response_date) = " . substr($_GET['m'], 0, 4) . " AND MONTH(response_date) = " . substr($_GET['m'], 4, 2);
    }

    if (isset($_GET['wpm-response-status']) && !empty($_GET['wpm-response-status'])) {
        $condition .= " AND response_status = '" . $_GET['wpm-response-status'] . "' ";
    }

    $join = " ";

    if (isset($_GET['wpm-category']) && !empty($_GET['wpm-category'])) {
        $category_slug = urldecode($_GET['wpm-category']);

        $posts = get_posts(array(
                'post_type'      => 'wpm-page',
                'posts_per_page' => -1,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'wpm-category',
                        'field'    => 'slug',
                        'terms'    => $category_slug)
                )
            )
        );
        $ids = array();
        if (count($posts)) {
            foreach ($posts as $post) {
                $ids[] = $post->ID;
            }
        }

        if(!empty($ids)) {
            $condition .= " AND post_id IN (" . implode(',', $ids) . ") ";
        }
    }

    if (isset($_GET['wpm-levels']) && !empty($_GET['wpm-levels'])) {
        $category_slug = urldecode($_GET['wpm-levels']);

        $posts = get_posts(array(
                'post_type'      => 'wpm-page',
                'posts_per_page' => -1,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'wpm-levels',
                        'field'    => 'slug',
                        'terms'    => $category_slug)
                )
            )
        );
        $ids = array();
        if (count($posts)) {
            foreach ($posts as $post) {
                $ids[] = $post->ID;
            }
        }

        if(!empty($ids)) {
            $condition .= " AND post_id IN (" . implode(',', $ids) . ") ";
        }

    }

    if (isset($_GET['s']) && !empty($_GET['s'])) {
        $join .= " JOIN $users_table AS b ON user_id=b.ID ";
        $s = $_GET['s'];
        $condition .= " AND ( b.user_login LIKE '%$s%')";
    }

    if (isset($_GET["paged"])) {
        $page = $_GET["paged"];
        if ($page < 1) $page = 1;
    } else {
        $page = 1;
    };

    $posts_per_page = 100;
    $start_from = ($page - 1) * $posts_per_page;


    $responses = $wpdb->get_results("SELECT *
                                     FROM $response_table
                                     $join
                                     WHERE $condition
                                     ORDER BY response_date DESC, response_status DESC
                                     LIMIT $start_from, $posts_per_page", OBJECT);

    $responses_count = $wpdb->get_results("SELECT COUNT(id)
                                           FROM $response_table
                                           $join
                                           WHERE $condition", ARRAY_A);

    $total_records = $responses_count[0]['COUNT(id)'];
    $total_pages = ceil($total_records / $posts_per_page);
    $base_url = admin_url('edit.php?post_type=wpm-page&page=wpm-autotraining');

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

    if(wpm_is_interface_2_0()) {
        include(WP_PLUGIN_DIR . '/member-luxe/templates/interface_2/admin/homework.php');
    } else {
        include(WP_PLUGIN_DIR . '/member-luxe/templates/admin/homework.php');
    }
}

function wpm_get_event_log($response_id, $status)
{
    global $wpdb;

    $date = '';

    if ($status != 'opened') {

        $response_log_table = $wpdb->prefix . "memberlux_response_log";

        $log =  $wpdb->get_row("SELECT * FROM `" . $response_log_table . "`
                                         WHERE response_id = " . $response_id . " AND
                                               event = '" . $status . "'
                                         ORDER BY id DESC;", OBJECT);

        if ($log) {
            $date = date('H:i d.m.Y', strtotime($log->created_at));
        }
    }

    return $date;
}

function wpm_homework_filters ()
{
    global $wpdb, $wp_locale;

    $response_table = $wpdb->prefix . "memberlux_responses";

    $months = $wpdb->get_results("SELECT DISTINCT YEAR( response_date ) AS year, MONTH( response_date ) AS month
                                  FROM $response_table
                                  ORDER BY response_date DESC, response_status DESC", OBJECT);

    $month_count = count( $months );

    if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
        return;

    $m = isset( $_GET['m'] ) ? (int) $_GET['m'] : 0;
    ?>
    <label for="filter-by-date" class="screen-reader-text"><?php _e( 'Filter by date' ); ?></label>
    <select name="m" id="filter-by-date" onchange="resetSearch()">
        <option<?php selected( $m, 0 ); ?> value="0"><?php _e( 'All dates' ); ?></option>
        <?php
        foreach ($months as $arc_row) {
            if ( 0 == $arc_row->year ) {
                continue;
            }

            $month = zeroise( $arc_row->month, 2 );
            $year = $arc_row->year;

            printf( "<option %s value='%s'>%s</option>\n",
                selected( $m, $year . $month, false ),
                esc_attr( $arc_row->year . $month ),
                sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
            );
        }
        ?>
    </select>
<?php

    $filters = array(
        'wpm-category',
        'wpm-levels'
    );

    foreach ($filters as $tax_slug) {
        $tax_obj  = get_taxonomy($tax_slug);
        $tax_name = $tax_obj->labels->name;
        $tax_name = mb_strtolower($tax_name);
        $terms    = get_terms($tax_slug);

        echo "<select name='$tax_slug' id='$tax_slug' class='postform' onchange='resetSearch()'>";
        echo "<option value=''>Все $tax_name</option>";
        foreach ($terms as $term) {
            echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .'</option>';
        }
        echo "</select>";
    }
}


//-------------
add_action('wp_ajax_wpm_add_response_action', 'wpm_add_response'); // ajax for logged in users
function wpm_add_response()
{
    global $wpdb;
    $response_table = $wpdb->prefix . "memberlux_responses";

    $add_to_response_log = false;

    $result = array(
        'message' => '',
        'error' => false,
        'homework' => array()
    );

    $user_id = get_current_user_id();

    $page_meta = get_post_meta($_POST['post_id'], '_wpm_page_meta', true);
    $confirmation_method = array_key_exists('confirmation_method', $page_meta) ? $page_meta['confirmation_method'] : 'auto';

    if (!empty($_POST['response_content'])) {
        $response = wpm_response($user_id, $_POST['post_id']);

        if (empty($response)){
            switch ($confirmation_method) {
                case 'manually':
                    $args = array(
                        'user_id' => $user_id,
                        'post_id' => $_POST['post_id'],
                        'response_content' => $_POST['response_content'],
                        'response_date' => current_time('mysql'),
                        'response_status' => 'opened',
                        'response_type' => $_POST['response_type']
                    );
                    break;
                case 'auto_with_shift':
                    $approval_shift = array_key_exists('homework_shift_value', $page_meta)
                        ? ($page_meta['homework_shift_value'] * 60 * 60)
                        : 0;
                    $approval_shift = date('Y-m-d H:i:s', (time() + $approval_shift));

                    $args = array(
                        'user_id' => $user_id,
                        'post_id' => $_POST['post_id'],
                        'response_content' => $_POST['response_content'],
                        'response_date' => current_time('mysql'),
                        'response_status' => 'accepted',
                        'response_type' => $_POST['response_type'],
                        'approval_date' => $approval_shift,
                    );

                    $add_to_response_log = true;

                    break;
                default:
                    $args = array(
                        'user_id' => $user_id,
                        'post_id' => $_POST['post_id'],
                        'response_content' => $_POST['response_content'],
                        'response_date' => current_time('mysql'),
                        'response_status' => 'accepted',
                        'response_type' => $_POST['response_type']
                    );
            }

            $row = $wpdb->insert($response_table, $args);
        } else {
//            $status = $confirmation_method=='manually' ? 'opened' : $response->response_status;
            $status = 'opened';

            $args = array(
                'response_content' => $_POST['response_content'],
                'response_date'    => current_time('mysql'),
                'response_status'  => $status
            );

            $where = array(
                'user_id' => $user_id,
                'post_id' => $_POST['post_id']
            );

            $row = $wpdb->update($response_table, $args, $where);
        }

        wpm_update_cat_autotraining_schedules($_POST['post_id'], $user_id, $confirmation_method);
        $result['homework'] = wpm_get_responses($user_id, $_POST['post_id'], $page_meta);
        if (isset($result['homework']['content'])) {
            $result['homework']['content'] .= UploadHandler::getHomeworkAttachmentsHtml($_POST['post_id'], $user_id);
        }

    }

    if($add_to_response_log) {
        $response = wpm_response($user_id, $_POST['post_id']);
        wpm_add_to_response_log($response->id, 'accepted');
    }

    wpm_alert_admin_by_email($_POST['response_content'], $_POST['post_id'], $user_id);

    if ($row === false) {
        $result['message'] = __('Произошла ошибка!', 'mbl');
        $result['error'] = true;
    } elseif ($row === 0) {
        $result['message'] = __('Ответ не сохранен!', 'mbl');
        $result['error'] = true;
    } elseif ($row > 0) {
        $result['message'] = __('Ответ отправлен!', 'mbl');
    }

    echo json_encode($result);
    die();
}

function wpm_alert_admin_by_email ($content, $post_id, $user_id)
{
    $attachments = UploadHandler::getHomeworkAttachments($post_id, $user_id);
    $files = array();
    $wp_upload_dir = wp_upload_dir();
    if (!empty($attachments['files'])) {
        foreach ($attachments['files'] AS $file) {
            $files[] = $wp_upload_dir['basedir'] . $file->path;
        }
    }

    $post = get_post($post_id);
    $user = get_user_by('id', $user_id);

    MBLMail::fromDB('response_admin', get_option('admin_email'), array(
        'material_name' => $post->post_title,
        'user_name' => $user->display_name,
        'user_email' => $user->user_email,
        'user_response' => trim($content),
        'admin_url' => admin_url('edit.php?post_type=wpm-page&page=wpm-autotraining'),
    ), $files);
}

function wpm_add_to_response_log($response_id, $event)
{
    global $wpdb;

    $response_log_table = $wpdb->prefix . "memberlux_response_log";

    $args = array(
        'response_id' => $response_id,
        'event' => $event,
        'created_at' => current_time('mysql')
    );

    $wpdb->insert($response_log_table, $args);
}

function wpm_update_cat_autotraining_schedules($post_id, $user_id, $confirmation_method, $force = false)
{
    $term_list = wp_get_post_terms($post_id, 'wpm-category', array("fields" => "ids"));

    if (count($term_list)) {
        foreach ($term_list as $term_id) {
            wpm_update_schedule($term_id, $user_id, $confirmation_method, $force);
        }
    }
}

function wpm_homework_info($post_id, $user_id, $page_meta)
{
    $response = wpm_get_responses($user_id, $post_id, $page_meta);

    if (!empty($response['content'])) {
        if (in_array($response['real_status'], array('accepted', 'approved'))) {
            return array(
                'done' => true,
                'time' => strtotime($response['date'])
            );
        }
    }

    return array(
        'done' => false
    );
}

function wpm_update_schedule($term_id, $user_id, $confirmation_method, $force = false)
{
    $previous_post_id = null;
    $is_postponed_due_to_homework  = false;
    $last_homework_completion_time = null;

    $user_cat_data = wpm_user_cat_data($term_id, $user_id);

    if ($user_cat_data['is_training_started']) {

        foreach ($user_cat_data['schedule'] as $post_id => $data) {
            if ($data['is_postponed_due_to_homework'] && ($confirmation_method != 'manually' || $force)) {

                if(!$previous_post_id) {
                    $previous_post    = get_previous_post(true);
                    $previous_post_id = $previous_post->ID;
                }

                $previous_page_meta   = get_post_meta($previous_post_id, '_wpm_page_meta', true);
                $is_prev_has_homework = (array_key_exists('is_homework', $previous_page_meta) && $previous_page_meta['is_homework']=='on') ? true : false;
                $prev_homework_info   = wpm_homework_info($previous_post_id, $user_id, $previous_page_meta);

                if ($is_prev_has_homework && $prev_homework_info['done']) {
                    $last_homework_completion_time = $prev_homework_info['time'];
                }

                if (($is_prev_has_homework && !$prev_homework_info['done']) || $is_postponed_due_to_homework) {
                    $is_postponed_due_to_homework = true;
                    $user_cat_data['schedule'][$post_id]['is_postponed_due_to_homework'] = $is_postponed_due_to_homework;
                } else {
                    if (!empty($last_homework_completion_time)) {
                        $release_date = $last_homework_completion_time + $user_cat_data['schedule'][$post_id]['shift'];
                    } else {
                        $release_date = time() + $user_cat_data['schedule'][$post_id]['shift'];
                    }

                    $user_cat_data['schedule'][$post_id]['is_first'] = false;
                    $user_cat_data['schedule'][$post_id]['release_date'] = $release_date;
                    $user_cat_data['schedule'][$post_id]['is_postponed_due_to_homework'] = false;
                }
            }
            $previous_post_id = $post_id;
        }

        update_user_meta($user_id, 'cat_data_' . $term_id . '_' . $user_id, $user_cat_data);
    }
}

//-------------
add_action('wp_ajax_wpm_add_response_review_action', 'wpm_add_response_review'); // ajax for logged in users
function wpm_add_response_review()
{
    global $wpdb;
    $userId = get_current_user_id();
    $content = wpm_array_get($_POST, 'review_content');
    $responseId = wpm_array_get($_POST, 'response_id');
    $table = $wpdb->prefix . "memberlux_response_review";
    $date = current_time('mysql');
    $lastUserId = intval(wpm_array_get($_POST, 'last_user_id'));

    if(intval($responseId)) {
        $update = $wpdb->query($wpdb->prepare(
            "INSERT INTO {$table} (response_id, user_id, review_content, review_date) VALUES (%d, %d, %s, %s);",
            $responseId,
            $userId,
            $content,
            $date
        ));

        if($update) {
            UploadHandler::saveHomeworkReviewAttachments($responseId, $wpdb->insert_id, $userId);
            $review = array(
                'id' => $wpdb->insert_id,
                'content' => $content,
                'user_id' => $userId,
                'date_object' => date_create($date)
            );

            wpm_render_partial('homework-review', 'base', compact('review', 'lastUserId'));
        }
    }

    die();
}

add_action('wp_ajax_wpm_update_response_action', 'wpm_update_response'); // ajax for logged in users
function wpm_update_response()
{
    global $wpdb;
    $response_table = $wpdb->prefix . "memberlux_responses";
    $response_review_table = $wpdb->prefix . "memberlux_response_review";

    $result = array(
        'message' => '',
        'error'   => false
    );

    $response_id = $_POST['response_id'];
    $response_status = $_POST['response_status'];
    $approval_date = current_time('mysql');

    $is_edit = false;
    $reply_id = null;

    if ($response_status == 'reply') {

        if (isset($_POST['edit_review'])) {
            $edit_review = intval($_POST['edit_review']);
            $update = $wpdb->query($wpdb->prepare("UPDATE " . $response_review_table . "
                                                   SET review_content=%s
                                                   WHERE id=%d;", $_POST['content'], $edit_review));
            $is_edit = true;

            $result['reply_id'] = $edit_review;
        } else {
            $user_id = get_current_user_id();
            $update = $wpdb->query($wpdb->prepare("INSERT INTO " . $response_review_table . " (response_id, user_id, review_content, review_date)
                                                   VALUES (%d, %d, %s, %s);", $response_id, $user_id, $_POST['content'], $approval_date));
            $result['reply_id'] = $wpdb->insert_id;
        }
    } elseif($response_status == 'delete') {
        $update = $wpdb->delete($response_table, array('ID' => $response_id));
    } elseif($response_status == 'archive') {
        $update = $wpdb->query("UPDATE {$response_table} SET is_archived=1 WHERE id='{$response_id}';");
    } elseif($response_status == 'unarchive') {
        $update = $wpdb->query("UPDATE {$response_table} SET is_archived=0 WHERE id='{$response_id}';");
    } else {
        $update = $wpdb->query("UPDATE {$response_table}
                                SET response_status = '{$response_status}', approval_date = '{$approval_date}'
                                WHERE id = '{$response_id}';");
        wpm_add_to_response_log($response_id, $response_status);
    }

    if ($update === false) {
        $result['message'] = 'Произошла ошибка!';
        $result['error'] = true;
    } elseif ($update === 0) {
        $result['message'] = 'Не сохранен!';
        $result['error'] = true;
    } elseif ($update > 0) {
        $result['message'] = 'Сохранено!';
        if($response_status == 'reply') {

            $result['html'] = getReviewHtml($_POST['content'], $result['reply_id'], date('Y-m-d H:i:s'), get_current_user_id());

            if (!$is_edit) {
                mail_response_review($response_id, $_POST['content']);
            }
        } else {
            $condition = "id = " . intval($response_id);
            $response =  $wpdb->get_row("SELECT * FROM {$response_table} WHERE {$condition}", OBJECT);

            if ($response_status == 'approved') {
                wpm_update_cat_autotraining_schedules($response->post_id, $response->user_id, 'manually', true);
            }

            wpm_alert_user_about_homework ($response_status, $response);
        }
    }
    echo json_encode($result);
    die();
}

function wpm_alert_user_about_homework ($status, $response)
{
    $post = get_post($response->post_id);
    $user = get_user_by('id', $response->user_id);

    switch($status) {
        case 'approved':
            $statusText = __('Правильно', 'wpm');
            break;
        case 'rejected':
            $statusText = __('Неправильно', 'wpm');
            break;
        case 'accepted':
            $statusText = __('Принят', 'wpm');
            break;
        case 'opened':
        default:
            $statusText = __('Проверяем', 'wpm');
            break;
    }

    MBLMail::fromDB('response_status', $user->user_email, array(
        'material_name' => $post->post_title,
        'status' => $statusText,
        'material_url' => get_permalink($post),
    ));
}

function mail_response_review($response_id, $review_content)
{
    global $wpdb;
    get_currentuserinfo();
    $response_table = $wpdb->prefix . "memberlux_responses";

    $condition = "id = " . intval($response_id);
    $response =  $wpdb->get_row("SELECT * FROM {$response_table} WHERE {$condition}", OBJECT);
    $user = get_user_by('id', $response->user_id);
    $response_content = trim($response->response_content);
    $post = get_post($response->post_id);

    MBLMail::fromDB('response_review', $user->user_email, array(
        'material_name' => $post->post_title,
        'user_response' => $response_content,
        'response_review' => $review_content,
        'material_url' => get_permalink($post),
    ));
}

function getReviewHtml($review_content, $reply_id, $date, $userId)
{
    $attachmentsHtml = '';
    if (wpm_is_interface_2_0()) {
        $attachments = UploadHandler::getHomeworkReviewAttachments($reply_id, $userId);
        if (!empty($attachments['files'])) {
            $attachmentsHtml = wpm_render_partial('review-attachments', 'admin', array('files' => $attachments['files']), true);
        }
    }

    return '<div class="admin-response-review" data-review-id="' . $reply_id . '">' .
                wpautop(stripslashes($review_content)) .
                $attachmentsHtml .
                '<small class="review-datetime">Добавлен: ' . date('H:i d.m.Y', strtotime($date)) . '</small>' .
           '</div>';
}

function getResponseReviews($response_id)
{
    global $wpdb;
    $response_review_table = $wpdb->prefix . "memberlux_response_review";
    $condition = "response_id = " . intval($response_id);
    $main_options = get_option('wpm_main_options');
    $order =  array_key_exists('comments_order', $main_options['main']) ? $main_options['main']['comments_order'] : 'asc';

    return $wpdb->get_results("SELECT *
    FROM $response_review_table
    WHERE $condition
    ORDER BY review_date {$order}", OBJECT);
}

function wpm_response($user_id, $post_id)
{
    global $wpdb;

    $response_table = $wpdb->prefix . "memberlux_responses";

    return $wpdb->get_row("SELECT * FROM `" . $response_table . "`
                                             WHERE user_id = " . $user_id . " AND
                                                   post_id = " . $post_id . "
                                             ORDER BY response_date;", OBJECT);
}

function wpm_response_log($response_id)
{
    global $wpdb;

    $response_log_table = $wpdb->prefix . "memberlux_response_log";

    return $wpdb->get_row("SELECT * FROM `" . $response_log_table . "`
                                             WHERE response_id = " . $response_id . ";", OBJECT);
}

function wpm_get_responses($user_id, $post_id, $page_meta)
{
    $data = array(
        'id' => null,
        'date' => '',
        'status' => '',
        'content' => '',
        'reviews' => array(),
        'date_object' => null
    );

    $response = wpm_response($user_id, $post_id);

    if (!empty($response)) {
        $data['id'] = $response->id;
        $data['date'] = date_format(date_create($response->response_date), 'H:i d/m/Y');
        $data['date_str'] = date_format(date_create($response->response_date), 'd.m.Y');
        $data['time_str'] = date_format(date_create($response->response_date), 'H:i');
        $data['date_object'] = date_create($response->response_date);
        $data['status'] = wpm_accepted_with_shift($response, $page_meta) ? 'opened' : $response->response_status;
        $data['real_status'] = $response->response_status;
        $data['content'] = apply_filters('the_content', stripslashes($response->response_content));
        $data['status_msg'] = wpm_get_response_status_message($response->response_status);
        $data['status_class'] = wpm_get_response_status_class($response->response_status);
        $data['reviews'] = wpm_get_response_reviews($response->id);
    }

    return $data;
}

function wpm_get_response_reviews ($response_id)
{
    global $wpdb;

    $main_options = get_option('wpm_main_options');

    $order =  array_key_exists('comments_order', $main_options['main']) ? $main_options['main']['comments_order'] : 'asc';

    $data = array();

    $review_table = $wpdb->prefix . "memberlux_response_review";

    $reviews = $wpdb->get_results("SELECT * FROM `" . $review_table . "`
                                            WHERE `response_id` = " . $response_id . "
                                            ORDER BY review_date " . $order . ";", OBJECT);

    if(count($reviews)) {
        foreach ($reviews as $review) {
            $data[] = array(
                'id' => $review->id,
                'date' => date_format(date_create($review->review_date), 'H:i d/m/Y'),
                'content' => $review->review_content,
                'user_id' => $review->user_id,
                'date_object' => date_create($review->review_date)
            );
        }
    }

    return $data;
}

function wpm_accepted_with_shift($response, $page_meta)
{
    if ($response->response_status == 'accepted' && array_key_exists('confirmation_method', $page_meta) && $page_meta['confirmation_method'] == 'auto_with_shift') {
        $shift = array_key_exists('homework_shift_value', $page_meta) ? $page_meta['homework_shift_value'] : 0;

        if ($shift > 0) {
            $approval_date = strtotime($response->approval_date) + $shift;

            if ($approval_date > time()) {
                return true;
            }
        }
    }

    return false;
}

function wpm_get_response_status_message ($status)
{
    if (wpm_is_interface_2_0()) {
        switch ($status) {
            case 'accepted':
            case 'approved':
                $message = __('выполнено', 'mbl');
                break;
            case 'rejected':
                $message = __('неправильно', 'mbl');
                break;
            case 'opened':
                $message = __('проверяется', 'mbl');
                break;
            default :
                $message = __('не начат', 'mbl');
        }
    } else {
        switch ($status) {
            case 'opened':
                $message = 'Ожидает проверки';
                break;
            case 'approved':
                $message = 'Правильно!';
                break;
            case 'rejected':
                $message = 'Неправильно';
                break;
            case 'accepted':
                $message = 'Ответ принят';
                break;
            default:
                $message = 'Ответ принят';
        }
    }


    return $message;
}

function wpm_get_response_status_class($status)
{
    switch ($status) {
        case 'accepted':
        case 'approved':
            $class = 'done';
            break;
        case 'rejected':
            $class = 'not-right';
            break;
        case 'opened':
            $class = 'checking';
            break;
        default :
            $class = '';
    }

    return $class;
}

function wpm_get_response_counter($status) {
    global $wpdb;
    $response_table = $wpdb->prefix . "memberlux_responses";

    if(in_array($status, array('approved', 'accepted'))) {
        $condition = "response_status IN ('approved', 'accepted') AND is_archived=0";
    } elseif($status == 'archive') {
        $condition = "is_archived=1";
    } else {
        $condition = "response_status = '{$status}' AND is_archived=0";
    }

    $responses_count = $wpdb->get_results("SELECT COUNT(id) FROM {$response_table} WHERE {$condition}", ARRAY_A);

    return $responses_count[0]['COUNT(id)'];
}

add_action('wp_ajax_wpm_ajax_pass_material', 'wpm_ajax_pass_material'); // ajax for logged in users

function wpm_ajax_pass_material()
{
    MBLPage::setIsPassed(wpm_array_get($_POST, 'post_id'), wpm_array_get($_POST, 'passed'));
}