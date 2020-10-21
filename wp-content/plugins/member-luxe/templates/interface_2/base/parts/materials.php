<?php /** @var MBLCategory $category */ ?>
<section class="materials-row <?php echo wpm_option_is('main.posts_row_nb', 1, 2) ? 'one-in-line' : ''; ?> clearfix">
<?php if ($category->displayMaterials()) : ?>
    <div class="container">
        <div class="row">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()): ?>
                    <?php $category->iterateComposer() ?>
                    <?php if ($category->postIsHidden()) : ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <div class="col-md-<?php echo wpm_option_is('main.posts_row_nb', 1, 2) ? '12' : '6'; ?>">
                        <?php wpm_render_partial('material', 'base', compact('category')) ?>
                    </div>

                    <?php $category->getAutotrainingView()->updatePostIterator(); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-md-12">
                    <div class="no-posts">
                        <p>
                            <?php _e('Нет материалов', 'mbl') ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
</section>
<?php if ($category->displayMaterials()) : ?>
    <?php wpm_render_partial('pagination', 'base', array('pager' => $category)) ?>
<?php endif; ?>