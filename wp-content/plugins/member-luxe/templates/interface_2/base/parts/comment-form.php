<?php if (is_user_logged_in()): ?>
    <script type="text/javascript">
        jQuery(function ($) {
            initCommentForm();

            function clearCommentForm() {
                $('#commentform').find('#comment').summernote('code', '');
            }

            function initCommentForm() {
                var commentform = $('#commentform');
                var options = {
                    beforeSubmit : function () {
                        $('#save-form').val('<?php _e('Загрузка...', 'mbl'); ?>');
                    },
                    success      : function () {
                        clearCommentForm();
                        commentform.find('.wpm-fileupload').trigger('reload_files');
                        $('.refresh-comments').click();
                        $('#save-form').val('<?php _e('Отправить', 'mbl'); ?>');
                        commentform.ajaxForm(options);
                    },
                    type         : 'post',
                    clearForm    : true
                };
                commentform.ajaxForm(options);
            }

            $('#comments').on('click', '#cancel-comment-reply-link', function () {
                var isReply = $('#comment_parent').val() == '0',
                    $this = $(this);

                if($this.data('comment-success')) {
                    $this.data('comment-success', false);
                    return;
                }

                clearCommentForm();
                $('#commentform').find('.wpm-fileupload').trigger('delete_files');

                if(isReply) {
                    return false;
                }
            });
        });
    </script>

    <form class="add-comment-form"
          method="post"
          action="<?php echo site_url('/wp-comments-post.php'); ?>"
          data-id="<?php echo $post_id; ?>_new"
          data-name="wpm_comment"
          id="commentform"
          enctype="multipart/form-data"
    >
        <div class="form-group form-title">
            <?php _e('Добавить комментарий', 'mbl'); ?>
        </div>
        <div class="form-group">
            <?php wpm_editor('', 'comment', array('placeholder' => __('Текст комментария', 'mbl'), 'height' => 100), true, null, true) ?>
        </div>
        <div class="form-group file-upload-row">
            <?php if (!$attachments_is_disabled) : ?>
                <?php jquery_html5_file_upload_hook(); ?>
            <?php endif; ?>
            <input id="save-form"
                   name="save-form"
                   class="submit wpm-button pull-right wpm-comment-button mbr-btn btn-medium btn-solid btn-green pull-right"
                   type="submit"
                   value="<?php _e('Отправить', 'mbl'); ?>"/>
            <?php cancel_comment_reply_link(__('Отменить', 'mbl')); ?>
        </div>

        <?php comment_id_fields($post_id); ?>
        <?php do_action('comment_form', $post_id); ?>
    </form>
<?php endif; ?>
