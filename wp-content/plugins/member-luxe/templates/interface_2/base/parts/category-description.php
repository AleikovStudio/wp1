<?php /** @var MBLCategory $category */ ?>
<?php if ($category->showTitleRow()) : ?>
    <section class="page-title-row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h1 class="page-title"><?php echo $category->getName(); ?></h1>
                    <?php if ($category->hasShortDescription()) : ?>
                        <div class="page-description">
                            <p><?php echo $category->getShortDescription(); ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($category->hasDescription() && $category->showDescription()) : ?>
                        <div class="page-description-content <?php echo $category->expandDescription() ? 'visible' : ''; ?>">
                            <?php if (!$category->showDescriptionAlways()) : ?>
                                <button
                                    type="button"
                                    name="button"
                                    class="mbr-btn btn-small btn-bordered btn-rounded btn-gray toggle-category-description-button <?php echo $category->expandDescription() ? 'active' : ''; ?>">
                                    <span class="text"><?php _e('подробнее', 'mbl') ?></span>
                                    <span class="iconmoon <?php echo $category->expandDescription() ? 'icon-close' : 'icon-angle-down'; ?>"></span>
                                </button>
                            <?php endif; ?>
                            <div class="content" <?php echo $category->expandDescription() ? 'style="display:block;"' : ''; ?>>
                                <?php echo term_description(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>