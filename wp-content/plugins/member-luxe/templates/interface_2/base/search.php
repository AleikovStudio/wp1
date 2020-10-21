<?php $search = new MBLSearch() ?>
<?php $showPage = is_user_logged_in() || wpm_get_option('main.opened'); ?>
<?php wpm_render_partial('head', 'base', compact('post')) ?>
<?php wpm_render_partial('navbar') ?>
<div class="site-content">
    <?php wpm_render_partial('header-cover'); ?>
    <?php wpm_render_partial('breadcrumbs', 'base', array('breadcrumbs' => $search->getBreadcrumbs() )); ?>
    <?php if ($showPage) : ?>
        <section class="search-input-row">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <form class="big-search" action="<?php echo wpm_search_link(); ?>">
                            <?php if (get_option('permalink_structure') == '') : ?>
                                <input type="hidden" name="wpm-page" value="search">
                            <?php endif; ?>
                            <input
                                class="search-hint-input"
                                id="search-input"
                                type="text"
                                name="s"
                                autocomplete="off"
                                placeholder="<?php _e('Поиск...', 'mbl'); ?>"
                                value="<?php echo $search->getTerm() ?>">
                            <button type="submit" class="search-input-icon icon-search"></button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <section class="clearfix">
            <div class="search-result-row <?php echo wpm_option_is('main.posts_row_nb', 1, 2) ? 'one-in-line' : ''; ?>">
                <div class="container">
                    <div class="row">
                        <?php if ($search->count()) : ?>
                            <?php $iterator = 0 ?>
                            <?php foreach ($search->getPages() as $page) : ?>
                                <?php $category = $page->getCategory(); ?>
                                <?php if ($category && $category->rewindToCurrent()) : ?>
                                    <?php ++$iterator; ?>
                                    <div class="col-md-<?php echo wpm_option_is('main.posts_row_nb', 1, 2) ? '12' : '6'; ?>">
                                        <?php wpm_render_partial('material', 'base', compact('category', 'iterator')) ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="col-md-12">
                                <div class="no-posts">
                                    <p>
                                        <?php _e('Нет материалов удовлетворяющих условиям поиска', 'mbl') ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php wpm_render_partial('pagination', 'base', array('pager' => $search)) ?>
    <?php else : ?>
        <?php wpm_render_partial('restricted') ?>
    <?php endif; ?>
</div>
<?php wpm_render_partial('footer') ?>
<?php wpm_render_partial('footer-scripts') ?>