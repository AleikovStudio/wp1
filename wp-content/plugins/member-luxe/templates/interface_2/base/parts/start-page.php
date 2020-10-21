<?php /** @var WP_Post $post */ ?>
<?php $page = new MBLPage($post); ?>
<?php $categoryCollection = new MBLCategoryCollection(0, true, true); ?>
<?php /*
    <?php if ($page->hasAccess()) : ?>
*/ ?>
    <?php if ($page->isProtected()) : ?>
        <?php wpm_render_partial('page-protection'); ?>
    <?php endif; ?>
    <section class="page-title-row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="page-title"><?php echo $page->getTitle(); ?></h1>
                    <?php if ($page->hasDescription()) : ?>
                        <div class="page-description">
                            <p><?php echo $page->getDescription(); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (trim(get_the_content()) != '' && wpm_option_is('homepage.show_description', 1, 1)) : ?>
                        <div class="page-description-content <?php echo wpm_option_is('homepage.description_expand', 1, 0) ? 'visible' : ''; ?>">
                            <?php if (!wpm_option_is('homepage.show_description_always', 1, 0)) : ?>
                                <button
                                    type="button"
                                    name="button"
                                    class="mbr-btn btn-small btn-bordered btn-rounded btn-gray toggle-category-description-button <?php echo wpm_option_is('homepage.description_expand', 1, 0) ? 'active' : ''; ?>">
                                    <span class="text"><?php _e('подробнее', 'mbl') ?></span>
                                    <span class="iconmoon  <?php echo wpm_option_is('homepage.description_expand', 1, 0) ? 'icon-close' : 'icon-angle-down'; ?>"></span>
                                </button>
                            <?php endif; ?>
                            <div class="content" <?php echo wpm_option_is('homepage.description_expand', 1, 0) ? 'style="display:block;"' : ''; ?>>
                                <?php the_content(); ?>
                                <?php echo get_interkassa_form($page->getPostMeta()); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php wpm_render_partial('categories', 'base', compact('categoryCollection')) ?>
    <?php wpm_render_partial('pagination', 'base', array('pager' => $categoryCollection)) ?>

<?php /*
    <?php else : ?>
        <?php wpm_render_partial('no-access'); ?>
    <?php endif; ?>
*/ ?>
