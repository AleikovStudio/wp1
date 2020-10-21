<?php /** @var MBLPaginationInterface $pager */ ?>
<?php if ($pager->hasToPaginate()) : ?>
    <section class="pagenavi-row">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="wp-pagenavi">
                        <?php if ($pager->getCurrentPage() > 1) : ?>
                            <a class="prevpostslink" rel="prev" href="<?php echo $pager->getPrevPageLink(); ?>"><span class="icon-long-arrow-left"></span> <?php _e('Предыдущая', 'mbl'); ?></a>
                        <?php endif; ?>
                        <div class="pages">
                            <?php if ($pager->getCurrentPage() == 1) : ?>
                                <span class="current">1</span>
                            <?php else : ?>
                                <a class="page" href="<?php echo $pager->getFirstPageLink(); ?>">1</a>
                            <?php endif; ?>

                            <?php if ($pager->getCurrentPage() > 4 && $pager->getTotalPages() > 6) : ?>
                                <span class="extend">...</span>
                            <?php endif; ?>

                            <?php $startLoop = $pager->getCurrentPage() <= 4 ? 2 : $pager->getCurrentPage() - 1;  ?>
                            <?php
                                $endLoop = $pager->getTotalPages() - $pager->getCurrentPage() > 3
                                    ? min($pager->getTotalPages() - 1, max($pager->getCurrentPage() + 1, 5))
                                    : $pager->getTotalPages() - 1;
                            ?>
                            <?php for ($i = $startLoop; $i <= $endLoop; $i++) : ?>
                                <?php if ($pager->getCurrentPage() == $i) : ?>
                                    <span class="current"><?php echo $i; ?></span>
                                <?php else : ?>
                                    <a class="page" href="<?php echo $pager->getPageLink($i); ?>"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if (($pager->getTotalPages() - $pager->getCurrentPage()) > 3 && $pager->getTotalPages() > 6) : ?>
                                <span class="extend">...</span>
                            <?php endif; ?>

                            <?php if ($pager->getCurrentPage() < $pager->getTotalPages()) : ?>
                                <a class="page" href="<?php echo $pager->getLastPageLink(); ?>"><?php echo $pager->getTotalPages(); ?></a>
                            <?php else : ?>
                                <span class="current"><?php echo $pager->getTotalPages(); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($pager->getCurrentPage() < $pager->getTotalPages()) : ?>
                            <a class="nextpostslink" rel="next" href="<?php echo $pager->getNextPageLink(); ?>"><?php _e('Следующая', 'mbl'); ?> <span class="icon-long-arrow-right"></span></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>