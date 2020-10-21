<?php /** @var MBLPage $mblPage */ ?>
<div class="form-group clearfix">
    <button type="button"
            name="button"
            class="mbr-btn btn-medium btn-solid btn-green write-message-button"
            id="mbl_show_review_form"
    ><?php _e('Написать сообщение', 'mbl'); ?></button>
</div>
<div class="discussion-form-wrap" style="display: none">
    <a class="close-message-form close-button" id="mbl_hide_review_form"><span class="iconmoon icon-close"></span></a>
    <form class="discussion-form"
          id="wpm_response_review_form"
          action="" method="post"
          data-id="<?php echo $mblPage->getHomeworkResponse('id'); ?>_new"
          data-response-id="<?php echo $mblPage->getHomeworkResponse('id'); ?>"
          data-name="wpm_review">
        <div class="form">
            <?php wpm_editor('', 'wpm_response_review_content', array(), true) ?>
            <div class="form-group file-upload-row">
                <?php jquery_html5_file_upload_hook(); ?>
            </div>
        </div>
        <div class="author">
            <?php echo wpm_get_avatar_tag(get_current_user_id(), '70' ); ?>
            <div class="title"><?php echo wpm_get_current_user('display_name'); ?></div>
            <button
                type="submit"
                name="button"
                class="mbr-btn btn-medium btn-solid btn-green"
                id="wpm_response_review_submit"
            ><?php _e('Отправить', 'mbl'); ?></button>
        </div>
    </form>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        var reviewForm = $('form#wpm_response_review_form'),
            reviewFormWrap = reviewForm.closest('.discussion-form-wrap'),
            showReviewButton = $('#mbl_show_review_form'),
            hideReviewButton = $('#mbl_hide_review_form');

        reviewForm.on('submit', function (e) {
            var button = $('#wpm_response_review_submit');

            button.text('<?php _e('Загрузка...', 'mbl'); ?>');

            $.post(
                ajaxurl,
                {
                    action : 'wpm_add_response_review_action',
                    response_id : reviewForm.data('response-id'),
                    review_content : $('#wpm_response_review_content').summernote('code'),
                    last_user_id : '<?php echo $lastUserId; ?>'
                },
                function ($html) {
                    $('#wpm_homework_reviews').append($html);
                    button.text('<?php _e('Отправить', 'mbl'); ?>');
                    hideReviewForm();
                    $('#wpm_response_review_content').summernote('code', '');
                    reviewForm.find('.wpm-fileupload').trigger('reload_files');
                }
            );

            e.preventDefault();
            return false;
        });

        showReviewButton.on('click', function () {
            showReviewButton.fadeOut('fast');
            reviewFormWrap.slideDown('fast');
            reviewForm.find('.wpm-fileupload').trigger('reload_files');
        });

        hideReviewButton.on('click', function () {
            hideReviewForm();
        });

        function hideReviewForm() {
            showReviewButton.fadeIn('fast');
            reviewFormWrap.slideUp('fast');
        }
    });
</script>