<?php /** @var MBLCategory $category */ ?>
<?php /** @var MBLPage $mblPage */ ?>
<?php if (!isset($category)) : ?>
    <?php $category = null; ?>
<?php endif; ?>
<?php if (is_user_logged_in() || wpm_get_option('main.opened')) : ?>
    <section class="breadcrumbs-row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="breadcrumbs-wrap">
                        <div class="breadcrumbs">
                            <a class="item"  href="<?php echo get_permalink(wpm_get_option('home_id')); ?>" title="<?php _e('Главная страница', 'mbl'); ?>">
                                <span class="iconmoon icon-home"></span>
                                <?php _e('Главная', 'mbl'); ?>
                            </a>
                            <?php if (!isset($breadcrumbs)) : ?>
                                <?php if ($category && $category->hasAccess()) : ?>
                                    <?php $i = 0; ?>
                                    <?php foreach ($category->getBreadcrumbs() as $breadcrumb) : ?>
                                        <?php $i++; ?>
                                        <span class="separator"><span class="icon-angle-right"></span></span>
                                        <?php if ($i == count($category->getBreadcrumbs()) && !isset($mblPage)) : ?>
                                            <a class="item" href="<?php echo wpm_array_get($breadcrumb, 'link'); ?>" title="<?php echo wpm_array_get($breadcrumb, 'name'); ?>">
                                                <span class="iconmoon icon-<?php echo wpm_array_get($breadcrumb, 'icon'); ?>"></span>
                                                <?php echo wpm_array_get($breadcrumb, 'name'); ?>
                                            </a>
                                        <?php else : ?>
                                            <a class="item"
                                               href="<?php echo wpm_array_get($breadcrumb, 'link'); ?>"
                                               title="<?php echo wpm_array_get($breadcrumb, 'name'); ?>">
                                                <span class="icon-<?php echo wpm_array_get($breadcrumb, 'icon'); ?>"></span>
                                                <?php echo wpm_array_get($breadcrumb, 'name'); ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if (isset($mblPage) && $mblPage->getId() == wpm_get_option('schedule_id')) : ?>
                                    <span class="separator"><span class="icon-angle-right"></span></span>
                                    <a class="item" href="<?php echo get_permalink(wpm_get_option('schedule_id')); ?>" title="<?php echo $mblPage->getTitle(); ?>">
                                        <span class="iconmoon icon-calendar-o"></span>
                                        <?php echo $mblPage->getTitle(); ?>
                                    </a>
                                <?php elseif (isset($mblPage) && $mblPage && is_single()) : ?>
                                    <span class="separator"><span class="icon-angle-right"></span></span>
                                    <a class="item" href="<?php echo wpm_material_link($category->getWpCategory(), $mblPage->getPost()); ?>" title="<?php echo $mblPage->getTitle(); ?>">
                                        <span class="iconmoon icon-file-text-o"></span>
                                        <?php echo $mblPage->getTitle(); ?>
                                    </a>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php foreach ($breadcrumbs as $breadcrumb) : ?>
                                        <?php $i++; ?>
                                        <span class="separator"><span class="icon-angle-right"></span></span>
                                        <?php if ($i == count($breadcrumbs)) : ?>
                                            <a class="item" href="<?php echo wpm_array_get($breadcrumb, 'link'); ?>" title="<?php echo wpm_array_get($breadcrumb, 'name'); ?>">
                                                <span class="iconmoon icon-<?php echo wpm_array_get($breadcrumb, 'icon'); ?>"></span>
                                                <?php echo wpm_array_get($breadcrumb, 'name'); ?>
                                            </a>
                                        <?php else : ?>
                                            <a class="item"
                                               href="<?php echo wpm_array_get($breadcrumb, 'link'); ?>"
                                               title="<?php echo wpm_array_get($breadcrumb, 'name'); ?>">
                                                <span class="icon-<?php echo wpm_array_get($breadcrumb, 'icon'); ?>"></span>
                                                <?php echo wpm_array_get($breadcrumb, 'name'); ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <?php if (!is_single() && isset($category) && $category->showProgressOnBreadcrumbs()) : ?>
                            <div class="course-progress-wrap" title="<?php _e('Пройдено уроков', 'mbl'); ?>">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $category->getProgress(); ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $category->getProgress(); ?>%">
                                        <span class="sr-only"><?php echo $category->getProgress(); ?>% <?php _e('Пройдено уроков', 'mbl'); ?></span>
                                    </div>
                                </div>
                                <div class="progress-count">
                                    <?php echo $category->getProgress(); ?>%
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>