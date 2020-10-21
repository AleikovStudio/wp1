<?php $standalone = isset($standalone) ? $standalone : false; ?>
<form class="login" method="post">
    <?php if ($full && !$standalone) : ?>
        <div class="dropdown-panel-header text-right">
            <a class="close-dropdown-panel"><?php _e('закрыть', 'mbl'); ?><span class="close-button"><span class="icon-close"></span></span> </a>
        </div>
    <?php endif; ?>
    <div class="form-fields-group">
        <p class="status"></p>
        <div class="form-group form-icon form-icon-user">
            <input type="text" name="username"  class="form-control" placeholder="<?php _e('Логин', 'mbl'); ?>" required="">
        </div>
        <div class="form-group form-icon form-icon-lock">
            <input type="password" name="password" class="form-control" placeholder="<?php _e('Пароль', 'mbl'); ?>" required="">
        </div>
        <?php if (!$standalone) : ?>
            <div class="form-group mbl-remember-row">
                <label><input name="remember" type="checkbox" value="yes" tabindex="90" /> <?php _e('Запомнить меня', 'mbl'); ?></label>
            </div>
            <div class="form-group">
                <a href="<?php echo wp_lostpassword_url(); ?>" class="helper-link"><?php _e('Забыли пароль?', 'mbl'); ?></a>
            </div>
        <?php else : ?>
            <div class="row">
                <div class="col-sm-6">
                    <label><input name="remember" type="checkbox" value="yes" tabindex="90" /> <?php _e('Запомнить меня', 'mbl'); ?></label>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="form-group">
                        <a href="<?php echo wp_lostpassword_url(); ?>" class="helper-link"><?php _e('Забыли пароль?', 'mbl'); ?></a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <button type="submit" class="mbr-btn btn-default btn-solid btn-green text-uppercase wpm-sign-in-button"><?php _e('Войти', 'mbl'); ?></button>

    <?php if (wpm_option_is('user_agreement.enabled_login', 'on')) : ?>
        <div class="mbl-user-agreement-row">
            <a href="#wpm_user_agreement_text"
               data-toggle="modal"
               data-target="#wpm_user_agreement_text"
            ><?php echo wpm_get_option('user_agreement.login_link_title', __('Пользовательское соглашение', 'mbl')); ?></a>
        </div>
    <?php endif; ?>
    <?php wp_nonce_field('wpm-ajax-login-nonce', 'security'); ?>
</form>
<?php if ($full) : ?>
    <script>
        jQuery(function ($) {
            $('form.login').on('submit', function (e) {
                var $form = $(this);
                $form.find('p.status').show().text('<?php _e('Проверка...', 'mbl'); ?>');
                $.ajax({
                    type     : 'POST',
                    dataType : 'json',
                    url      : ajaxurl,
                    data     : {
                        'action'   : 'wpm_ajaxlogin',
                        'username' : $form.find('[name="username"]').val(),
                        'password' : $form.find('[name="password"]').val(),
                        'security' : $form.find('[name="security"]').val(),
                        'remember' : $form.find('[name="remember"]').val()
                    },
                    success  : function (data) {
                        $form.find('p.status').text(data.message);
                        if (data.loggedin == true) {
                            location.reload(false);
                        }
                    }
                });
                e.preventDefault();
                return false;
            });
        });
    </script>
<?php endif; ?>