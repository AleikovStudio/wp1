<?php /** @var MBLSearchPage $page */ ?>
<?php $category = $page->getCategory(); ?>
<?php $category->rewindToCurrent(); ?>
<article class="material-item <?php echo $category->isPageOnlyPreview() ? 'material-closed' : 'material-opened'; ?> <?php echo $category->isHomeworkDone() ? 'material-done' : ''; ?>">
    <a class="flex-wrap"
       <?php if (!$category->isPageOnlyPreview()) : ?>
           href="<?php echo $category->getCurrentPageLink(); ?>"
       <?php endif; ?>
    >
        <div class="col-thumb <?php echo $category->getHomeworkStatusClass(); ?>">
            <div class="thumbnail-wrap" style="background-image: url(./images/assets/lesson-1.jpg);">
                <div class="icons-top">
                    <div class="icons">
                        <?php $iterator = isset($iterator) ? $iterator : $category->getAutotrainingView()->postIterator; ?>
                        <span class="m-icon count"># <?php echo $iterator; ?></span>
                        <span class="m-icon video"><span class="icon-video-camera"></span></span>
                        <span class="m-icon audio"><span class="icon-volume-up"></span></span>
                        <span class="m-icon file"><span class="icon-file-text"></span></span>
                        <span class="status-icon"><span class="icon-unlock"></span></span>
                    </div>
                </div>
                <div class="icons-bottom">
                    <div class="icons">
                        <?php if ($category->isAutotraining() && $category->getCurrentMBLPage()->hasHomework()) : ?>
                            <span class="status <?php echo $category->getHomeworkStatusClass(); ?>"><span class="icon-file-text-o"></span>
                                <?php echo $category->getHomeworkStatusText(); ?>
                            </span>
                        <?php endif; ?>
                        <div class="right-icons">
                            <span class="comments"><span class="icon-comment-o"></span> <?php echo $category->getCurrentMBLPage()->getCommentsNumber(); ?></span>
                            <span class="date"><span class="icon-calendar-o"></span> <?php echo get_the_date(); ?></span>
                            <span class="views"><span class="icon-eye"></span> <?php echo $category->getCurrentMBLPage()->getViewsNumber(); ?></span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-content <?php echo $category->getHomeworkStatusClass(); ?>">
            <div class="content-wrap">
                <h1 class="title"><?php echo $category->getCurrentMBLPage()->getTitle(); ?></h1>
                <div class="description">
                    <p><?php echo $category->getCurrentMBLPage()->getDescription(); ?></p>
                </div>
            </div>
            <div class="content-overlay">
                <?php if (!$category->isPageOnlyPreview()) : ?>
                    <span class="doc-label opened"><?php _e('доступ открыт', 'mbl'); ?></span>
                <?php else : ?>
                    <span class="doc-label locked"><?php _e('доступ закрыт', 'mbl'); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </a>
</article>