<?php
$main_options = get_option('wpm_main_options');
$design_options = get_option('wpm_design_options');
?>

<script type="text/javascript">
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        jQuery(function ($) {
            var result = $('#ajax-result');
            var registered_alert = $('#ajax-user-registered');
            registered_alert.html('').css({'display': 'none'});
            result.html('').css({'display': 'none'});
            $('form[name=wpm-user-register-form]').submit(function (e) {
                $('#register-submit').val('Регистрация...');
                result.html('').css({'display': 'none'});
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: ajaxurl,
                    data: {
                        'action': 'wpm_ajax_register_user_action',
                        'fields': $(this).serializeArray()
                    },
                    success: function (data) {
                        if (data.registered === true) {
                            registered_alert.html(data.message).fadeIn('fast');
                            $('form#registration').slideUp('slow', function () {
                                setTimeout(function () {
                                    $('a[href="#wpm-login"]').click();
                                    $('form#login input[name=username]').val($('form#registration input[name=login]').val());
                                    $('form#login input[name=password]').val($('form#registration input[name=pass]').val());
                                    $('form#login').submit();
                                }, 1000);
                            });

                        } else {
                            result.html(data.message).fadeIn();
                            $('#register-submit').val('Зарегистрироваться');
                        }

                    },
                    error: function (data) {
                        result.html('Произошла ошибка.').fadeIn();
                        $('#register-submit').val('Зарегистрироваться');
                    }
                });
                e.preventDefault();
            });

            $('#wpm_generate_pin_code_button').on('click', function () {
                var $this = $(this),
                    $loader = $('#wpm_generate_pin_code_loader'),
                    $wpmUserCode = $('#wpm_user_code');
                $loader.css('display', 'block');
                $wpmUserCode.prop('disabled', true);
                $this.slideUp();
                $('.pin-code-error').hide();
                $.post(ajaxurl, {action: 'wpm_get_pin_code_action'}, function (data) {
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

<form id="registration" name="wpm-user-register-form" method="post" class="text-center">


    <?php if (wpm_reg_field_is_enabled($main_options, 'surname')):?>
        <p class="wpm-user-last-name">
            <span class="icon-user wpm-icon"></span>
            <input type="text" name="last_name" id="wpm_user_last_name" class="input" value="" size="20"
                   placeholder="<?php _e('Фамилия', 'wpm'); ?>">
        </p>
    <?php endif;?>

    <?php if (wpm_reg_field_is_enabled($main_options, 'name')):?>
        <p class="wpm-user-first-name">
            <span class="icon-user wpm-icon"></span>
            <input type="text" name="first_name" id="wpm_user_first_name" class="input" value="" size="20"
                   placeholder="<?php _e('Имя', 'wpm'); ?>">
        </p>
    <?php endif;?>

    <?php if (wpm_reg_field_is_enabled($main_options, 'patronymic')):?>
        <p class="wpm-user-sur-name">
            <span class="icon-user wpm-icon"></span>
            <input type="text" name="surname" id="wpm_user_surname" class="input" value="" size="20"
                   placeholder="<?php _e('Отчество', 'wpm'); ?>">
        </p>
    <?php endif;?>
    <br>

    <p class="wpm-user-email">
        <span class="icon-envelope wpm-icon"></span>
        <input type="email" name="email" id="wpm_user_email" class="input" value="" size="20" required=""
               placeholder="<?php _e('Email', 'wpm'); ?>">
    </p>

    <?php if (wpm_reg_field_is_enabled($main_options, 'phone')):?>
        <p class="wpm-user-phone">
            <span class="icon-phone wpm-icon"></span>
            <input type="text" name="phone" id="wpm_user_phone" class="input" value="" size="20"
                   placeholder="<?php _e('Телефон', 'wpm'); ?>">
        </p>
        <br>
    <?php endif;?>

    <p class="wpm-user-login">
        <span class="icon-user2 wpm-icon"></span>
        <input type="text" name="login" id="wpm_user_login" class="input" value="" size="20" required=""
               placeholder="<?php _e('Желаемый логин', 'wpm'); ?>">
    </p>

    <p class="wpm-login-password">
        <span class="icon-lock wpm-icon"></span>
        <input type="text" name="pass" id="wpm_user_pass" class="input" value="" size="20" required="" min="6"
               placeholder="<?php _e('Желаемый пароль', 'wpm'); ?>">
    </p>
    <br>

    <p class="wpm-login-code">
        <span class="icon-key wpm-icon"></span>
        <input type="text" name="code" id="wpm_user_code" class="input" value="" size="20" required=""
               placeholder="<?php _e('Введите код активации', 'wpm'); ?>">
        <?php if (wpm_option_is('pincode_page.generate', 'on')) : ?>
            <i class="input-loader" id="wpm_generate_pin_code_loader"></i>
            <a href="#" id="wpm_generate_pin_code_button"><?php echo wpm_get_option('pincode_page.link_name', __('Генерировать', 'wpm')); ?></a>
            <p class="pin-code-error" style="display:none; color:#990000"></p>
        <?php endif; ?>
    </p>

    <?php if ($main_options['registration_form']['custom1'] == 'on'):?>
        <p class="wpm-user-pencil">
            <span class="icon-pencil wpm-icon"></span>
            <input type="text" name="custom1" id="wpm_user_custom1" class="input" value="" size="20"
                   placeholder="<?php echo $main_options['registration_form']['custom1_label'] ?>">
        </p>
    <?php endif;?>
    <?php if ($main_options['registration_form']['custom2'] == 'on'):?>
        <p class="wpm-user-pencil">
            <span class="icon-pencil wpm-icon"></span>
            <input type="text" name="custom2" id="wpm_user_custom2" class="input" value="" size="20"
                   placeholder="<?php echo $main_options['registration_form']['custom2_label'] ?>">
        </p>
    <?php endif;?>
    <?php if ($main_options['registration_form']['custom3'] == 'on'):?>
        <p class="wpm-user-pencil">
            <span class="icon-pencil wpm-icon"></span>
            <input type="text" name="custom3" id="wpm_user_custom3" class="input" value="" size="20"
                   placeholder="<?php echo $main_options['registration_form']['custom3_label'] ?>">
        </p>
    <?php endif;?>

    <?php if (wpm_option_is('user_agreement.enabled_registration', 'on')) : ?>
        <?php wpm_render_partial('user-agreement') ?>
    <?php endif; ?>
    <p class="result alert alert-warning" id="ajax-result"></p>
    <br>

    <p class="register-submit">
        <input type="submit"
               name="wpm-register-submit"
               id="register-submit"
               <?php echo wpm_option_is('user_agreement.enabled_registration', 'on') ? 'disabled="disabled"' : ''; ?>
               class="button-primary wpm-register-button"
               value="<?php echo $design_options['buttons']['register']['text']; ?>">
    </p>
</form>
<p class="result alert alert-success text-center" id="ajax-user-registered"></p>