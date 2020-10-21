<script type="text/javascript">
    jQuery(function ($) {
        var hash = window.location.hash;
        if(hash == '#registration'){
            $('a[href="#wpm-register"]').click();
        }else{
            $('a[href="#wpm-login"]').click();
        }
    });
</script>

<?php if (wpm_option_is('login_content.visible', 'on') && wpm_option_is('login_content.position', 'top')) : ?>
    <section class="page-title-row">
        <?php wpm_render_partial('restricted-content') ?>
    </section>
<?php endif; ?>
<section class="main-row clearfix">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="login-tabs bordered-tabs tabs-count-2">
                    <!-- Nav tabs -->
                      <ul class="nav nav-tabs text-center" role="tablist">
                        <li role="presentation"
                            class="tab-1 active">
                            <a href="#wpm-login"
                               aria-controls="login-tab"
                               role="tab"
                               data-toggle="tab">
                                <span class="icon-sign-in iconmoon"></span>
                                <?php _e('Вход', 'mbl'); ?>
                            </a>
                        </li>
                        <li role="presentation"
                            class="tab-2">
                            <a href="#wpm-register"
                               aria-controls="registration-tab"
                               role="tab"
                               data-toggle="tab">
                                <span class="iconmoon icon-user"></span>
                                <?php _e('Регистрация', 'mbl'); ?>
                            </a>
                        </li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="wpm-login">
                            <div class="login-form">
                                <?php wpm_render_partial('login-form', 'base', array('full' => true, 'standalone' => true)); ?>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wpm-register">
                            <div class="registration-form"><?php wpm_render_partial('register-form', 'base', array('full' => true, 'standalone' => true)); ?></div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</section>
<?php if (wpm_option_is('login_content.visible', 'on') && wpm_option_is('login_content.position', 'bottom')) : ?>
    <section class="page-title-row" style="margin-top: 0; margin-bottom: 40px;">
        <?php wpm_render_partial('restricted-content') ?>
    </section>
<?php endif; ?>
