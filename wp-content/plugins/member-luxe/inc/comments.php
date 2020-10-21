<?php
/**
 * Comments for wpm
 */
add_action('wp_ajax_wpm_the_comments_action', 'wpm_the_comments'); // ajax for logged in users
add_action('wp_ajax_nopriv_wpm_the_comments_action', 'wpm_the_comments'); // ajax for not logged in users
function wpm_the_comments()
{
    $post_id = $_POST['id'];
    $section = $_POST['section'];
    $section = $section == 'user' ? 'user' : 'all';
    if (!wpm_comments_is_visible() || !comments_open($post_id)) {
        echo '';
        die();
    }
    $current_user = wp_get_current_user();
    $accessible_levels = wpm_get_all_user_accesible_levels($current_user->ID);

    if (!wpm_check_access($post_id, $accessible_levels)) {
        echo '';
        die();
    } else {
        $comments = null;
        if ($section == 'user') {
            $comments = MBLComment::getUserTree($post_id);
        }
        wpm_comments_wordpress($post_id, $comments, $section, false);
    }
    die();

}

function wpm_comments_wordpress($post_id, $comments = null, $section = 'all', $full = true)
{
    wpm_render_partial('comments', 'base', compact('post_id', 'comments', 'section', 'full'));
}

/**
 *
 */

function wpm_comment_template($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    $post_id = $comment->comment_post_ID;
    $avatar = get_user_meta($comment->user_id, 'avatar', true);
    $attachments_is_disabled = wpm_attachments_is_disabled();
    $user = get_userdata($comment->user_id);

    if (wpm_is_interface_2_0()) {
        $args['reply_text'] = __('Ответить', 'mbl') . ' <span class="icon-long-arrow-right"></span>';
    }

    add_filter('comment_text', 'wpm_remove_protocol_from_text');
    wpm_render_partial('comment', 'base', compact('comment', 'user', 'post_id', 'avatar', 'attachments_is_disabled', 'args', 'depth'));
}

/**
 *
 */

function wpm_comment_form($post_id)
{
    global $user_identity;
    $design_options = get_option('wpm_design_options');
    $attachments_is_disabled = wpm_attachments_is_disabled();
    wpm_render_partial('comment-form', 'base', compact('post_id', 'user_identity', 'design_options', 'attachments_is_disabled'));
}

/**
 *
 */
add_action('comment_post', 'wpm_ajaxify_comments', 20, 2);
function wpm_ajaxify_comments($comment_ID, $comment_status)
{
    $comment = get_comment($comment_ID);
    $post_id = $comment->comment_post_ID;
    $isFrontend = isset($_POST['save-form']);
    if (get_post_type($post_id) == 'wpm-page' && $isFrontend) {

        $result = array(
            'status'         => 'error',
            'comment_parent' => '',
            'comment'        => ''
        );

        $maybe_notify = get_option( 'comments_notify' );
        $maybe_notify = apply_filters( 'notify_post_author', $maybe_notify, $comment_ID );

        //If AJAX Request Then
        switch ($comment_status) {
            case '0':
                //notify moderator of unapproved comment
                if ($maybe_notify) {
                    wp_notify_moderator($comment_ID);
                }
            case '1': //Approved comment
                $result['status'] = "success";
                $comment = get_comment($comment_ID);
                // Allow the email to the author to be sent
                if ($maybe_notify) {
                    wp_notify_postauthor($comment_ID);
                }
                // Get the comment HTML from my custom comment HTML function
                $result['comment'] = wpm_get_comment_html($comment);
                $result['comment_parent'] = $comment->comment_parent;
                break;
            default:
                $result['status'] = "error";
        }
        echo json_encode($result);
    }

}

/**
 * @param $comment
 */

