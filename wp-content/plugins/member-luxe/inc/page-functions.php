<?php

function wpm_get_page($page_id)
{

    $user_id = get_current_user_id();
    $args = array(
        'post_type' => 'wpm-page',
        'page_id' => $page_id
    );
    wpm_enqueue_script('jquery-migrate', includes_url('js/jquery/jquery-migrate.js'));
    wpm_enqueue_script('wplink', includes_url('js/wplink.js'));
    did_action( 'init' ) && wp_localize_script( 'wplink', 'wpLinkL10n', array(
        'title' => __('Insert/edit link'),
        'update' => __('Update'),
        'save' => __('Add Link'),
        'noTitle' => __('(no title)'),
        'noMatchesFound' => __('No matches found.')
    ) );
    wpm_enqueue_script('wpdialogs', includes_url('js/wpdialog.js'));
    wpm_enqueue_style('editor_css', plugins_url('../css/editor-frontend.css', __FILE__));

    $page = new WP_Query($args);
    if ($page->have_posts()): while ($page->have_posts()):
        $page->the_post();


        $page_meta = get_post_meta(get_the_ID(), '_wpm_page_meta', true);

        if (!empty($page_meta)) $descriptions = $page_meta['description'];
        else $descriptions = '$descriptions';

        remove_all_actions('the_content');
        remove_all_filters('the_content');
        add_filter('the_content', 'wpautop');
        add_filter('the_content', 'do_shortcode');
        add_filter('the_content', 'wpm_add_infoprotector_key_to_url');
        //add_filter('the_content', 'wpm_remove_protocol_from_text');

        $accessible_levels = wpm_get_all_user_accesible_levels($user_id);

        $has_access = wpm_check_access($page_id, $accessible_levels);

        $main_options = get_option('wpm_main_options');
        $date_is_hidden = wpm_date_is_hidden($main_options);

        if ($has_access) {
            if (wpm_text_protection_is_enabled($main_options, get_the_ID())) {
                ?>

                <style type="text/css">
                    .wpm-content {
                        -webkit-user-select: none;
                        -moz-user-select: -moz-none;
                        -ms-user-select: none;
                        user-select: none;
                    }
                </style>
                <script>
                    $(".wpm-content").on("contextmenu", function (event) {
                        event.preventDefault();
                    });
                </script>
            <?php } ?>
            <div class="wpm-page-header-wrap">
                <div class="wpm-page-header">
                    <div class="info-row row">
                        <div class="col-label col-lg-3 col-md-4 col-sm-4 col-xs-4">
                            <span class="icon-pencil"></span>
                            <?php _e("Название", "wpm"); ?>
                        </div>
                        <div class="col-info col-lg-9 col-md-8 col-sm-8 col-xs-8">
                            <h1 class="title">
                                <?php the_title(); ?>
                            </h1>
                        </div>
                    </div>
                    <?php if(!empty($descriptions)): ?>
                    <div class="info-row row">
                        <div class="col-label col-lg-3 col-md-4 col-sm-4 col-xs-4">
                            <span class="icon-file"></span>
                            <?php _e("Краткое описание", "wpm"); ?>
                        </div>
                        <div class="col-info col-lg-9 col-md-8 col-sm-8 col-xs-8">
                            <div class="description">
                                <?php echo $descriptions; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (!$date_is_hidden): ?>
                    <div class="info-row row">
                        <div class="col-label col-lg-3 col-md-4 col-sm-4 col-xs-4">
                            <span class="icon-calendar2"></span> <?php _e("Дата", "wpm"); ?>
                        </div>
                        <div class="col-info col-lg-9 col-md-8 col-sm-8 col-xs-8">
                            <div class="description col-lg-6 col-md-5 col-sm-5 col-xs-12">
                                <span class="date"><?php echo get_the_date(); ?></span>
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <?php $design_options = get_option('wpm_design_options');?>
                                <?php $back_btn_text = array_key_exists('text', $design_options['buttons']['back']) ? $design_options['buttons']['back']['text'] : 'Вернуться к списку материалов';?>
                                <button
                                    class="wpm-button back-button pull-right"><?php _e($back_btn_text, "wpm"); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="info-row row">
                            <div class="col-label col-xs-12">
                                <?php $design_options = get_option('wpm_design_options');?>
                                <?php $back_btn_text = array_key_exists('text', $design_options['buttons']['back']) ? $design_options['buttons']['back']['text'] : 'Вернуться к списку материалов';?>
                                <button
                                    class="wpm-button back-button pull-right"><?php _e($back_btn_text, "wpm"); ?></button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="wpm-content wpm-content-text">
                <?php the_content();
                echo get_interkassa_form($page_meta);
                ?>
            </div>
            <?php wpm_include_partial('homework_block'); ?>
        <?php

        } else {
            $term_list = wp_get_post_terms(get_the_ID(), 'wpm-levels', array("fields" => "ids"));
            $taxonomy_term_metas = array();

            foreach ($term_list AS $_term_id) {
                $_taxonomy_term_meta = get_option("taxonomy_term_$_term_id");
                if($_taxonomy_term_meta && !empty($_taxonomy_term_meta['no_access_content'])) {
                    $taxonomy_term_metas[$_term_id] = $_taxonomy_term_meta;
                }
            }

            $no_access_content = '';

            if (count($taxonomy_term_metas) > 1) {
                $no_access_content .= '<h2 class="accordion-title">Материал доступен для следующих тарифных планов:</h2>';
                $no_access_content .= '<div id="no-access-content">';

                foreach ($taxonomy_term_metas as $term_id => $taxonomy_term_meta) {

                    $term = get_term($term_id, 'wpm-levels');

                    $no_access_content .= '<div data-term-id="' . $term_id . '">' .
                                              '<h3>' . $term->name . '</h3>' .
                                              '<div class="term-content">'. stripslashes($taxonomy_term_meta['no_access_content']) . '</div>' .
                                          '</div>';
                }

                $no_access_content .= '</div>';

            } else {
                foreach ($taxonomy_term_metas as $term_id => $taxonomy_term_meta) {
                    $taxonomy_term_meta = get_option("taxonomy_term_$term_id");
                    $no_access_content .= stripslashes($taxonomy_term_meta['no_access_content']);
                }
            }

            ?>
            <div class="wpm-page-header-wrap">
                <div class="wpm-page-header">
                    <div class="info-row row">
                        <div class="col-label col-lg-3 col-md-4 col-sm-4 col-xs-4">
                            <span class="icon-pencil"></span>
                            <?php _e("Название", "wpm"); ?>
                        </div>
                        <div class="col-info col-lg-9 col-md-8 col-sm-8 col-xs-8">
                            <h1 class="title">
                                <?php the_title(); ?>
                            </h1>
                        </div>
                    </div>
                    <div class="info-row row">
                        <div class="col-label col-lg-3 col-md-4 col-sm-4 col-xs-4">
                            <span class="icon-file"></span>
                            <?php _e("Краткое описание", "wpm"); ?>
                        </div>
                        <div class="col-info col-lg-9 col-md-8 col-sm-8 col-xs-8">
                            <div class="description">
                                <?php echo $descriptions; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!$date_is_hidden): ?>
                    <div class="info-row row">
                        <div class="col-label col-lg-3 col-md-4 col-sm-4 col-xs-4">
                            <span class="icon-calendar2"></span> <?php _e("Дата", "wpm"); ?>
                        </div>
                        <div class="col-info col-lg-9 col-md-8 col-sm-8 col-xs-8">
                            <div class="description col-lg-6 col-md-5 col-sm-5 col-xs-12">
                                <span class="date"><?php echo get_the_date(); ?></span>
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <?php $design_options = get_option('wpm_design_options');?>
                                <?php $back_btn_text = array_key_exists('text', $design_options['buttons']['back']) ? $design_options['buttons']['back']['text'] : 'Вернуться к списку материалов';?>
                                <button
                                    class="wpm-button back-button pull-right"><?php _e($back_btn_text, "wpm"); ?></button>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="info-row row">
                            <div class="col-label col-xs-12">
                                <?php $design_options = get_option('wpm_design_options');?>
                                <?php $back_btn_text = array_key_exists('text', $design_options['buttons']['back']) ? $design_options['buttons']['back']['text'] : 'Вернуться к списку материалов';?>
                                <button
                                    class="wpm-button back-button pull-right"><?php _e($back_btn_text, "wpm"); ?></button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="wpm-content no-access-content wpm-content-text">
                <div class="post">
                    <div class="ps_content ">
                        <?php
                        echo apply_filters('the_content', $no_access_content);
                        ?>

                        <?php if (count($taxonomy_term_metas) > 1): ?>
                            <script>
                                $(function() {
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

                                                if(inactive_term_id!==term_id && inactive_item.hasClass('active')) {
                                                    inactive_item.removeClass('active');
                                                    inactive_content.slideUp();
                                                }
                                            });

                                            term_item.addClass('active')
                                            term_content.slideDown();
                                        }

                                    });
                                });
                            </script>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        <?php
        }

    endwhile;
    endif;

}


