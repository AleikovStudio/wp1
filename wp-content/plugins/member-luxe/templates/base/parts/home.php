<?php

$page_meta = get_post_meta(get_the_ID(), '_wpm_page_meta', true);

if (!empty($page_meta)) $descriptions = $page_meta['description'];
else $descriptions = '$descriptions';

remove_all_actions('the_content');
remove_all_filters('the_content');
add_filter('the_content', 'wpautop');
add_filter('the_content', 'do_shortcode');
//add_filter('the_content', 'wpm_remove_protocol_from_text');

$accessible_levels = wpm_get_all_user_accesible_levels($user_id);

$has_access = wpm_check_access(get_the_ID(), $accessible_levels);

$main_options = get_option('wpm_main_options');
$date_is_hidden = wpm_date_is_hidden($main_options);


//-------
$term_link = '';
if(is_archive() && is_single()){
    $terms = wp_get_post_terms(get_the_ID(), 'wpm-category', array('fields' => 'all'));
    $term_link = get_term_link($terms[0], 'wpm-category');
}
//------------

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
    <div class="wpm-content home-page wpm-content-text">
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
        if ($_taxonomy_term_meta && !empty($_taxonomy_term_meta['no_access_content'])) {
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
                '<div class="term-content">' . stripslashes($taxonomy_term_meta['no_access_content']) . '</div>' .
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
            <div class="info-row row">
                <div class="col-label col-lg-3 col-md-4 col-sm-4 col-xs-4">
                    <span class="icon-calendar2"></span> <?php _e("Дата", "wpm"); ?>
                </div>
                <div class="col-info col-lg-9 col-md-8 col-sm-8 col-xs-8">
                    <div class="description col-lg-6 col-md-5 col-sm-5 col-xs-12">
                        <span class="date"><?php echo get_the_date(); ?></span>
                    </div>
                    <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                        <?php $design_options = get_option('wpm_design_options'); ?>
                        <?php $back_btn_text = array_key_exists('text', $design_options['buttons']['back']) ? $design_options['buttons']['back']['text'] : 'Вернуться к списку материалов'; ?>
                        <button class="wpm-button back-button pull-right"><?php _e($back_btn_text, "wpm"); ?></button>
                    </div>
                </div>
            </div>
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

                                    term_item.addClass('active')
                                    term_content.slideDown();
                                }

                            });
                        });
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php
}