<div class="wrap bootstrap-admin-wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2>Ответы</h2>
    <div class="page-content-wrap">
        <form id="posts-filter" action="<?php echo $base_url; ?>" onsubmit="resetFilter(this);">
            <input type="hidden" name="post_type" value="wpm-page">
            <input type="hidden" name="page" value="wpm-autotraining">
            <input type="hidden" name="status" value="<?php echo $_GET['status']; ?>">

            <ul class="subsubsub">
                <li class="open">
                    <a href="<?php echo $base_url; ?>&status=opened" class="<?php echo $status=='opened'?'current':'';?>">Ожидающие
                        <span class="count">(<span class="pending-count"><?php echo wpm_get_response_counter('opened') ?></span>)</span>
                    </a> |
                </li>
                <li class="approved">
                    <a href="<?php echo $base_url; ?>&status=approved" class="<?php echo $status=='approved'?'current':'';?>">Одобренные
                        <span class="count">(<span class="pending-count"><?php echo wpm_get_response_counter('approved') ?></span>)</span>
                    </a> |
                </li>
                <li class="not_approved">
                    <a href="<?php echo $base_url; ?>&status=rejected" class="<?php echo $status=='rejected'?'current':'';?>">Не одобренные
                        <span class="count">(<span class="pending-count"><?php echo wpm_get_response_counter('rejected') ?></span>)</span>
                    </a> |
                </li>
                <li class="archive">
                    <a href="<?php echo $base_url; ?>&status=archive" class="<?php echo $status=='archive'?'current':'';?>">Архив
                        <span class="count">(<span class="pending-count"><?php echo wpm_get_response_counter('archive') ?></span>)</span>
                    </a>
                </li>
            </ul>

            <p class="search-box">
                <label class="screen-reader-text" for="post-search-input">Поиск:</label>
                <input type="search" id="post-search-input" name="s" value="<?php echo $_GET['s']; ?>">
                <input type="submit" name="" id="search-submit" class="button" value="Поиск">
            </p>
            <br><br>

            <div class="keys-nav-links tablenav top">
                <div class="alignleft actions">
                    <?php wpm_homework_filters(); ?>
                    <input type="submit" name="" id="post-query-submit" class="button" value="Фильтр">
                </div>
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo $total_records; ?> ответов</span>
                    <span class="pagination-links">
                    <a class="first-page disabled" title="Перейти на первую страницу"
                       href="<?php echo $first_link; ?>">«</a>
                    <a class="prev-page" title="Перейти на предыдущую страницу" href="<?php echo $prev_link; ?>">‹</a>
                    <span class="paging-input">
                        <input class="current-page" title="Текущая страница" type="text" name="paged"
                               value="<?php echo $page; ?>" size="1"> из
                        <span class="total-pages"><?php echo $total_pages; ?></span>
                    </span>
                    <a class="next-page" title="Перейти на следующую страницу" href="<?php echo $next_link; ?>">›</a>
                        <a class="last-page" title="Перейти на последнюю страницу"
                           href="<?php echo $last_link; ?>">»</a>
                </span>
                </div>
            </div>
            <div class="responses">
                <?php if (!empty($responses)):  ?>
                    <table id="the-comment-list" class="wp-list-table widefat fixed pages">
                        <thead>
                            <tr>
                            <th class="manage-column column-author sortable "><a>Автор</a></th>
                            <th class="manage-column column-comment sortable "><a>Ответ</a></th>
                            <th class="manage-column column-comment sortable "><a>Дата</a></th>
                            <th class="manage-column column-response sortable "><a>Урок</a></th>
                            <th class="manage-column column-date sortable"><a>Статус</a></th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                            <th class="manage-column column-author sortable "><a>Автор</a></th>
                            <th class="manage-column column-comment sortable "><a>Ответ</a></th>
                            <th class="manage-column column-comment sortable "><a>Дата</a></th>
                            <th class="manage-column column-response sortable "><a>Урок</a></th>
                            <th class="manage-column column-date sortable"><a>Статус</a></th>
                            </tr>
                        </tfoot>
                    <?php
                        $i = 0;
                        foreach ($responses as $item) {
                            $i++;
                            $alternative = ($i % 2) ? 'alternate ' : '';

                            if($item->is_archived) {
                                $status_class = 'archive';
                            } else {
                                $status_class = $item->response_status;
                            }

                            $user_info = get_userdata($item->user_id);
                            $user_profile_url = admin_url('/user-edit.php?user_id=' . $item->user_id);
                            $user_name = $user_info->display_name . ($user_info->user_login != $user_info->display_name ? ' (' . $user_info->user_login . ')' : '' );
                            $author = '<a href="' . $user_profile_url . '">' . $user_name . '</a>';

                            $content = apply_filters('the_content', $item->response_content);
                            $lesson = get_the_title($item->post_id);
                            $lesson = '<a href="' . get_permalink($item->post_id) . '" target="_blank">' . $lesson . '</a>';

                            switch ($item->response_status) {
                                case 'opened':
                                    $status = 'Ожидается проверка';
                                    break;
                                case 'approved':
                                    $status = 'Ответ правильный';
                                    break;
                                case 'accepted':
                                    $status = 'Ответ принят автоматически';
                                    break;
                                case 'rejected':
                                    $status = 'Ответ неправильный';
                                    break;
                                default:
                                    $status = '';
                                    break;
                            }

                            $reviews = getResponseReviews($item->id);
                            $reviewsHtml = '';
                            if (is_array($reviews) && count($reviews)) {
                                foreach ($reviews AS $review) {
                                    $reviewsHtml .= getReviewHtml($review->review_content, $review->id, $review->review_date, $review->user_id);
                                }
                                $reviewsStyle = '';
                                $editReply = '<span class="edit-reply hide-if-no-js">
                                                  <a onclick="closeTaskEditor();window.commentReply && commentReply.open( \'' . $item->id . '\',\'' . $item->post_id . '\', \'wpm_update_response_action\' );initRedactor(' . $item->id . '); add_edit_review_input(' . $item->id . '); return false;"
                                                     class="vim-r"
                                                     title="Редактировать последний ответ"
                                                     href="#">Редактировать последний ответ</a>
                                              </span>';
                            } else {
                                $reviewsStyle = 'style="display:none;"';
                                $editReply = '';
                            }

                            $attachments = UploadHandler::getHomeworkAttachments($item->post_id, $item->user_id);
                            ?>
                            <tr class="status-publish hentry iedit <?php echo $alternative . $status_class ?>"
                                id="comment-<?php echo $item->id; ?>">
                                <td class="manage-column column-title sortable desc"><?php echo $author; ?></td>
                                <td class="manage-column column-title sortable desc response_content">
                                    <?php echo stripslashes($content); ?>
                                    <?php if (!empty($attachments['files'])) : ?>
                                        <span class="homework-attachments">
                                            <?php foreach ($attachments['files'] AS $file) : ?>
                                                <?php if (isset($file->thumbnailUrl)) : ?>
                                                    <a href="<?php echo $file->url; ?>" title="<?php echo $file->name; ?>"
                                                       rel="wpm_homework_file_<?php echo $item->id; ?>" class="fancybox">
                                                        <img src="<?php echo $file->thumbnailUrl; ?>">
                                                    </a>
                                                <?php else : ?>
                                                    <a href="<?php echo $file->url; ?>" title="<?php echo $file->name; ?>"
                                                       download target="_blank">
                                                        <i class="fa fa-<?php echo $file->icon_class ?>-o"
                                                           aria-hidden="true"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </span>
                                    <?php endif; ?>
                                    <div class="row-actions">
                                        <span class="approve"><a href="" data-action="approve" response-status="approved"
                                                                 post-id="<?php echo $item->post_id; ?>"
                                                                 response-id="<?php echo $item->id; ?>"
                                                                 class="update-response vim-a" title="Одобрить этот ответ">Одобрить</a></span>
                                        <span class="unapprove"><a href="" data-action="unapprove" response-status="opened"
                                                                   response-id="<?php echo $item->id; ?>"
                                                                   class="update-response vim-u"
                                                                   title="Отклонить этот ответ">Отклонить</a></span>
                                        <span class="not_approve"><a href="" data-action="not_approve"
                                                                        response-status="rejected"
                                                                        post-id="<?php echo $item->post_id; ?>"
                                                                        response-id="<?php echo $item->id; ?>"
                                                                        class="update-response vim" title="Не правильно">Не правильно</a></span>
                                        <span class="archive_response"><a href="" data-action="archive"
                                                                        response-status="archive"
                                                                        post-id="<?php echo $item->post_id; ?>"
                                                                        response-id="<?php echo $item->id; ?>"
                                                                        class="update-response vim" title="Архивировать">Архивировать</a></span>
                                        <span class="unarchive_response"><a href="" data-action="unarchive"
                                                                        response-status="unarchive"
                                                                        post-id="<?php echo $item->post_id; ?>"
                                                                        response-id="<?php echo $item->id; ?>"
                                                                        class="update-response vim" title="Архивировать">Восстановить</a></span>
                                        <span class="reply hide-if-no-js"><a
                                                <?php if (version_compare(get_bloginfo('version'), '4.9', '>=')) : ?>
                                                    onclick="closeTaskEditor();window.commentReply && commentReply.open( '<?php echo $item->id; ?>','<?php echo $item->post_id; ?>', 'wpm_update_response_action' );return false;"
                                                <?php else : ?>
                                                    onclick="closeTaskEditor();window.commentReply && commentReply.open( '<?php echo $item->id; ?>','<?php echo $item->post_id; ?>', 'wpm_update_response_action' );initRedactor();return false;"
                                                <?php endif; ?>
                                                class="vim-r"
                                                title="Ответить"
                                                href="#">Ответить</a></span>
                                        <?php echo $editReply; ?>
                                        <span class="delete_response hide-if-no-js"><a href=""
                                                                                          data-action="delete_response"
                                                                                          response-status="delete"
                                                                                          post-id="<?php echo $item->post_id; ?>"
                                                                                          response-id="<?php echo $item->id; ?>"
                                                                                          class="update-response vim-d"
                                                                                          title="Удалить навсегда">Удалить навсегда</a></span>
                                    </div>
                                    <div class="admin-response-reviews" <?php echo $reviewsStyle; ?>>
                                        <div class="admin-review-title">Комментарии:</div><?php echo $reviewsHtml; ?>
                                    </div>
                                </td>
                                <td class="manage-column column-title sortable desc"><?php echo date('H:i', strtotime($item->response_date)); ?>
                                    <small><?php echo date('d.m.Y', strtotime($item->response_date)); ?></small>
                                </td>
                                <td class="manage-column column-title sortable desc"><?php echo $lesson; ?></td>
                                <td class="manage-column column-title sortable desc">
                                    <?php echo $status; ?><br/><i>
                                        <?php echo wpm_get_event_log($item->id, $item->response_status); ?></i>
                                </td>
                            </tr>
                            <?php
                        } ;?>
                    </table>
                <?php else : ?>
                    <p>Нет ответов</p>
                <?php endif; ?>
            </div>
        </form>
        <form method="get" action="">
            <table style="display:none;">
                <tbody id="com-reply">
                <tr id="replyrow" style="display: none;">
                    <td colspan="5" class="colspanchange">
                        <div id="replyhead" style=""></div>
                        <div id="replycontainer">
                            <?php if (version_compare(get_bloginfo('version'), '4.9', '>=')) : ?>
                                <?php wpm_editor('', 'replycontent', array('dialogsInBody' => false)); ?>
                            <?php else : ?>
                                <?php wp_editor('', 'replycontent', MBLRedactor::getWpPageTinyMCEOptions()); ?>
                            <?php endif; ?>
                        </div>

                        <p id="replysubmit" class="submit">
                            <a href="#comments-form" class="wpm-reply-save button-primary alignright"><span style="">Ответить</span></a>
                            <a href="#comments-form" class="wpm-reply-cancel button-secondary alignleft">Отмена</a>
                            <span class="waiting spinner"></span>
                            <span class="error" style="display: none;"></span>
                            <br class="clear">
                        </p>

                        <input type="hidden" name="action" value="wpm_update_response_action">
                        <input type="hidden" name="response_id" id="comment_ID" value="">
                        <input type="hidden" name="post_id" id="comment_post_ID" value="">
                        <input type="hidden" name="response_status" id="status" value="reply">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
        <div id="ajax-response"></div>
        <script type="text/javascript">
            function resetFilter(elem) {
                var $ = jQuery,
                    $form = $(elem).closest('form');

                if ($('#post-search-input').val() != '') {
                    $form.find('.keys-nav-links select').each(function () {
                        $(this).val($(this).find('option:first').attr('value'));
                    });
                }

            }

            function resetSearch() {
                jQuery('#post-search-input').val('');
            }

            function add_edit_review_input(response_id) {
                var id = jQuery('#comment-' + response_id).find('.admin-response-review:last').attr('data-review-id');

                jQuery('<input type="hidden" name="edit_review" id="edit_review" value="' + id + '">').insertAfter('#status');
            }

            function initRedactor(response_id) {
                var init, edId, qtId, firstInit, lastReview, wrapper, c = '';

                response_id = response_id || false;

                if (response_id !== false) {
                    lastReview = jQuery('#comment-' + response_id).find('.admin-response-review:last').clone();
                    lastReview.find('.review-datetime').remove();
                    c = lastReview.html();
                }

                <?php if (version_compare(get_bloginfo('version'), '4.9', '>=')) : ?>
                    jQuery('#replycontent').summernote('code', c);
                    return;

                <?php else: ?>
                    jQuery('#replycontainer').html('<div id="wp-replycontent-wrap" class="wp-core-ui wp-editor-wrap tmce-active"><link rel=\'stylesheet\' id=\'editor-buttons-css\'  href=\'/wp-includes/css/editor.min.css?ver=3.9.2\' type=\'text/css\' media=\'all\' />'
                        + '<div id="wp-replycontent-editor-container" class="wp-editor-container"><textarea class="wppage-footer-content wp-editor-area" style="height: 100px" autocomplete="off" cols="40" name="replycontent" id="replycontent">' + c + '</textarea></div></div>');
                    tinyMCE.editors = [];

                    if (typeof tinymce !== 'undefined') {
                        for (edId in tinyMCEPreInit.mceInit) {
                            if (firstInit) {
                                init = tinyMCEPreInit.mceInit[edId] = tinymce.extend({}, true, tinyMCEPreInit.mceInit[edId]);
                            } else {
                                init = firstInit = tinyMCEPreInit.mceInit[edId];
                            }

                            wrapper = tinymce.DOM.select('#wp-' + edId + '-wrap')[0];

                            if (( tinymce.DOM.hasClass(wrapper, 'tmce-active') || !tinyMCEPreInit.qtInit.hasOwnProperty(edId) ) && !init.wp_skip_init) {
                                try {
                                    tinymce.init(init);

                                    if (!window.wpActiveEditor) {
                                        window.wpActiveEditor = edId;
                                    }
                                } catch (e) {
                                }
                            }
                        }
                    }

                    if (typeof quicktags !== 'undefined') {
                        for (qtId in tinyMCEPreInit.qtInit) {
                            try {
                                quicktags(tinyMCEPreInit.qtInit[qtId]);

                                if (!window.wpActiveEditor) {
                                    window.wpActiveEditor = qtId;
                                }
                            } catch (e) {
                            }
                        }
                    }

                    if (typeof jQuery !== 'undefined') {
                        jQuery('.wp-editor-wrap').on('click.wp-editor', function () {
                            if (this.id) {
                                window.wpActiveEditor = this.id.slice(3, -5);
                            }
                        });
                    } else {
                        for (qtId in tinyMCEPreInit.qtInit) {
                            document.getElementById('wp-' + qtId + '-wrap').onclick = function () {
                                window.wpActiveEditor = this.id.slice(3, -5);
                            }
                        }
                    }

                <?php endif; ?>
            }
            jQuery(function ($) {
                $('.fancybox').fancybox();
                $('.update-response').on('click', function (e) {
                    var item = $(this);
                    $.ajax({
                        type     : 'POST',
                        url      : ajaxurl,
                        dataType : 'json',
                        data     : {
                            'action'          : 'wpm_update_response_action',
                            'response_id'     : item.attr('response-id'),
                            'post_id'         : item.attr('post-id'),
                            'response_action' : item.attr('data-action'),
                            'response_status' : item.attr('response-status')
                        },
                        success  : function (data) {
                            if (!data.error) {
                                window.location.reload();
                            }
                        }
                    });
                    e.preventDefault();
                });

                $(document).on('click', '.wpm-reply-cancel', function () {
                    closeTaskEditor();
                    return false;
                });

                $(document).on('click', '.wpm-reply-save', function () {
                    var post = {};

                    $('#replysubmit .error').hide();
                    $('#replysubmit .spinner').show();

                    $('#replyrow input').not(':button').each(function () {
                        var t = $(this);
                        post[t.attr('name')] = t.val();
                    });

                    <?php if (version_compare(get_bloginfo('version'), '4.9', '>=')) : ?>
                        post.content = jQuery('#replycontent').summernote('code');
                    <?php else : ?>
                        post.content = tinyMCE.activeEditor.getContent();
                    <?php endif; ?>

                    $.ajax({
                        type     : 'POST',
                        url      : ajaxurl,
                        dataType : 'json',
                        data     : post,
                        success  : function (data) {
                            if (!data.error) {
                                closeTaskEditor();
                                if (post.edit_review === undefined) {
                                    $('#comment-' + post.response_id)
                                        .find('.admin-response-reviews')
                                        .show()
                                        .append(data.html);
                                } else {
                                    $('#comment-' + post.response_id)
                                        .find('.admin-response-reviews')
                                        .show();
                                    $('#edit_review').remove();
                                    $('[data-review-id="' + data.reply_id + '"]').replaceWith(data.html);
                                }
                            }
                        },
                        error    : function (r) {
                            commentReply.error(r);
                        }
                    });

                    return false;
                });
            });
            function closeTaskEditor() {
                var $ = jQuery,
                    c,
                    replyrow = $('#replyrow');

                $('#add-new-comment').css('display', '');

                replyrow.hide();
                $('#com-reply').append(replyrow);
                $('#replycontent').css('height', '').val('');
                $('#edithead input').val('');
                $('.error', replyrow).html('').hide();
                $('.spinner', replyrow).hide();
            }
        </script>
    </div>
</div>

<?php wpm_enqueue_script('jquery-fancybox', plugins_url('/member-luxe/js/fancybox/jquery.fancybox.js')); ?>