<?php add_filter('comment_text', 'wpm_remove_protocol_from_comment'); ?>
<?php $commentsNb = count($comments); ?>
<?php if (is_null($comments)) : ?>
    <?php
        $args = array('post_id' => $post_id);
        $countArgs = array('post_id' => $post_id, 'status' => 'approve', 'fields' => 'ids');
        $comments = get_comments($args);
        $commentsNb = count(get_comments($countArgs))
    ?>
<?php endif; ?>
<?php if (comments_open($post_id) || have_comments() || wpm_comments_is_visible()) : ?>
    <?php if (!wpm_option_is('main.comments_mode', 'cackle')) : ?>
        <?php if ($full && ($commentsNb || is_user_logged_in())) : ?>
            <div class="comments-row" id="comments">
                <div class="comments-header">
                    <span class="pull-right all-comments-count">
                        <span class="icon-comment-o"></span>
                        <span id="wpm-comments-nb">
                            <?php echo $commentsNb; ?>
                        </span>
                    </span>
                </div>
                <div class="comments-tabs bordered-tabs tabs-count-<?php echo is_user_logged_in() ? '2' : '1'; ?>">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs text-center" role="tablist">
                        <li role="presentation" class="active tab-1" id="wmp_all_comments" <?php echo is_user_logged_in() ? '' : 'style="display:none;"'; ?>>
                            <a href="#all-comments" aria-controls="all-comments" role="tab" data-toggle="tab">
                                <span class="iconmoon icon-comments-o"></span>
                                <span class="tab-label"><?php _e('Все коментарии', 'mbl'); ?></span>
                            </a>
                        </li>
                        <?php if (is_user_logged_in()) : ?>
                            <li class="tab-2" role="presentation" id="wmp_user_comments">
                                <a href="#own-comments" aria-controls="own-comments" role="tab" data-toggle="tab">
                                    <span class="iconmoon icon-comment-o"></span>
                                    <span class="tab-label"><?php _e('Мои комментарии', 'mbl'); ?></span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="wpm-comment-form">
                            <div class="row">
                                <div class="col-sm-8">
                                    <?php if (is_user_logged_in()) : ?>
                                        <div class="form-group">
                                            <label class="">
                                                <input type="checkbox"
                                                       name="notify-about-comments"
                                                       id="mbl_comment_subscription"
                                                       <?php echo MBLComment::hasSubscription($post_id) ? 'checked="checked"' : ''; ?>
                                                > <?php _e('Уведомлять по почте об ответах на мои комментарии', 'mbl'); ?>
                                            </label>
                                        </div>
                                    <?php else: ?>
                                        <span class="tab-label"><?php _e('Все коментарии', 'mbl'); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-sm-4">
                                    <a href="#" class="refresh-comments"><span class="icon-refresh"></span><span class="text"><?php _e('Обновить', 'mbl'); ?></span></a>
                                </div>
                            </div>

                            <?php if (comments_open($post_id)): ?>
                                <div id="respond" class="no-index">
                                    <?php wpm_comment_form($post_id); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div role="tabpanel" class="tab-pane active" id="all-comments">
                            <ol class="comments-list">
                                <?php wp_list_comments(wpm_get_comments_args(), $comments); ?>
                            </ol>
                        </div>
                        <?php if (is_user_logged_in()) : ?>
                            <div role="tabpanel" class="tab-pane" id="own-comments">
                                <ol class="comments-list"></ol>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <script>
                jQuery(function ($) {
                    var inactivePaneContent = false;

                    $('.refresh-comments').on('click', function () {
                        loadComments();
                        return false;
                    });

                    $('.comments-tabs .nav-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                        var activePane = $('.comments-tabs .tab-pane.active'),
                            inactivePane = $('.comments-tabs .tab-pane:not(.active) .comments-list'),
                            content = activePane.find('.comments-list');

                        if (inactivePaneContent === false) {
                            loadComments();
                        } else {
                            content.html(inactivePaneContent);
                            $('#wpm-comments-nb').text(content.find('.comment-item.approved').length);
                        }
                        inactivePaneContent = inactivePane.html();
                        inactivePane.html('');
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

                    function loadComments() {
                        var activePane = $('.comments-tabs .tab-pane.active'),
                            content = activePane.find('.comments-list'),
                            section = activePane.attr('id') === 'own-comments' ? 'user' : 'all';

                        activePane.addClass('loading');
                        $('#cancel-comment-reply-link').data('comment-success', true).click();
                        $.ajax({
                            type    : 'POST',
                            url     : ajaxurl,
                            data    : {
                                action  : 'wpm_the_comments_action',
                                id      : '<?php echo $post_id; ?>',
                                section : section
                            },
                            success : function (data) {
                                content.html(data);
                                activePane.removeClass('loading');
                                $('#wpm-comments-nb').text(content.find('.comment-item.approved').length);
                                $('.refresh-comments').removeClass('active').find('.text').text(dictionary.update);
                            }
                        });
                    }
                });
            </script>
        <?php else : ?>
            <?php wp_list_comments(wpm_get_comments_args(), $comments); ?>
        <?php endif; ?>
    <?php else : ?>
        <div class="comments-row" id="comments">
            <div class="comments-tabs bordered-tabs tabs-count-1">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active cackle">
                        <div id="mc-container"></div>
                    </div>
                </div>
            </div>
        </div>

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