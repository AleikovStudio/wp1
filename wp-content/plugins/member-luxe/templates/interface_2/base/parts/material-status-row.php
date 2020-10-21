<?php /** @var MBLCategory $category */ ?>
<?php /** @var MBLPage $mblPage */ ?>
<div class="col-xs-12">
    <div class="reading-status-row form-group">
        <?php if ($mblPage->hasPrevPostLink()) : ?>
            <div class="left-wrap">
                <a href="<?php echo $mblPage->getPrevPostLink(); ?>"
                   class="prev ui-icon-wrap"
                   title="<?php _e('Предыдущий урок', 'mbl'); ?>">
                    <span class="iconmoon icon-chevron-left"></span>
                    <span class="arrow-label"> <?php _e('Предыдущий урок', 'mbl'); ?></span>
                </a>
            </div>
        <?php endif; ?>
        <div class="status-wrap">
            <label class="status-toggle-wrap ui-icon-wrap"
                   data-passed="<?php echo $category->getProgressPassed($mblPage->isPassed()); ?>"
                   data-not-passed="<?php echo $category->getProgressNotPassed($mblPage->isPassed()); ?>"
                   data-id="<?php echo $mblPage->getId(); ?>">
                <?php if (!$mblPage->hasHomework() && $mblPage->hasAccess()) : ?>
                    <span class="iconmoon icon-toggle-<?php echo $mblPage->isPassed() ? 'on' : 'off'; ?>"></span>
                    <input type="checkbox" name="reading-status" value="" <?php echo $mblPage->isPassed() ? 'checked="checked"' : ''; ?>>
                <?php endif; ?>
                <span class="toggle-label">
                    <?php if ($mblPage->isPassed()) : ?>
                        <?php _e('Пройден', 'mbl'); ?>
                    <?php else : ?>
                        <?php _e('Не пройден', 'mbl'); ?>
                    <?php endif; ?>
                </span>
            </label>
        </div>
        <div class="progress-wrap">
            <?php if ($category->showProgressOnStatusRow()) : ?>
                <div class="course-progress-wrap">
                    <div class="progress"
                         title="<?php _e('Пройдено', 'mbl'); ?> <?php echo $category->getProgress(); ?>%"
                    >
                        <div
                            class="progress-bar progress-bar-success"
                            role="progressbar"
                            aria-valuenow="<?php echo $category->getProgress(); ?>"
                            style="width: <?php echo $category->getProgress(); ?>%"
                        >
                            <span class="sr-only">
                                <?php echo $category->getProgress(); ?>%
                                <?php _e('Пройдено уроков', 'mbl'); ?>
                            </span>
                        </div>
                    </div>
                    <div class="progress-count">
                        <?php echo $category->getProgress(); ?>%
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="right-wrap">
            <?php if ($mblPage->hasNextPostLink() && $category->getAutotrainingView()->hasNextPosts($mblPage)) : ?>
                <a href="<?php echo $mblPage->getNextPostLink(); ?>"
                   class="next ui-icon-wrap"
                   title="<?php _e('Следующий урок', 'mbl'); ?>">
                    <span class="arrow-label"><?php _e('Следующий урок', 'mbl'); ?></span>
                    <span class="iconmoon icon-chevron-right"></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
