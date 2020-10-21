<?php
include_once('pin_code_header.php');
?>
<?php if (is_user_logged_in() || $main_options['main']['opened'] == 'on') : ?>
    <div class="wpm-nav-bar-wrap wpm-top-admin-bar">
        <div class="wpm-nav-bar hidden-xs">
            <?php include_once('top-nav-bar.php'); ?>
        </div>
        <div class="visible-xs">
            <?php include_once('top-nav-bar-mobile.php'); ?>
        </div>
    </div>
<?php endif; ?>

<?php
if ($main_options['header']['visible'] == 'on') {
    $page_header = '';
    if ($main_options['headers']['headers']['pincodes']['disabled'] != 'disabled') {
        $page_header = $main_options['headers']['headers']['pincodes']['content'];
    }
    if (!empty($page_header)) {
        ?>
        <div class="container container-content-wrap">
            <div class="row">
                <div class="col-xs-12 header-wrap">
                    <div class="header-content wpm-content-text">
	                    <?php echo apply_filters('the_content', stripslashes($page_header)); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
} ?>
<div class="container container-content-wrap wpm-pin-code-page">

    <div class="row">
        <div class="content-col wpm-single">
            <div id="wpm-content">
                <div class="wpm-content" style="margin-top: 0">
                    <div class="pin-code-success">
                        <a href="#" class="get-code-button wpm-get-pin-code-button" id="get-pin-code"><?php echo $design_options['buttons']['get_pin']['text']; ?></a>
                        <input type="text" id="wpm-pin-code" disabled="disabled" value=""/>
                        <button type="button" class="wpm-button wpm-copy-key wpm-copy-pin-code-button"
                               data-clipboard-target="wpm-pin-code"><?php echo $design_options['buttons']['copy_pin']['text']; ?>
                        </button>
                        <?php if (!is_user_logged_in() && $main_options['main']['opened'] != 'on') : ?>
                            <a class="wpm-button wpm-register-on-pin-button"
                               href="<?php echo utf8_encode(get_permalink($main_options['home_id'])) ?>#registration"><?php echo $design_options['buttons']['register_on_pin']['text']; ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="pin-code-error" style="display: none"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        var copy_keys = new ZeroClipboard($('.wpm-copy-key'), {
            moviePath: "<?php echo plugins_url('/member-luxe/js/zeroclipboard/ZeroClipboard.swf') ?>"
        });
        $('#get-pin-code').on('click', function () {
            var $this = $(this);
            $.post(ajaxurl, {action: 'wpm_get_pin_code_action'}, function (data) {
                if (data.success && data.code) {
                    $this.css({visibility: 'hidden'});
                    $('#wpm-pin-code').val(data.code);
                } else {
                    $('.pin-code-success').hide();
                    $('.pin-code-error').html(data.error).show();
                }
            }, "json");
        });
    });
</script>

<?php
?>
<?php include_once('footer.php'); ?>
<?php wpm_footer(); ?>
<!-- wpm footer code -->
<?php echo $wpm_footer_code; ?>
<!-- / wpm footer code -->
<?php echo get_option('wpm-analytics'); ?>
</body>
</html>