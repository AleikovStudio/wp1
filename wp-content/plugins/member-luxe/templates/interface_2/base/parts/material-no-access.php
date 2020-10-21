<div class="post">
    <div class="ps_content mbl-no-access">
        <?php echo $mblPage->getNoAccessContent(); ?>

        <?php if (count($mblPage->getLevelMetasWithNoAccessContent()) > 1): ?>
            <script>
                $(function () {
                    setTimeout(function () {
                        $('[data-term-id]').each(function () {
                            var elem_id = $(this).attr('data-term-id');
                            var content = $('[data-term-id="' + elem_id + '"] .term-content').addClass('evaluate');
                            content.removeClass('evaluate');
                        });
                    }, 2000);
                    $(document).off('click', '[data-term-id] h3');
                    $(document).on('click', '[data-term-id] h3', function () {
                        var header = $(this);
                        var term_item = header.parents('[data-term-id]');
                        var term_id = term_item.attr('data-term-id');
                        var term_content = $('[data-term-id="' + term_id + '"] .term-content');

                        if (term_item.hasClass('active')) {
                            term_item.removeClass('active');
                            term_content.slideUp();
                        } else {
                            $('[data-term-id]').each(function () {
                                var inactive_item = $(this);
                                var inactive_term_id = inactive_item.attr('data-term-id');
                                var inactive_content = inactive_item.find('.term-content');

                                if (inactive_term_id !== term_id && inactive_item.hasClass('active')) {
                                    inactive_item.removeClass('active');
                                    inactive_content.slideUp();
                                }
                            });

                            term_item.addClass('active');
                            term_content.slideDown();
                        }
                    });
                });
            </script>
        <?php endif; ?>
    </div>
</div>