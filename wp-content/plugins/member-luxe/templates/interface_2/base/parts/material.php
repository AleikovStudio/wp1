<?php /** @var MBLCategory $category */ ?>
<?php $category->getAutotrainingView()->updateAccessMeta($category->getCurrentMBLPage()->hasAccess()); ?>
<article class="material-item <?php echo $category->isPageOnlyPreview() ? 'material-inaccessible' : (!$category->getCurrentMBLPage()->hasAccess() ? 'material-closed' : 'material-opened'); ?> <?php echo $category->isMaterialPassed() ? 'material-done' : ''; ?>">
    <a class="flex-wrap"
       <?php if (!$category->isPageOnlyPreview()) : ?>
           href="<?php echo $category->getCurrentPageLink(); ?>"
       <?php endif; ?>
    >
        <div class="col-thumb <?php echo $category->isMaterialPassed() ? 'done' : ''; ?>">
            <div
                class="thumbnail-wrap"
                <?php if ($category->getCurrentMBLPage()->hasBackgroundImage()) : ?>
                    style="background-image: url('<?php echo $category->getCurrentMBLPage()->getBackgroundImage() ?>');"
                <?php endif; ?>
            >
                <div class="icons-top">
                    <div class="icons">
                        <?php if ($category->getCurrentMBLPage()->showNumber()) : ?>
                            <?php $iterator = isset($iterator) ? $iterator : $category->getAutotrainingView()->postIterator; ?>
                            <span class="m-icon count"># <?php echo $iterator; ?></span>
                        <?php endif; ?>
                        <?php if ($category->getCurrentMBLPage()->showContentType()) : ?>
                            <?php if ($category->getCurrentMBLPage()->hasVideoContent()) : ?>
                                <span class="m-icon video"><span class="icon-video-camera"></span></span>
                            <?php endif; ?>
                            <?php if ($category->getCurrentMBLPage()->hasAudioContent()) : ?>
                                <span class="m-icon audio"><span class="icon-volume-up"></span></span>
                            <?php endif; ?>
                            <?php if ($category->getCurrentMBLPage()->hasTextContent()) : ?>
                                <span class="m-icon file"><span class="icon-file-text"></span></span>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($category->getCurrentMBLPage()->showAccess()) : ?>
                            <span class="status-icon"><span class="icon-<?php echo !$category->isPageOnlyPreview() && $category->getCurrentMBLPage()->hasAccess() ? 'unlock' : 'lock'; ?>"></span></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($category->showCurrentMaterialBottomIcons()) : ?>
                    <div class="icons-bottom">
                        <div class="icons">
                            <?php if ($category->isAutotraining() && $category->getCurrentMBLPage()->hasHomework() && $category->getCurrentMBLPage()->showHomeworkStatus()) : ?>
                                <span class="status <?php echo $category->getHomeworkStatusClass(); ?>"><span class="icon-file-text-o"></span>
                                    <?php echo $category->getHomeworkStatusText(); ?>
                                </span>
                            <?php endif; ?>
                            <div class="right-icons">
                                <?php if ($category->getCurrentMBLPage()->showComments() && $category->getCurrentMBLPage()->getCommentsNumber()) : ?>
                                    <span class="comments"><span class="icon-comment-o"></span> <?php echo $category->getCurrentMBLPage()->getCommentsNumber(); ?></span>
                                <?php endif; ?>
                                <?php if ($category->getCurrentMBLPage()->showDate()) : ?>
                                    <span class="date"><span class="icon-calendar-o"></span> <?php echo mysql2date( get_option( 'date_format' ), $category->getCurrentMBLPage()->getPost()->post_date); ?></span>
                                <?php endif; ?>
                                <?php if ($category->getCurrentMBLPage()->showViews()) : ?>
                                    <span class="views"><span class="icon-eye"></span> <?php echo $category->getCurrentMBLPage()->getViewsNumber(); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-content <?php echo $category->isMaterialPassed() ? 'done' : ''; ?>">
            <div class="content-wrap">
                <h1 class="title"><?php echo $category->getCurrentMBLPage()->getTitle(); ?></h1>
                <div class="description">
                    <p><?php echo $category->getCurrentMBLPage()->getDescription(); ?></p>
                </div>
            </div>
            <div class="content-overlay">
                <?php if (!$category->isPageOnlyPreview() && $category->getCurrentMBLPage()->hasAccess()) : ?>
                    <span class="doc-label opened"><?php _e('доступ открыт', 'mbl'); ?></span>
                <?php elseif($category->isPageOnlyPreview()) : ?>
                    <span class="doc-label locked"><?php _e('пройдите предыдущий урок', 'mbl'); ?></span>
                <?php else : ?>
                    <span class="doc-label locked"><?php _e('доступ закрыт', 'mbl'); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </a>
</article>