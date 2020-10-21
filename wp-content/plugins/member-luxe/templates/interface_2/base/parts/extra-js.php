<script type="text/javascript">
    jQuery(function ($) {
        (function () {
            var hash = document.location.hash;
            var prefix = "tab-";
            if ($('section.lesson-row').length) {
                if (hash) {
                    $('.nav-tabs a[href="' + hash.replace(prefix, "") + '"]').tab('show');
                }

                $('.nav-tabs a').on('shown.bs.tab', function (e) {
                    window.location.hash = e.target.hash.replace("#", "#" + prefix);
                });
            }
        })();
        (function () {
            truncate($('.folder-content .title'), 110);

            function truncate($elem, height) {
                $elem.each(function () {
                    var $this = $(this);

                    $this.data('txt', $this.html());
                    $this.shave(height);

                    $(window).on('resize', function () {
                        $this.html($this.data('txt'));
                        $this.shave(height);
                    });
                });
            }

            $('.material-item .content-wrap .description').each(function () {
                var $this = $(this);

                if ($this.text().trim().length) {
                    truncateDescription($this);

                    $(window).on('resize', function () {
                        $this.html($this.data('txt'));
                        truncateDescription($this);
                    });
                }
            });

            function truncateDescription($this) {
                var $wrap = $this.closest('.content-wrap'),
                    $title = $wrap.find('> .title'),
                    height = $wrap.height() - $title.height() - 20;

                $this.data('txt', $this.html());
                $this.shave(height);
            }
        })();
        (function () {
            $('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]').fancybox();
        })();
    });
</script>