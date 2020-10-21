<form class="ask-form" action="" method="post" name="wpm-ajax-ask-form">
    <?php if ($full) : ?>
        <div class="dropdown-panel-header text-right">
            <a class="close-dropdown-panel"><?php _e('закрыть', 'mbl'); ?><span class="close-button"><span class="icon-close"></span></span> </a>
        </div>
    <?php endif; ?>
    <div class="form-fields-group">
        <div class="form-group form-icon form-icon-user">
            <input type="text" name="user_name" value="<?php echo wpm_get_current_user('display_name'); ?>" class="form-control" placeholder="<?php _e('Имя', 'mbl'); ?>" required="">
        </div>
        <div class="form-group form-icon form-icon-email">
            <input type="email" name="user_email" value="<?php echo wpm_get_current_user('user_email'); ?>" class="form-control" placeholder="<?php _e('Email', 'mbl'); ?>" required="">
        </div>
        <div class="form-group form-icon form-icon-pencil">
            <input type="text" name="title" value="" class="form-control" placeholder="<?php _e('Заголовок вопроса', 'mbl'); ?>" required>
        </div>
        <div class="form-group form-icon form-icon-edit">
            <?php wpm_editor('', 'message' . ($full ? '_full' : '_mobile'), array('placeholder' => __('Ваш вопрос', 'mbl')), true, 'message'); ?>
        </div>
    </div>
    <button type="submit" class="mbr-btn btn-default btn-solid btn-green text-uppercase wpm-button-ask"><?php _e('Отправить', 'mbl'); ?></button>
    <div class="wpm-ask-result"></div>
</form>
<?php if ($full) : ?>
    <script>
        jQuery(function ($) {
            $('form[name=wpm-ajax-ask-form]').on('submit', function (e) {
                var form = $(this);
                var result = $('.wpm-ask-result');
                result.html('<div class="preloader"></div>');

                var name = $('form[name=wpm-ajax-ask-form] input[name=user_name]');
                var email = $('form[name=wpm-ajax-ask-form] input[name=user_email]');
                var title = $('form[name=wpm-ajax-ask-form] input[name=title]');
                var message = $('form[name=wpm-ajax-ask-form] textarea[name=message]');

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: "send_wpm_ask_form",
                        name: name.val(),
                        email: email.val(),
                        title: title.val(),
                        message: message.val()
                    },
                    success: function (data) {
                        if (data == 'yes') {
                            result.html('<p class="success" style="text-align:center;"><?php _e('Ваше сообщение отправлено.', 'mbl'); ?></p>').show();

                            setTimeout(function () {
                                form.find('.close-dropdown-panel').click();
                                title.val('');
                                message.summernote('code', '');
                                result.hide();
                            }, 1000);
                        }
                        if (data == 'no') {
                            result.html('<p class="danger"><?php _e('Произошла ошибка! Сообщение не отправлено.', 'mbl'); ?></p>').show();
                        }
                    }
                });

                e.preventDefault();
            });
        });
    </script>
<?php endif; ?>