function wpm_get_comment_html($comment) {
    $comment_html = '';
    $comment_html .= '<li '.comment_class("","","", false).'id="li-comment-'.$comment->comment_ID.'">';
    $comment_html .= '<div id="comment-'.$comment->comment_ID.'" class="comment-body">';
    $comment_html .= '<div class="comment-avatar-wrap pull-left">'.get_avatar($comment,$size='48' ).'</div>';
    $comment_html .= '<div class="comment-content">';
    $comment_html .= '<div class="comment-meta">';
    $comment_html .= '<div class="comment-author vcard">';
    $comment_html .= '<cite class="name">'.$comment->comment_author.'</cite><span class="coment-date">'.$comment->comment_date.'</span>';
    $comment_html .='</div>';
    $comment_html .='<div class="comment-metadata">';
                     if ($comment->comment_approved == '0') :
                         $comment_html .='<em>'.__('Your comment is awaiting moderation.').'</em>';
                    endif;
    $comment_html .='</div></div>';
    $comment_html .='<div class="comment-text">'.get_comment_text($comment->comment_ID).'</div>';
   // $comment_html .='<div class="comment-nav">'.get_comment_reply_link(array(), $comment->comment_ID, $comment->comment_post_ID).'</div>';
    $comment_html .='</div></li>';
    return $comment_html;
}

add_action('restrict_manage_comments', 'wpm_restrict_manage_comments');
function wpm_restrict_manage_comments() {
    $categoryTaxonomy = get_taxonomy('wpm-category');
    $categoryName = $categoryTaxonomy->labels->name;
    $categoryName = mb_strtolower($categoryName);
    $categoryTerms = get_terms('wpm-category');

    echo "<select name='wpm-category' id='wpm-category' class='postform'>";
    echo "<option value=''>Все {$categoryName}</option>";
    foreach ($categoryTerms as $term) {
        $current = $_GET['wpm-category'] == $term->term_id;
        echo '<option value="'. $term->term_id . '"', ($current ? ' selected="selected"' : ''),'>' . $term->name .'</option>';
    }
    echo "</select>";

    $posts = get_posts(array(
        'post_type' => 'wpm-page',
        'posts_per_page' => -1,
    ));

    echo "<select name='wpm-page' id='wpm-page' class='postform'", $_GET['wpm-category'] ? '' : 'disabled','>';
    echo "<option value=''>Все материалы</option>";
    foreach ($posts AS $post) {
        $current = $_GET['wpm-page'] == $post->ID;
        $categoryIds = wp_get_post_terms($post->ID, 'wpm-category', array("fields" => "ids"));
        echo ('<option value="'. $post->ID . '"'),
            $current ? ' selected="selected"' : '',
            (' data-categories=",' . implode(',',$categoryIds) . ',"'),
            '>' . $post->post_title .'</option>';
    }
    echo "</select>";
}

function wpm_comments_filter($clauses, $wp_comment_query)
{
    global $wpdb;

    if(!isset($_GET['wpm-category']) && !isset($_GET['wpm-page'])) {
        return $clauses;
    }

    if (!$clauses['join']) {
        $clauses['join'] = "JOIN $wpdb->posts ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID";
    }

    if (!$wp_comment_query->query_vars['post_type'])
    {
        $clauses['where'] .= $wpdb->prepare(" AND {$wpdb->posts}.post_type = %s", 'wpm-page');
    }

    if(isset($_GET['wpm-page']) && !empty($_GET['wpm-page'])) {
        $clauses['where'] .= $wpdb->prepare(" AND {$wpdb->posts}.ID = %d", $_GET['wpm-page']);
    }

    if (isset($_GET['wpm-category']) && !empty($_GET['wpm-category'])) {
        $category_id = intval($_GET['wpm-category']);

        $posts = get_posts(array(
                'post_type'      => 'wpm-page',
                'posts_per_page' => -1,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'wpm-category',
                        'field'    => 'id',
                        'terms'    => $category_id)
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
            $clauses['where'] .= " AND {$wpdb->posts}.ID IN (" . implode(',', $ids) . ") ";
        }
    }

    return $clauses;
}

function wpm_comments_lazy_hook($screen)
{
    if ($screen->id == 'edit-comments') {
        add_filter('comments_clauses', 'wpm_comments_filter', 10, 2);
    }
}

add_action( 'current_screen', 'wpm_comments_lazy_hook', 10, 2 );

function wpm_save_comment_image($commentId)
{
    if (wpm_is_interface_2_0()) {
        $postId = wpm_array_get($_POST, 'comment_post_ID');
        UploadHandler::saveCommentAttachments($commentId, $postId, get_current_user_id());
    }
}

add_filter('wp_insert_comment', 'wpm_save_comment_image');