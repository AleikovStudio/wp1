<!-- header -->
<?php if (is_user_logged_in() || wpm_get_option('main.opened')) : ?>
    <?php wpm_render_partial('navbar-full'); ?>
    <?php wpm_render_partial('navbar-mobile'); ?>
<?php endif; ?>
<!-- // header -->
