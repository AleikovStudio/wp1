<?php /** @var MBLCategory $category */ ?>
<?php /** @var MBLPage $mblPage */ ?>
<section class="lesson-row clearfix">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="lesson-tabs bordered-tabs white-tabs tabs-count-<?php echo $mblPage->getTabsCount(); ?>">
                    <?php if ($mblPage->getTabsCount() > 1) : ?>
                        <ul class="nav nav-tabs text-center" role="tablist">
                            <li role="presentation" class="active lesson-content-nav tab-1">
                                <a href="#lesson-content"
                                   aria-controls="lesson-content"
                                   role="tab"
                                   data-toggle="tab">
                                    <span class="icon-graduation-cap"></span>
                                    <span class="tab-label"><?php _e('Контент', 'mbl'); ?></span>
                                </a>
                            </li>
                            <?php if ($mblPage->hasHomework()) : ?>
                                <li role="presentation" class="lesson-tasks-nav tab-2">
                                    <a href="#lesson-tasks"
                                       aria-controls="lesson-tasks"
                                       role="tab"
                                       data-toggle="tab">
                                        <span id="response-status-tab-icon" class="icon-file-text-o <?php echo $mblPage->getHomeworkStatusClass(); ?>"></span>
                                        <span class="tab-label"><?php _e('Задание', 'mbl'); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($mblPage->hasAttachments()) : ?>
                                <li role="presentation" class="lesson-files-nav tab-<?php echo $mblPage->hasHomework() ? '3' : '2'; ?>">
                                    <a href="#lesson-files"
                                       aria-controls="lesson-files"
                                       role="tab"
                                       data-toggle="tab">
                                        <span class="icon-paperclip"></span>
                                        <span class="tab-label"><?php _e('Вложения', 'mbl'); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php wpm_render_partial('material-content', 'base', compact('mblPage')) ?>
                        <?php if ($mblPage->hasAccess()) : ?>
                            <?php if ($mblPage->hasHomework()) : ?>
                                <div role="tabpanel" class="tab-pane lesson-tasks" id="lesson-tasks">
                                    <?php wpm_render_partial('material-homework', 'base', compact('category', 'mblPage')) ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($mblPage->hasAttachments()) : ?>
                                <div role="tabpanel" class="tab-pane lesson-files" id="lesson-files">
                                    <?php wpm_render_partial('material-attachments', 'base', compact('mblPage')) ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php if ($mblPage->getId() != wpm_get_option('schedule_id') && $mblPage->hasAccess()) : ?>
                <?php wpm_render_partial('material-status-row', 'base', compact('category', 'mblPage')) ?>
            <?php endif; ?>
        </div>
        <?php if (comments_open() && (is_user_logged_in() || wpm_comments_is_visible()) && wpm_check_access() && $mblPage->getId() != wpm_get_option('schedule_id')) : ?>
            <div class="row">
                <div class="col-xs-12 wpm-comments-wrap">
                    <?php wpm_comments_wordpress(get_the_ID()) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>