function get_interkassa_form($page_meta)
{
    if(!is_page() || !is_single() || !is_array($page_meta['interkassa'])) return false;


    $product_title = array_key_exists('name',$page_meta['interkassa']);
    echo '$product_title = '.$product_title;
    $shop_id = $page_meta['interkassa']['id'];
    $price = $page_meta['interkassa']['price'];
    $currency = $page_meta['interkassa']['currency'];
    $desc = $page_meta['interkassa']['desc'];
    $payment_id = time() . (number_format((double)microtime(),4) * 10000);

    $interkassa_fields_id = $page_meta['interkassa']['show_fields'];
    $interkassa_fields = array(
        array('Ф.И.О' => 'Ваше имя'),
        array('Страна' => 'Ваша страна'),
        array('Город' => 'Ваш город'),
        array('Адрес' => 'Ваш адрес'),
        array('Индекс' => 'Ваш индекс'),
        array('Телефон' => 'Ваш телефон'),
        array('E-mail' => 'Ваш e-mail')
    );
    $product_thml = '
<div style="display:none">
    <div id="order_popup" class="interkassa-popup-wrap">
        <div class="p_content">
            <h2 class="p_title">' . $product_title . '</h2>

            <div class="p_thumb">' . get_the_post_thumbnail(get_the_ID(), array(150, 150), false) . '</div>
            <div class="p_info" style="text-align: center;">';

    if (!empty($price)) {
        $product_thml .= '
                <div>Цена: <span class="price">' . $price . '</span><span class="currency"> ' . $currency . '</span>
                </div>
                                                            ';
    }
    if (!empty($desc)) {
        $product_thml .= '
                <div>Описание: ' . $desc . '</div>
                                                            ';
    }
    $product_thml .= '
            </div>
            <form id="ik_form" name="payment" method="post" action="https://sci.interkassa.com/" enctype="utf-8"
                  target="_blank">
                <input type="hidden" name="ik_co_id" value="' . $shop_id . '"/>
                <input type="hidden" name="ik_pm_no" value="' . $payment_id . '"/>
                <input type="hidden" name="ik_am" value="' . $price . '"/>
                <input type="hidden" name="ik_desc" value="' . $product_title . '"/>';

    if (!empty($interkassa_fields_id)) {
        $product_thml .= '<span>Заполните форму</span><br/>';
        foreach ($interkassa_fields_id as $i) {
            if(empty($i)) $i = 0;
                $product_thml .= '<label>' . $i . '
                    <input type="text" name="ik_x_ik_x_baggage_' . $i . '"
                           placeholder="" value=""/></label><br/>';
        }
    }
    $product_thml .= '<input type="submit" id="ik_submit" value="Оплатить"></form>
        </div>
    </div>
</div>';

    return $product_thml;
}
