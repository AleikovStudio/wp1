<?php $page_meta = get_post_meta(get_the_ID(), '_wpm_page_meta', true); ?>
<?php $has_homework = wpm_has_homework($page_meta); ?>
<?php $design_options = get_option('wpm_design_options'); ?>
<?php  if ($has_homework) : ?>
    <?php $data = wpm_get_responses(get_current_user_id(), get_the_ID(), $page_meta); ?>
    <?php $attachments = UploadHandler::getHomeworkAttachmentsHtml(get_the_ID(), get_current_user_id()); ?>
    <div class="homework-wrap wpm-content">
        <h3 class="homework-title">Задание
            <?php if (empty($data['status'])): ?>
                <a class="link pull-right response-link wpm-button wpm-homework-respond-button"
                   href="#response"
                   data-toggle="modal"
                   data-target="#response_modal">
                    <?php echo $design_options['buttons']['home_work_respond_on_page']['text']; ?>
                </a>
            <?php endif; ?>
        </h3>
        <div class="wpm-content-text"><?php echo wpautop($page_meta['homework_description']);?></div>

        <div class="response">
            <div id="homework-response-wrapper" class="<?php echo empty($data['content']) ? 'hidden' : ''; ?>">
                <h3>
                    Ваш ответ
                    <?php if (!in_array($data['status'], array('accepted', 'approved'))): ?>
                        <a class="link pull-right response-link wpm-button wpm-homework-edit-button"
                           href="#response"
                           data-toggle="modal"
                           data-target="#response_modal"><?php echo $design_options['buttons']['home_work_edit']['text']; ?></a>
                    <?php endif; ?>
                </h3>

                <div class="response-body">
                    <div class="response-header">
                        <div class="response-title">
                            <i class="clocks"></i>
                            <span class="response-date" id="response-date"><?php echo $data['date']; ?></span>
                            <span class="response-status <?php echo $data['status']; ?>">
                                <i class="status-icon"></i><span><?php echo $data['status_msg']; ?></span>
                            </span>
                        </div>
                    </div>
                    <span id="response_content" class="response_content">
                        <?php echo apply_filters('the_content', wpautop($data['content'])); ?>
                        <?php echo $attachments; ?>
                    </span>

                    <div class="response-reviews">
                        <div class="response-title <?php echo (count($data['reviews'])) ? '' : 'hidden'; ?>">
                            <i></i>Комментарии к ответу:
                        </div>
                        <div id="response-reviews">
                            <?php if (count($data['reviews'])) : ?>
                                <?php foreach ($data['reviews'] as $review): ?>
                                    <div class="response-review">
                                                <span class="response-date">
                                                    <?php echo $review['date']; ?>
                                                </span>
                                        <?php echo wpautop(stripslashes($review['content'])); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade"
                 id="response_modal"
                 tabindex="-1"
                 role="dialog"
                 aria-labelledby="response_label"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button"
                                    class="close"
                                    data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                                <span class="sr-only">Закрыть</span>
                            </button>
                            <h4 class="modal-title" id="activation_label">
                                <?php if (empty($data['status'])): ?>
                                    Ответить на задание
                                <?php else: ?>
                                    Редактировать задание
                                <?php endif; ?>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <form class="homework-respnose-form" page-id="<?php the_ID(); ?>" enctype="multipart/form-data">
                                <div><?php wpm_editor( wpautop($data['content']), 'response-content'.get_the_ID()); ?></div>
                                <?php jquery_html5_file_upload_hook(); ?>
                                <p>
                                    <?php if (empty($data['status'])): ?>
                                        <input type="submit" class="button wpm-respond-popup-button" value="<?php echo $design_options['buttons']['home_work_respond_on_popup']['text']; ?>">
                                    <?php else: ?>
                                        <input type="submit" class="button wpm-homework-edit-popup-button" value="<?php echo $design_options['buttons']['home_work_edit_on_popup']['text']; ?>">
                                    <?php endif; ?>
                                </p>
                                <br>
                                <div class="response-result"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                function reviews(homework) {
                    if (jQuery.isEmptyObject(homework['reviews']) === false) {
                        $('#response-reviews').html('');
                        $.each(homework['reviews'], function (key, value) {
                            var review = '<div class="response-review">' +
                                '<span class="response-date">' + value['date'] + '</span><br/>' +
                                value['content'] +
                                '</div>';
                            $('#response-reviews').append(review);
                        });
                    }
                }

                function homework(homework) {
                    $('.homework-title .response-link').remove();
                    if (jQuery.isEmptyObject(homework) === false) {
                        $('#response-date').html(homework['date']);
                        $('#response_content').html(homework['content']);
                        $('.response-status span').html(homework['status_msg']);
                        $('.response-status').attr('class', 'response-status ' + homework['status']);
                        $('#homework-response-wrapper').removeClass('hidden');

                        if (homework['status'] == 'approved' || homework['status'] == 'accepted') {
                            $('.response-link').hide();
                        } else {
                            $('.response-link').html('Редактировать');
                            $('#activation_label').html('Редактировать задание');
                        }
                        reviews(homework);
                    }
                }

                jQuery(function ($) {
                    $('.response-link').data('edit', 'yes');
                    var response = $('form.homework-respnose-form');
                    var result = $('.response-result');
                    result.hide().html('');

                    response.on('submit', function (e) {
                        result.hide().html('');
                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            dataType: 'json',
                            data: {
                                'action': 'wpm_add_response_action',
                                'post_id': "<?php the_ID(); ?>",
                                'response_content': $('#response-content<?php the_ID()?>').summernote('code'),
                                'response_type': "<?php echo $page_meta['homework']['checking_type'] ?>"
                            },
                            success: function (data) {
                                if (data.error) {
                                    result.html('<p class="alert alert-warning">' + data.message + '</p>').show();
                                } else {
                                    result.html('<p class="alert alert-success">' + data.message + '</p>').show();
                                    homework(data.homework);
                                    setTimeout(function () {
                                                $('#response_modal').find('.close:first').click();
                                        result.hide().html('');
                                    }, 1000);
                                }
                            }
                        });

                        e.preventDefault();
                    });
                });
            </script>
        </div>
    </div>
<?php endif; ?>