<script type="text/javascript">
    jQuery(function ($) {
        var commentform = $('#commentform');
        var options = {
            beforeSubmit : function () {
                $('#save-form').val('Загрузка...');
            },
            success      : function (data) {
                $('.refresh-comments').click();
            },
            type         : 'post',
            clearForm    : true
        };
        commentform.ajaxForm(options);
    });
</script>

<?php if (is_user_logged_in()):?>

    <form class="wpm-comment-form" method="post" action="<?php echo site_url('/wp-comments-post.php'); ?>" id="commentform" enctype="multipart/form-data">
        <header class="info">
            <h4><?php comment_form_title(); ?></h4>
            <?php cancel_comment_reply_link(__('Отменить', 'wpm')); ?>
        </header>
        <div class="clearfix comment-form-wrap">
            <div id="comment-response"></div>
            <textarea name="comment" id="comment" cols="50" rows="10" required
                      class="field textarea medium" placeholder="<?php _e('Текст комментария', 'wpm'); ?>"></textarea>
            <div class="comment-image-wrap">
            </div>
            <div class="clearfix">
                <?php if(!$attachments_is_disabled):?>
                    <input type="hidden" name="comment-images" value="">
                    <label class="upload-image">
                        <input name="comment_image_<?php echo $post_id; ?>[]" id="comment_image" type="file" multiple="" />
                    </label>
                <?php endif;?>
                <input id="save-form" name="save-form" class="submit wpm-button pull-right wpm-comment-button" type="submit"
                       value="<?php echo $design_options['buttons']['send_comment']['text']; ?>"/>
            </div>
        </div>


        <?php comment_id_fields($post_id); ?>
        <?php do_action('comment_form', $post_id); ?>
    </form>
<?php endif;?>