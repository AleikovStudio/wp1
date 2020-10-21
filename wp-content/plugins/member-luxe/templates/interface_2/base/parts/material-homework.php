<?php /** @var MBLCategory $category */ ?>
<?php /** @var MBLPage $mblPage */ ?>
<div class="content-wrap">
    <div class="question-answer-row">
        <div class="question">
            <div class="title">
                <span class="iconmoon icon-question-circle"></span><?php _e('Ответьте на вопрос:', 'mbl'); ?>
            </div>
            <div class="content"><?php echo $mblPage->getHomeworkDescription(); ?></div>
        </div>
        <div id="homework-response-wrapper" class="answer" <?php echo $mblPage->hasHomeworkResponse() ? '' : 'style="display:none;"'; ?>>
            <div class="title">
                <span class="iconmoon icon-pencil"></span><?php _e('Ваш ответ', 'mbl'); ?>
            </div>
            <div class=answer-meta>
                <span class="meta-item date">
                    <span class="iconmoon icon-calendar-o"></span>
                    <span id="response-date"><?php echo $mblPage->getHomeworkResponseDate(); ?></span>
                </span>
                <span class="meta-item time">
                    <span class="iconmoon icon-clock-o"></span>
                    <span id="response-time"><?php echo $mblPage->getHomeworkResponseTime(); ?></span>
                </span>
                <span class="meta-item status">
                    <span id="response-status-icon" class="iconmoon icon-file-text-o <?php echo $mblPage->getHomeworkStatusClass(); ?>"></span>
                    <span id="response-status-text"><?php echo $mblPage->getHomeworkStatusText(); ?></span>
                </span>
            </div>
            <div class="content" id="homework-response-content">
                <?php echo $mblPage->getHomeworkResponseContent(); ?>
                <?php echo $mblPage->getHomeworkResponseAttachmentsHTML(); ?>
            </div>
            <br class="clear">
        </div>
        <?php if (!$mblPage->hasHomeworkResponse() || !$mblPage->isHomeworkDone()) : ?>
            <?php wpm_render_partial('homework-response-form', 'base', compact('mblPage')) ?>
        <?php endif; ?>
    </div>
    <div class="discussion-row" id="wpm_homework_reviews_wrapper" <?php echo $mblPage->hasHomeworkResponseReviews() ? '' : 'style="display:none;"'; ?>>
        <div class="title">
            <span class="icon-comments-o"></span> <?php _e('Обсуждение ответа', 'mbl'); ?> <span class="note">(<?php _e('доступно только Вам и Тренеру', 'mbl'); ?>) </span>
        </div>
        <ol class="discussion-list" id="wpm_homework_reviews">
            <?php $lastUserId = $mblPage->getHomeworkResponse('reviews.0.user_id') ?: get_current_user_id(); ?>
            <?php foreach ($mblPage->getHomeworkResponse('reviews') as $review) : ?>
                <?php wpm_render_partial('homework-review', 'base', compact('review', 'lastUserId')) ?>
                <?php $lastUserId = wpm_array_get($review, 'user_id'); ?>
            <?php endforeach; ?>
        </ol>
        <?php wpm_render_partial('homework-review-form', 'base', compact('mblPage', 'lastUserId')) ?>
    </div>
</div>