<?php $standalone = isset($standalone) ? $standalone : false; ?>
<form class="wpm-registration-form" name="wpm-user-register-form" action="" method="post">
    <?php if ($full && !$standalone) : ?>
        <div class="dropdown-panel-header text-right">
            <a class="close-dropdown-panel"><?php _e('закрыть', 'mbl'); ?><span class="close-button"><span class="icon-close"></span></span> </a>
        </div>
    <?php endif; ?>
    <?php if (wpm_option_is('registration_form.surname', 'on') || wpm_option_is('registration_form.name', 'on') || wpm_option_is('registration_form.patronymic', 'on')) : ?>
        <div class="form-fields-group">
            <?php if (wpm_option_is('registration_form.surname', 'on')) : ?>
                <div class="form-group form-icon form-icon-user">
                    <input type="text" name="last_name" value="" class="form-control" placeholder="<?php _e('Фамилия', 'mbl'); ?>" required="">
                </div>
            <?php endif; ?>
            <?php if (wpm_option_is('registration_form.name', 'on')) : ?>
                <div class="form-group form-icon form-icon-user">
                    <input type="text" name="first_name" value="" class="form-control" placeholder="<?php _e('Имя', 'mbl'); ?>" required="">
                </div>
            <?php endif; ?>
            <?php if (wpm_option_is('registration_form.patronymic', 'on')) : ?>
                <div class="form-group form-icon form-icon-user">
                    <input type="text" name="surname" value="" class="form-control" placeholder="<?php _e('Отчество', 'mbl'); ?>" required="">
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="form-fields-group">
        <div class="form-group form-icon form-icon-email">
            <input type="email" name="email" value="" class="form-control" placeholder="<?php _e('Email', 'mbl'); ?>" required="">
        </div>
        <?php if (wpm_option_is('registration_form.phone', 'on')) : ?>
            <div class="form-group form-icon form-icon-phone">
                <input type="text" name="phone" value="" class="form-control" placeholder="<?php _e('Телефон', 'mbl'); ?>" required="">
            </div>
        <?php endif; ?>
    </div>
    <div class="form-fields-group">
        <div class="form-group form-icon form-icon-user">
            <input type="text" name="login"  value="" class="form-control" placeholder="<?php _e('Желаемый логин', 'mbl'); ?>" required="">
        </div>
        <div class="form-group form-icon form-icon-lock">
            <input type="text" name="pass" value="" class="form-control" placeholder="<?php _e('Желаемый пароль', 'mbl'); ?>" required="">
        </div>
    </div>
    <div class="form-fields-group">
        <div class="form-group form-icon form-icon-key wpm-pin-code-row">
            <input type="text" name="code" value="" class="form-control" placeholder="<?php _e('Код активации', 'mbl'); ?>" required="">
            <?php if (wpm_option_is('pincode_page.generate', 'on')) : ?>
                <i class="input-loader wpm_generate_pin_code_loader"></i>
                <a href="#" class="wpm_generate_pin_code_button"><?php echo wpm_get_option('pincode_page.link_name', __('Генерировать', 'wpm')); ?></a>
                <div class="pin-code-error"></div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (wpm_option_is('registration_form.custom1', 'on')) : ?>
        <div class="form-group form-icon form-icon-pencil">
            <input type="text"
                   name="custom1"
                   value=""
                   class="form-control"
                   placeholder="<?php echo wpm_get_option('registration_form.custom1_label'); ?>"
                   required="">
        </div>
    <?php endif; ?>
    <?php if (wpm_option_is('registration_form.custom2', 'on')) : ?>
        <div class="form-group form-icon form-icon-pencil">
            <input type="text"
                   name="custom1"
                   value=""
                   class="form-control"
                   placeholder="<?php echo wpm_get_option('registration_form.custom2_label'); ?>"
                   required="">
        </div>
    <?php endif; ?>
    <?php if (wpm_option_is('registration_form.custom3', 'on')) : ?>
        <div class="form-group form-icon form-icon-pencil">
            <input type="text"
                   name="custom1"
                   value=""
                   class="form-control"
                   placeholder="<?php echo wpm_get_option('registration_form.custom3_label'); ?>"
                   required="">
        </div>
    <?php endif; ?>

    <?php if (wpm_option_is('user_agreement.enabled_registration', 'on')) : ?>
        <?php wpm_render_partial('user-agreement') ?>
    <?php endif; ?>

    <div class="result alert alert-warning ajax-result"></div>

    <input
        type="submit"
        class="mbr-btn btn-default btn-solid btn-green text-uppercase register-submit"
        <?php echo wpm_option_is('user_agreement.enabled_registration', 'on') ? 'disabled="disabled"' : ''; ?>
        name="wpm-register-submit"
        value="<?php _e('Зарегистрироваться', 'mbl'); ?>"
    >
    <br>
    <div class="result alert alert-success text-center ajax-user-registered "></div>
</form>
<?php if ($full) : ?>
    <script type="text/javascript">
        jQuery(function ($) {
            $('form[name=wpm-user-register-form]').submit(function (e) {
                var $form = $(this);
                var $holder = $form.closest('.holder');
                var $mainHolder = $form.closest('.main-holder');
                var $loginForm = $('form.login:first');
                var result = $form.find('.ajax-result');
                var registered_alert = $form.find('.ajax-user-registered');
                $form.find('[name="wpm-register-submit"]').val('<?php _e('Регистрация...', 'mbl'); ?>');
                result.html('').css({'display' : 'none'});
                $.ajax({
                    type     : 'POST',
                    dataType : 'json',
                    url      : ajaxurl,
                    data     : {
                        'action' : 'wpm_ajax_register_user_action',
                        'fields' : $form.serializeArray()
                    },
                    success  : function (data) {
                        if (data.registered === true) {
                            registered_alert.html(data.message).fadeIn('fast', function() {
                                setTimeout(function () {
                                    $('a[href="#wpm-login"]').click();
                                    $loginForm.find('input[name=username]').val($form.find('input[name=login]').val());
                                    $loginForm.find('input[name=password]').val($form.find('input[name=pass]').val());
                                    $loginForm.submit();
                                }, 2000);
                            });

                        } else {
                            result.html(data.message).fadeIn();
                            $form.find('[name="wpm-register-submit"]').val('<?php _e('Зарегистрироваться', 'mbl'); ?>');
                        }

                    },
                    error    : function (data) {
                        result.html('<?php _e('Произошла ошибка.', 'mbl'); ?>').fadeIn();
                        $form.find('[name="wpm-register-submit"]').val('<?php _e('Зарегистрироваться', 'mbl'); ?>');
                    }
                });
                e.preventDefault();
            });

            $('.wpm_generate_pin_code_button').on('click', function () {
                var $this = $(this),
                    $form = $this.closest('form'),
                    $loader = $('.wpm_generate_pin_code_loader'),
                    $wpmUserCode = $form.find('[name="code"]');
                $loader.css('display', 'block');
                $wpmUserCode.prop('disabled', true);
                $this.slideUp();
                $('.pin-code-error').hide();
                $.post(ajaxurl, {action : 'wpm_get_pin_code_action'}, function (data) {
                    $wpmUserCode.prop('disabled', false);
                    if (data.success && data.code) {
                        $wpmUserCode.val(data.code);
                        $wpmUserCode.focus();
                        $wpmUserCode.blur();
                        $loader.hide();
                    } else {
                        $this.slideDown();
                        $('.pin-code-error').show().html(data.error).show();
                    }
                }, "json");

                return false;
            });
        });
    </script>
<?php endif; ?>
