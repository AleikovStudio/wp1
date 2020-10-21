<?php
$design_options = get_option('wpm_design_options');
add_filter('comment_text', 'wpm_remove_protocol_from_comment');
?>
<?php if (comments_open($post_id) || have_comments() || wpm_comments_is_visible()): ?>
    <?php if (!wpm_option_is('main.comments_mode', 'cackle')) : ?>
        <script>
            jQuery(function ($) {
                $('.refresh-comments').on('click', function () {
                    var button = $(this);
                    button.text('Загрузка...');
                    $.ajax({
                        type    : 'POST',
                        url     : ajaxurl,
                        data    : {
                            'action' : 'wpm_the_comments_action',
                            'id'     : '<?php echo $post_id; ?>'
                        },
                        success : function (data) {
                            button.text('Обновлено!');
                            $('.wpm-comments-wrap').html(data);
                        }
                    });
                });
                $('.comment-tabs>li>a').on('click', function () {
                    var $this = $(this),
                        commentsContent = $('.wpm-comments-content'),
                        data;
                    if (!$this.closest('li').hasClass('active')) {
                        data = {
                            action  : 'wpm_the_comments_action',
                            id      : '<?php echo $post_id; ?>',
                            section : ($this.attr('id') == 'wmp_user_comments' ? 'user' : 'all')
                        };
                        commentsContent.addClass('loading');
                        $.post(ajaxurl, data, function (response) {
                            $('.wpm-comments-wrap').html(response);
                        });
                    }
                    return false;
                });
                $('#mbl_comment_subscription').on('change', function () {
                    var $this = $(this),
                        data = {
                            action : 'wpm_add_comment_subscription',
                            id     : '<?php echo $post_id; ?>'
                        };
                    $.post(ajaxurl, data, function (response) {
                        $this.prop('checked', !!response);
                    }, "json");
                    return false;
                });
            });
        </script>
        <div id="comments">
            <?php global $post;
            if (is_null($comments)) {
                $comments = get_comments('post_id=' . $post_id);
            }
            $commentsNb = count($comments);
            ?>
            <ul class="nav nav-tabs comment-tabs">
                <li <?php echo $section == 'all' ? 'class="active"' : ''; ?>><a id="wmp_all_comments" href="#">Все
                        комментарии</a></li>
                <li <?php echo $section == 'user' ? 'class="active"' : ''; ?>><a id="wmp_user_comments" href="#">Мои
                        комментарии</a></li>
            </ul>
            <br>
            <label>
                <input type="checkbox"
                       id="mbl_comment_subscription" <?php echo MBLComment::hasSubscription($post_id) ? 'checked="checked"' : ''; ?>>
                <?php _e('Уведомлять по почте об ответах на мои комментарии. ', 'wpm'); ?>
            </label>
            <div class="wpm-comments-content">
                <h2 id="comments-title" class="clearfix"><?php
                    printf(_n('Один комментарий', '%1$s комментариев', $commentsNb),
                        number_format_i18n($commentsNb));
                    ?>
                    <a class="refresh-comments pull-right"><?php echo $design_options['buttons']['refresh_comments']['text']; ?></a>
                </h2>

                <?php if (comments_open($post_id)): ?>
                    <div id="respond" class="no-index">
                        <?php wpm_comment_form($post_id); ?>
                    </div>
                <?php endif; ?>

                <ol class="commentlist clearfix">
                    <?php
                    $args = array(
                        'walker' => null,
                        'max_depth' => '',
                        'style' => 'ul',
                        'callback' => 'wpm_comment_template',
                        'end-callback' => null,
                        'type' => 'all',
                        'reply_text' => __('Ответить', 'wpm'),
                        'page' => '',
                        'per_page' => '',
                        'avatar_size' => 48,
                        'reverse_top_level' => null,
                        'reverse_children' => '',
                        'format' => 'html5', //or xhtml if no HTML5 theme support
                        'short_ping' => false, // @since 3.6,
                        'echo' => true // boolean, default is true
                    );

                    wp_list_comments($args, $comments); ?>
                </ol>

                <?php if (get_comment_pages_count($post_id) > 1 && get_option('page_comments')) : // Are there comments to navigate through? ?>
                    <div class="navigation">
                        <div class="nav-previous"><?php previous_comments_link(); ?></div>
                        <div class="nav-next"><?php next_comments_link(); ?></div>
                        <div class="clear"></div>
                    </div><!-- .navigation -->
                <?php endif; // check for comment navigation ?>
            </div>
        </div>
    <?php else : ?>
        <div id="mc-container"></div>
        <script type="text/javascript">
            cackle_widget = window.cackle_widget || [];
            cackle_widget.push({
                widget  : 'Comment',
                channel : '<?php echo $post_id; ?>',
                stream  : <?php echo wpm_option_is('main.cackle_auto_update', 'on') ? 'true' : 'false'; ?>,
                id      : <?php echo wpm_get_option('main.cackle_id'); ?>
            });
            (function () {
                var mc = document.createElement('script');
                mc.type = 'text/javascript';
                mc.async = true;
                mc.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cackle.me/widget.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(mc, s.nextSibling);
            })();
        </script>
    <?php endif; ?>
<?php endif; ?>