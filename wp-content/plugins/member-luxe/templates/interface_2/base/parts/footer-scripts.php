<?php
$wpm_footer_code = '';

if(is_single()){
    $page_meta = get_post_meta($post->ID, '_wpm_page_meta', true);

    if(isset($page_meta['code'])){
        $wpm_footer_code = stripcslashes(wpm_prepare_val($page_meta['code']['footer']));
    }
}
?>

<?php if (wpm_option_is('user_agreement.enabled_registration', 'on') || wpm_option_is('user_agreement.enabled_footer', 'on') || wpm_option_is('user_agreement.enabled_login', 'on')) : ?>
    <div class="modal fade" id="wpm_user_agreement_text" tabindex="-1" role="dialog" aria-labelledby="wpm_user_agreement_text" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"
                            class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only"><?php _e('закрыть', 'mbl'); ?></span>
                    </button>
                    <h4 class="modal-title" id="registration_label"><?php echo wpm_get_option('user_agreement.title', __('Пользовательское соглашение', 'mbl')); ?></h4>
                </div>
                <div class="modal-body">
                    <?php echo wpautop(stripslashes(wpm_get_option('user_agreement.text'))); ?>
                </div>
                <div class="modal-footer empty"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="wpm_user_agreement" tabindex="-1" role="dialog" aria-labelledby="user_agreement" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button"
                            class="close"
                            data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only"><?php _e('закрыть', 'mbl'); ?></span>
                    </button>
                    <h4 class="modal-title" id="registration_label"><?php echo wpm_get_option('user_agreement.title', __('Пользовательское соглашение', 'wpm')); ?></h4>
                </div>
                <div class="modal-body">
                    <?php echo wpautop(stripslashes(wpm_get_option('user_agreement.text'))); ?>
                </div>
                <div class="modal-footer">
                    <a type="button"
                       style="float: left"
                       class="btn btn-danger reject-button"
                       id="wpm_user_agreement_reject"
                       href=""><?php _e('Не принимаю', 'mbl'); ?></a>

                    <a type="button"
                       id="wpm_user_agreement_accept"
                       class="btn btn-primary btn-success"
                       ><?php _e('Принимаю', 'mbl'); ?></a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (is_user_logged_in() && wpm_option_is('protection.one_session.status', 'on')) : ?>
    <div class="modal fade" id="user-auth-fail" tabindex="-1" role="dialog" aria-labelledby="user_auth_fail"
         data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="activation_label"><?php _e('Предупреждение о выходе из системы', 'mbl'); ?></h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="padding-row">
                                <p><?php _e('Кто-то вошел в систему под вашим логином.', 'mbl'); ?></p>

                                <p><?php _e('Вы будете перенаправлены на главную страницу через 7 секунд', 'mbl'); ?></p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <img src="<?php echo plugins_url('/member-luxe/templates/base/img/hacker.png'); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php wpm_footer(); ?>

<?php wpm_render_partial('extra-js') ?>

<?php echo $wpm_footer_code; ?>
<!-- / wpm footer code -->
<?php echo get_option('coach_analytics'); ?>
<!--
<?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds.
-->
</body>
</html>