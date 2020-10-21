<?php $attachments = UploadHandler::getHomeworkReviewAttachmentsHtml(wpm_array_get($review, 'id'), get_current_user_id()); ?>
<li class="comment-item <?php echo wpm_is_admin(wpm_array_get($review, 'user_id')) ? '' : 'answer'; ?>">
    <article class="comment">
        <div class="comment-meta-wrap">
            <div class="comment-avatar">
                <?php if (wpm_array_get($review, 'user_id')) : ?>
                    <?php echo wpm_get_avatar_tag(wpm_array_get($review, 'user_id'), '70' ); ?>
                <?php endif; ?>
            </div>
            <div class="comment-author-name">
                <?php echo wpm_get_user(wpm_array_get($review, 'user_id'), 'display_name'); ?>
            </div>
            <div class="comment-meta">
                <span class="date"><span class="iconmoon icon-calendar-o"></span> <?php echo date_format(wpm_array_get($review, 'date_object'), 'd.m.Y'); ?></span>
                <span class="time"><span class="iconmoon icon-clock-o"></span> <?php echo date_format(wpm_array_get($review, 'date_object'), 'H:i'); ?></span>
            </div>
        </div>
        <div class="comment-content">
            <div class="comment-text"><?php echo wpautop(stripslashes(wpm_array_get($review, 'content', ''))); ?></div>
            <?php echo $attachments; ?>
        </div>
    </article>
</li>