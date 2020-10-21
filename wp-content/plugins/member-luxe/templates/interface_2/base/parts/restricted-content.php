<div class="container">
    <div class="row">
        <div class="col-xs-12 text-center">
            <div class="page-description-content <?php echo wpm_option_is('login_content_opened', 'on') ? 'visible' : ''; ?>">
                <?php if (!wpm_option_is('login_content_opened', 'on')) : ?>
                    <button type="button"
                            name="button"
                            class="mbr-btn btn-small btn-bordered btn-rounded btn-gray toggle-category-description-button">
                        <span class="text"><?php _e('подробнее', 'mbl') ?></span>
                        <span class="iconmoon icon-angle-down"></span>
                    </button>
                <?php endif; ?>
                <div class="content" <?php echo wpm_option_is('login_content_opened', 'on') ? 'style="display:block;"' : ''; ?>>
                    <?php echo apply_filters('the_content', wpm_get_option('login_content.content')); ?>
                </div>
            </div>
        </div>
    </div>
 </div>