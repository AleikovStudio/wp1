<?php wpm_redirect_filter(); ?>
<?php wpm_render_partial('head', 'base', compact('post')) ?>
<?php wpm_render_partial('navbar') ?>
<div class="site-content">
    <?php wpm_render_partial('header-cover'); ?>
    <?php if (post_password_required()) : ?>
        <div class="wpm-protected">
            <?php echo get_the_password_form(); ?>
        </div>
    <?php elseif (is_user_logged_in() || wpm_get_option('main.opened')) : ?>
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
            <?php wpm_render_partial('start-page', 'base', compact('post')) ?>
        <?php endwhile; endif; ?>
    <?php else : ?>
        <?php wpm_render_partial('restricted') ?>
    <?php endif; ?>
</div>
<?php wpm_render_partial('footer') ?>
<?php wpm_render_partial('footer-scripts') ?>
