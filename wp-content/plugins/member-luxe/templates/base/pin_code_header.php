<!DOCTYPE html>
<?php

global $post;

$main_options   = get_option('wpm_main_options');
$design_options = get_option('wpm_design_options');

$yt_protection_is_enabled = wpm_yt_protection_is_enabled();

$wpm_head_code = stripcslashes(get_post_meta($post->ID, '_wpm_head_code', true));
$wpm_body_code = stripcslashes(get_post_meta($post->ID, '_wpm_body_code', true));
$wpm_footer_code = stripcslashes(get_post_meta($post->ID, '_wpm_footer_code', true));

?>
<html
<?php language_attributes(); ?>
xmlns:og="http://ogp.me/ns#"
itemscope
itemtype="http://schema.org/Article"
<?php echo wpm_option_is('protection.right_button_disabled', 'on') ? 'oncontextmenu="return false;"' : '' ?>
>
<head>
    <meta name="generator" content="wpm <?php echo WP_MEMBERSHIP_VERSION; ?> | http://wpm.wppage.ru"/>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width"/>
    <?php
    $wpm_favicon = wpm_remove_protocol(wpm_get_option('favicon.url'));
    if (!empty($wpm_favicon)) {
        $ext = pathinfo($wpm_favicon, PATHINFO_EXTENSION);
        if ($ext == 'ico') echo '<link rel="shortcut icon" href="' . $wpm_favicon . '" />';
        if ($ext == 'png') echo ' <link rel="icon" type="image/png" href="' . $wpm_favicon . '" />';
    } ?>

    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="description" content="<?php echo $desc; ?>">
    <title><?php echo _('Получение пин-кода') ?></title>
    <?php
    wpm_head();
    ?>

    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';

        function changeLinks (children)
        {
            var li_class = children.parent('.cat-item').attr('class').replace(' current-cat-parent', '')
                                                                     .replace('cat-item ', '.');

            $(li_class + ' > a').attr('href', '#');

            $(document).on('click', li_class + ' > a', function () {
                $(li_class + ' .plus').click();
            });
        }

        jQuery(function ($) {
            //============
            $('.main-menu .children').each(function () {
                var children = $(this);
                if (children.is(':visible')) {
                    children.before('<span class="plus">-</span>');
                    changeLinks(children);
                } else {
                    children.before('<span class="plus">+</span>');
                    changeLinks(children);
                }
            });
            $('.main-menu .plus').on('click', function () {
                var plus = $(this);
                var childern = $(this).next('.children');
                $(this).next('.children').slideToggle('fast', function () {
                    if (childern.is(':visible')) {
                        plus.html('-');
                    } else {
                        plus.html('+');
                    }
                });
            });
            $('.interkassa-payment-button').fancybox({
                'padding': '20',
                'type': 'inline',
                'href': '#order_popup'
            });
            $('a[href$=".jpg"],a[href$=".png"],a[href$=".gif"]').fancybox();
        });
    </script>

    <?php include(__DIR__ . '/video_header.php'); ?>

    <!-- wpm head code -->
    <?php echo $wpm_head_code; ?>
    <!-- / wpm head code -->
    <?php
    if(array_key_exists('header_scripts', $main_options)){ ?>
        <!-- wpm global head code -->
        <?php echo stripslashes($main_options['header_scripts']); ?>
        <!-- // wpm global head code -->
    <?php } ?>
</head>
<body
<?php echo ' ' . $wpm_body_code; ?>
<?php echo wpm_option_is('protection.right_button_disabled', 'on') ? 'oncontextmenu="return false;"' : '' ?>
>
<?php
if (is_user_logged_in() || $main_options['main']['opened'] == 'on') echo '<div style="height: 32px"></div>';
include_once(WP_PLUGIN_DIR . '/member-luxe/inc/theme-settings.php'); ?>
