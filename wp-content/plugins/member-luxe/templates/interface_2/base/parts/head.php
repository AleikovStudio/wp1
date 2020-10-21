<!DOCTYPE html>
<?php
$main_options   = get_option('wpm_main_options');
$design_options = get_option('wpm_design_options');

$yt_protection_is_enabled = wpm_yt_protection_is_enabled();
$home_url = get_permalink(wpm_get_option('home_id'));

//---------
$wpm_head_code = $wpm_body_code = $wpm_footer_code = '';
$protected_body_class = post_password_required() ? 'protected' : '';
if(is_single()){
    $page_meta = get_post_meta($post->ID, '_wpm_page_meta', true);

    if(isset($page_meta['code'])){
        $wpm_head_code = stripcslashes(wpm_prepare_val($page_meta['code']['head']));
        $wpm_body_code = stripcslashes(wpm_prepare_val($page_meta['code']['body']));
        $wpm_footer_code = stripcslashes(wpm_prepare_val($page_meta['code']['footer']));
    }else{
        $wpm_head_code = $wpm_body_code = $wpm_footer_code = '';
    }

}
//----------

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
    <meta charset="utf-8">
    <meta name="theme-color" content="#ffffff">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, minimal-ui">

    <?php
    $wpm_favicon = wpm_remove_protocol(wpm_get_option('favicon.url'));
    if (!empty($wpm_favicon)) {
        $ext = pathinfo($wpm_favicon, PATHINFO_EXTENSION);
        if ($ext == 'ico') echo '<link rel="shortcut icon" href="' . $wpm_favicon . '" />';
        if ($ext == 'png') echo ' <link rel="icon" type="image/png" href="' . $wpm_favicon . '" />';
    } ?>
    <title><?php

        $wp_title = substr(wp_title(' | ', false, 'right'), 0, -3);
        if(is_home() || is_front_page()) echo $wp_title;
        elseif(is_archive() || is_category()) single_cat_title();
        elseif(wpm_is_search_page()) _e('Результаты поиска', 'mbl');
        elseif(wpm_is_activation_page()) _e('Активация', 'mbl');
        else the_title();

        ?></title>
    <?php wpm_render_partial('js-app-init') ?>
    <?php wpm_head(); ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('/admin-ajax.php'); ?>';
        jQuery(function ($) {
            $('.interkassa-payment-button').fancybox({
                'padding': '20',
                'type': 'inline',
                'href': '#order_popup'
            });
            $('.fancybox').fancybox();

        });
    </script>
    <?php
    if(is_user_logged_in()){
        $current_user = wp_get_current_user();
        if (is_array($current_user->roles) && in_array('customer', $current_user->roles) && wpm_option_is('protection.one_session.status', 'on')) {
            ?>
            <script type="text/javascript">
                jQuery(function($){
                    window.setInterval(wpm_check_auth, <?php echo wpm_get_option('protection.one_session.interval') * 1000; ?>);
                    function wpm_check_auth() {
                        $.ajax({
                            type: 'POST',
                            url: ajaxurl,
                            dataType: 'json',
                            data: {
                                action: "wpm_auth_check_action",
                                user_id: <?php echo get_current_user_id(); ?>
                            },
                            success: function (data) {

                                if(data.auth == false){
                                    $('#user-auth-fail').modal('show');
                                    window.setTimeout(function(){window.location.href = "<?php echo $home_url; ?>"}, 7000);
                                }
                            },
                            error: function (errorThrown) {}
                        });
                    }
                });
            </script>
        <?php } // end if role is customer
    } // end if_user_logged_in ?>

    <?php wpm_render_partial('video-header') ?>
    <?php wpm_render_partial('extra-styles') ?>

    <!-- wpm head code -->
    <?php echo $wpm_head_code; ?>
    <!-- / wpm head code -->
    <!-- wpm global head code -->
    <?php echo stripslashes(wpm_get_option('header_scripts')); ?>
    <!-- / wpm global head code -->
</head>
<body
<?php body_class($protected_body_class . ' fix-top-nav home'); ?>
<?php echo ' ' . $wpm_body_code; ?>
<?php echo wpm_option_is('protection.right_button_disabled', 'on') ? 'oncontextmenu="return false;"' : '' ?>
>