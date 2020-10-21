<?php if ($comment->comment_approved == '1' || $comment->user_id == get_current_user_id() || wpm_is_admin()) : ?>
    <li <?php comment_class('comment-item' . ($comment->comment_approved == '1' ? ' approved' : '')); ?> id="li-comment-<?php comment_ID() ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment <?php echo $user && wpm_is_admin($user) ? 'comment-admin' : ''; ?>">
            <div class="comment-meta-wrap">
                <div class="comment-avatar">
                    <?php echo wpm_get_avatar_tag($user->ID, 48, true); ?>
                </div>
                <div class="comment-author-name">
                    <?php echo get_comment_author_link(); ?>
                </div>
                <div class="comment-meta">
                    <span class="date"><span class="iconmoon icon-calendar-o"></span> <?php echo get_comment_date(); ?></span>
                    <span class="time"><span class="iconmoon icon-clock-o"></span> <?php echo get_comment_time(); ?></span>
                </div>
                <?php if ($comment->comment_approved == '0' && is_user_logged_in()) : ?>
                    <div class="comment-meta">
                        <em class="not-approved-comment" ><?php _e('Ваш комментарий ожидает проверки.', 'mbl') ?></em>
                    </div>
                <?php endif; ?>
            </div>
            <div class="comment-content">
                <div class="comment-text">
                    <?php comment_text() ?>
                    <div class="comment-image row">
                        <?php $oldImages = get_comment_meta($comment->comment_ID, "comment_image"); ?>

                        <?php if (!empty($oldImages)) : ?>
                            <span class="homework-attachments">
                                <?php foreach ($oldImages as $image) : ?>
                                    <a
                                        href="<?php echo wpm_remove_protocol($image['url']); ?>"
                                        rel="wpm_comment_file_<?php echo $comment->comment_ID; ?>"
                                        class="fancybox homework-attachment"><img src="<?php echo wpm_remove_protocol($image['url']); ?>"></a>
                                <?php endforeach; ?>
                            </span>
                        <?php endif; ?>

                        <?php echo UploadHandler::getCommentAttachmentsHtml($comment->comment_ID, $comment->user_id); ?>
                    </div>
                </div>
                <div class="">
                    <?php if(is_user_logged_in()):?>
                        <?php edit_comment_link(__('Изменить', 'mbl'),'',' • ') ?>
                        <?php comment_reply_link(array_merge( $args, array('depth' => $depth))) ?>
                    <?php endif;?>
                </div>
            </div>
        </article>
    </li>
<?php endif; ?>