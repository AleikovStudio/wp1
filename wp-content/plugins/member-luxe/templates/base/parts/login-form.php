<?php $design_options = get_option('wpm_design_options'); ?>
<form id="login" method="post">
    <p class="status"></p>

    <p>
        <span class="icon-user2"></span>
        <input id="username" type="text" name="username" placeholder="<?php _e('Логин', 'wpm'); ?>">
    </p>

    <p>
        <span class="icon-lock"></span>
        <input id="password" type="password" name="password" placeholder="<?php _e('Пароль', 'wpm'); ?>">
    </p>
    <p class="forgetmenot">
        <label><input name="remember" type="checkbox" id="rememberme" value="yes"
                      tabindex="90"/> <?php _e('Запомнить меня', 'wpm'); ?></label>
    </p>

    <p>
        <input class="submit_button wpm-sign-in-button" type="submit"
               value="<?php echo $design_options['buttons']['sign_in']['text']; ?>" name="submit">
    </p>

    <p class="text-center mb0">
        <a class="lost" href="<?php echo wp_lostpassword_url(); ?>"><?php _e('Забыли пароль?', 'wpm'); ?></a>
    </p>
    <?php if (wpm_option_is('user_agreement.enabled_login', 'on')) : ?>
        <div style="text-align: center">
            <a href="#wpm_user_agreement_text"
               data-toggle="modal"
               data-target="#wpm_user_agreement_text"
            ><?php echo wpm_get_option('user_agreement.login_link_title', __('Пользовательское соглашение', 'wpm')); ?></a>
        </div>
    <?php endif; ?>
    <?php wp_nonce_field('wpm-ajax-login-nonce', 'security'); ?>
</form>
<script>
    jQuery(function ($) {
        $('form#login').on('submit', function (e) {
            $('form#login p.status').show().text('<?php _e('Проверка...', 'wpm'); ?>');
            $.ajax({
                type     : 'POST',
                dataType : 'json',
                url      : ajaxurl,
                data     : {
                    'action'   : 'wpm_ajaxlogin',
                    'username' : $('form#login #username').val(),
                    'password' : $('form#login #password').val(),
                    'security' : $('form#login #security').val()
                },
                success  : function (data) {
                    $('form#login p.status').text(data.message);
                    if (data.loggedin == true) {
                        location.reload(false);
                    }
                }
            });
            e.preventDefault();
        });
    });
</script>