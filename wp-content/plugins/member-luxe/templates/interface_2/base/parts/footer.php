<?php $footer = apply_filters('the_content', wpm_get_option('footer.content')) ?>
<?php if (wpm_option_is('footer.visible', 'on')) : ?>
    <footer class="footer-row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <?php if (trim($footer) == '') : ?>
                        <div class="footer-text">
                            Copyright © MemberLux.ru
                        </div>
                    <?php else : ?>
                        <div class="footer-content wpm-content-text">
                            <?php echo $footer; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if (wpm_option_is('user_agreement.enabled_footer', 'on')) : ?>
            <div class="footer-user-agreement">
                <a href="#wpm_user_agreement_text"
                   data-toggle="modal"
                   data-target="#wpm_user_agreement_text"
                ><?php echo wpm_get_option('user_agreement.footer_link_title', __('Пользовательское соглашение', 'mbl')); ?></a>
            </div>
        <?php endif; ?>
    </footer>
<?php endif; ?>