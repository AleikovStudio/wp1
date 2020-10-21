<?php $mblHeader = new MBLHeader(); ?>

<?php if ($mblHeader->showHeader()) : ?>
    <?php if ($mblHeader->getHeaderLink() && $mblHeader->isVisible()) : ?>
        <a
            href="<?php echo $mblHeader->getHeaderLink(); ?>"
            target="_<?php echo $mblHeader->getHeaderLinkTarget(); ?>"
            class="brand-row"
            style="background-image: url('<?php echo $mblHeader->getHeaderImage(); ?>')"
        >
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center flex-logo-wrap">
                        <?php if ($mblHeader->hasLogo()) : ?>
                            <span class="wpm_default_header">
                                <img class='brand-logo' src="<?php echo $mblHeader->getLogo(); ?>" alt="logo">
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </a>
    <?php else : ?>
        <section
            class="brand-row"
            <?php if ($mblHeader->isVisible()) : ?>
                style="background-image: url('<?php echo $mblHeader->getHeaderImage(); ?>')"
            <?php else : ?>
                style="background: transparent;"
            <?php endif; ?>
        >
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center flex-logo-wrap">
                        <?php if ($mblHeader->hasLogo()) : ?>
                            <a href="<?php echo get_permalink(wpm_get_option('home_id')); ?>" class="wpm_default_header">
                                <img class='brand-logo' src="<?php echo $mblHeader->getLogo(); ?>" alt="logo">
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>