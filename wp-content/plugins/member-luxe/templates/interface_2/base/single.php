<?php $categorySlug = get_query_var('wpm-category', null); ?>
<?php $category = $categorySlug ? get_term_by('slug', $categorySlug, 'wpm-category') : null; ?>
<?php $category = $category ? new MBLCategory($category) : null; ?>
<?php $mblPage = new MBLPage($post, $category); ?>
<?php $mblPage->incrementView(); ?>
<?php wpm_redirect_filter(); ?>
<?php wpm_render_partial('head', 'base', array('post' => $mblPage->getPost())) ?>
<?php wpm_render_partial('navbar') ?>
<div class="site-content">
    <?php wpm_render_partial('header-cover'); ?>
    <?php wpm_render_partial('breadcrumbs', 'base', compact('category', 'mblPage')); ?>
    <?php if (post_password_required()) : ?>
        <div class="wpm-protected">
            <?php echo get_the_password_form(); ?>
        </div>
    <?php elseif (is_user_logged_in() || wpm_get_option('main.opened')) : ?>
        <?php if (have_posts()): while (have_posts()): the_post(); ?>
            <?php wpm_render_partial('single', 'base', compact('category', 'mblPage')) ?>
        <?php endwhile; endif; ?>
    <?php else : ?>
        <?php wpm_render_partial('restricted') ?>
    <?php endif; ?>
</div>
<?php wpm_render_partial('footer') ?>
<?php wpm_render_partial('footer-scripts') ?>
