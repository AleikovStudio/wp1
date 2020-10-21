<?php /** @var MBLPage $mblPage */ ?>
<div class="form-group clearfix">
    <button type="button"
            name="button"
            class="mbr-btn btn-medium btn-solid btn-green write-message-button"
            id="mbl_show_response_form"
    ><?php $mblPage->hasHomeworkResponse() ? _e('Редактировать', 'mbl') : _e('Ответить', 'mbl'); ?></button>
</div>
<div class="discussion-form-wrap" style="display: none">
    <a class="close-message-form close-button" id="mbl_hide_response_form"><span class="iconmoon icon-close"></span></a>
    <form class="discussion-form"
          id="wpm_response_form"
          data-id="<?php echo $mblPage->getId(); ?>"
          data-name="wpm_task"
          enctype="multipart/form-data"
    >
        <div class="form">
            <?php wpm_editor($mblPage->getHomeworkResponseContent(), 'wpm_response_content', array(), true) ?>
            <div class="form-group file-upload-row">
                <?php jquery_html5_file_upload_hook(); ?>
            </div>
        </div>
        <div class="author">
            <?php echo wpm_get_avatar_tag(get_current_user_id(), 70); ?>
            <div class="title"><?php echo wpm_get_current_user('display_name'); ?></div>
            <button
                type="submit"
                name="button"
                class="mbr-btn btn-medium btn-solid btn-green"
                id="wpm_response_submit"
            ><?php _e('Отправить', 'mbl'); ?></button>
        </div>
    </form>
    <div class="response-result"></div>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        var responseForm = $('form#wpm_response_form'),
            responseFormWrap = responseForm.closest('.discussion-form-wrap'),
            showResponseButton = $('#mbl_show_response_form'),
            result = $('.response-result'),
            hideResponseButton = $('#mbl_hide_response_form');

        responseForm.on('submit', function (e) {
            var button = $('#wpm_response_submit');

            button.text('<?php _e('Загрузка...', 'mbl'); ?>');

            $.post(
                ajaxurl,
                {
                    action : 'wpm_add_response_action',
                    post_id : <?php echo $mblPage->getId(); ?>,
                    response_content : $('#wpm_response_content').summernote('code'),
                    response_type : '<?php echo $mblPage->getPostMeta('homework.checking_type'); ?>'
                },
                function (data) {
                    if (data.error) {
                        result.html('<p class="alert alert-warning">' + data.message + '</p>').show();
                    } else {
                        result.html('<p class="alert alert-success">' + data.message + '</p>').show();
                        window.location.reload();
                        <?php /*
                            setTimeout(function () {
                                homework(data.homework);
                                showResponseButton.fadeIn('fast').text('<?php _e('Редактировать', 'mbl'); ?>');
                                responseFormWrap.slideUp('fast');
                            }, 1000);
                        */ ?>
                    }

                    button.text('<?php _e('Отправить', 'mbl'); ?>');
                },
                "json"
            );

            e.preventDefault();
            return false;
        });

        showResponseButton.on('click', function () {
            showResponseButton.fadeOut('fast');
            responseFormWrap.slideDown('fast');
        });

        hideResponseButton.on('click', function () {
            showResponseButton.fadeIn('fast');
            responseFormWrap.slideUp('fast');
        });

        function homework(homework) {
            var $wrapper = $('#homework-response-wrapper'),
                $reviewWrapper = $('#wpm_homework_reviews_wrapper'),
                $reviewForm = $('form#wpm_response_review_form'),
                $date = $wrapper.find('#response-date'),
                $time = $wrapper.find('#response-time'),
                $statusIcon = $wrapper.find('#response-status-icon'),
                $statusTabIcon = $('#response-status-tab-icon'),
                $statusText = $wrapper.find('#response-status-text'),
                $content = $wrapper.find('#homework-response-content');

            if (jQuery.isEmptyObject(homework) === false) {
                $date.text(homework.date_str);
                $time.text(homework.time_str);
                $content.html(homework.content);
                $statusText.text(homework.status_msg);
                $statusIcon.attr('class', 'iconmoon icon-file-text-o ' + homework.status_class);
                $statusTabIcon.attr('class', 'icon-file-text-o  ' + homework.status_class);
                $wrapper.slideDown('fast');
                $reviewWrapper.slideDown('fast');
                $reviewForm.data('response-id', homework.id);
            }
        }
    });
</script>