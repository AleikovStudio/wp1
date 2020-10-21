<!-- wpm admin js -->
<script type="text/javascript">
jQuery(function ($) {
    var wpmOptionsPage = $('.wpm-options-page');

    // ajax url
    var wp_ajax = {"ajaxurl": "<?php echo admin_url('admin-ajax.php'); ?>"};

    // show popup window with shortcode settings
    $('.wpm-mce-button').bind('click', function () {

        var button_id = $(this).attr('button-id');
        var title = $(this).attr('title');
        tb_show(title, "#TB_inline?width=640&&height=550&inlineId=shortcode-settings-conent");
        $('#TB_ajaxContent').html('');
        $('#TB_ajaxContent').css({'width': '640', 'height': ($('#TB_window').height() - 50) + 'px'}).addClass('wpm-loader');

        $.ajax({
            type: 'GET',
            url: ajaxurl,
            data: {
                action: "get_wpm_shortcode_settings",
                button: button_id
            },
            success: function (data) {
               //console.log(data);
               $('#TB_ajaxContent').html(data).removeClass('wpm-loader');
            },
            error: function(errorThrown){
//                alert(errorThrown);
            }
        });
    });

    // fix thickbox window height
    $(window).resize(function () {
        $('#TB_ajaxContent').css({'width': '640', 'height': ($('#TB_window').height() - 50) + 'px'});
    });

    <?php if(version_compare(get_bloginfo('version'), '3.9', '>=')){ ?>
    tinymce.init({
        mode: 'textarea',
        selector: "#coach_title, #wpp_smartresponder_title, #wpp_media_smartresponder_title, #coach_timer_description, #coach_privacy_description",
        menubar: false,
        statusbar: false,
        width: '500px',
        toolbar: "bold italic | fontsizeselect fontselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
    });
    <?php }else{?>
    if (typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function") {
        tinyMCE.settings = {
            width: '100%',
            height: '100',
            theme: 'advanced',
            skin: 'wp_theme',
            theme_advanced_buttons1: 'bold,italic,underline,forecolor,|,numlist,bullist,|,outdent,indent,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,removeformat, |,image',
            theme_advanced_buttons2: 'fontselect,fontsizeselect,formatselect,',
            theme_advanced_buttons3: '',
            theme_advanced_buttons4: '',
            theme_advanced_toolbar_location: 'top',
            theme_advanced_toolbar_align: 'left',
            //theme_advanced_statusbar_location : 'bottom',
            theme_advanced_resizing: false,
            //theme_advanced_resize_horizontal : false,
            dialog_type: 'modal',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            apply_source_formatting: false,
            remove_linebreaks: false,
            <?php if(get_locale() == 'ru_RU' || get_locale()== 'uk_UA' || get_locale() == 'uk') echo "language: 'ru',"; ?>
            //gecko_spellcheck : true,
            entities: '38,amp,60,lt,62,gt',
            accessibility_focus: true,
            tabfocus_elements: 'major-publishing-actions',
            paste_strip_class_attributes: 'all',
            wpeditimage_disable_captions: false
        };
        tinyMCE.execCommand("mceAddControl", true, "coach_title");
        tinyMCE.execCommand("mceAddControl", true, "coach_newsletter_description");
        tinyMCE.execCommand("mceAddControl", true, "coach_timer_description");
        tinyMCE.execCommand("mceAddControl", true, "coach_privacy_description");
        tinyMCE.execCommand("mceAddControl", true, "wpp_smartresponder_title");
        tinyMCE.execCommand("mceAddControl", true, "wpp_media_smartresponder_title");
    }

    <?php } ?>

    $("#wpm_tabs").tabs({
        autoHeight: false,

        collapsible: false,
        fx: {
            opacity: 'toggle',
            duration: 'fast'
        }
    }).addClass('ui-tabs-vertical ui-helper-clearfix');

    $('.wpm_inner_tabs').tabs({

        collapsible: false,
        fx: {
            opacity: 'toggle',
            duration: 'fast'
        }

    });

    

//------------------------- radio, checkbox, select

    $('.wpm_radio input:checked').each(function () {
        $(this).parent().addClass('wpm_checked');
    });

    $('.wpm_radio input').live('change', function () {
        var name = $(this).attr('name');
        $('input[name = ' + name + ']').each(function () {
            $(this).parent().removeClass('wpm_checked');
        });
        $(this).parent().addClass('wpm_checked');
    });

    $('.wpm_subsc_thumb input').live('change', function () {
        if ($(this).hasClass('trial')) return false;
        var name = $(this).attr('name');
        $('input[name = ' + name + ']').each(function () {
            $(this).parent().removeClass('wpm_checked');
        });
        $(this).parent().addClass('wpm_checked');
    });
    $('.ps_bullets_form input, .p_cbutton input, .ps_satisfaction input, .ps_arrows input, .ps_bonus input, .ps_cbutton input, .ps_timer_image input, .wpp_header input').live('change', function () {
        var name = $(this).attr('name');
        $('input[name = ' + name + ']').each(function () {
            $(this).parent().removeClass('wpm_checked');
        });
        $(this).parent().addClass('wpm_checked');
    });


    $('.wpm_checkbox input:checked, .p_cbutton input:checked').each(function () {
        $(this).parent().addClass('wpm_checked');
    });

    $('.wpm_checkbox input:checkbox').live('change', function () {
        $(this).parent().toggleClass('wpm_checked');
    });

    $('.wpm_checkbox input:radio').live('change', function () {
        $('input[name=' + $(this).attr("name") + ']').each(function () {
            $(this).parent().removeClass('wpm_checked');
        });
        $(this).parent().toggleClass('wpm_checked');
    });
    $('.wpm_checkbox input:disabled, .wpm_radio input:disabled').live('click', function () {
        return false;
    });


//----------------------------- video border style  -------

    $('.wpm_radio_V input:checked').each(function () {
        $(this).parent().addClass('wpm_checked');
    });

    $('.wpm_radio_v input').live('change', function () {
        if ($('input[name=video_border]:checked').val() == 'yes') {
            var name = $(this).attr('name');
            $('input[name = ' + name + ']').each(function () {
                $(this).parent().removeClass('wpm_checked');
            });
            $(this).parent().addClass('wpm_checked');
        }
    });

    $('input[name=video_border]').live('change', function () {
        if ($(this).val() == 'yes') {
            $('#video-width, #video-height').attr('disabled', 'disabled');
            $('.video_border_560 input').click();
            $('.video_styles label:first-child input').click();
        } else {
            $('#video-width, #video-height').removeAttr('disabled');
            $('.video_border_sizes label, .video_styles > label').removeClass('wpm_checked');
            $('.video_border_sizes input:checked, .video_styles input:checked').removeAttr('checked');
        }
    });
    $('input[name=video_border_size]').live('click', function () {

        if ($('input[name=video_border]:checked').val() == 'yes') {
            if ($(this).val() == '480x270') {
                $('#video-width').val('480');
                $('#video-height').val('270');
            }
            if ($(this).val() == '560x315') {
                $('#video-width').val('560');
                $('#video-height').val('315');
            }
            if ($(this).val() == '640x360') {
                $('#video-width').val('640');
                $('#video-height').val('360');
            }
            if ($(this).val() == '720x405') {
                $('#video-width').val('720');
                $('#video-height').val('405');
            }
        }
    });


// --------------------------- wpm editor

    $('.wpm_edit_ico, .wpm_text_preview').click(function () {
        var name = $(this).attr('text_id');
        // tinyMCE.get(name).dom.setStyles(tinyMCE.get(name).dom.select('body'), {'min-height': '75px', 'line-height': 'normal'});
        $('.wpm_editor_box').css({'display': 'none', 'opacity': 0});
        //$('.' + $(this).attr('text_id')).css({'display':'block'});
        $('.' + $(this).attr('text_id') + '_box').css({'display': 'block'}).animate({'opacity': 1}, 150, function () {
            // alert(tinyMCE.get(name).getContent(content));
        });
    });

    $('a.wpm_save_text').click(function () {
        var name = $(this).attr('text_id');
        var content = tinyMCE.get(name).getContent();
        $('.' + name + '_text').html(content);
        $(this).parent().parent().animate({'opacity': 0}, 150).css({'display': 'none'});

    });
    $('a.wpm_cancel_text').click(function () {
        var name = $(this).attr('text_id');
        var content = $('.' + name + '_text').html();
        tinyMCE.get(name).setContent(content);
        $(this).parent().parent().css({'display': 'none'});
    });
//---------------------------   wpm select

    $('#wpp_media_smartresponder_button_style_selected, #wpp_smartresponder_button_style_selected').live('click', function () {
        var name = $(this).attr('box_id');
        $('.' + name).css({'display': 'block'});
    });
    $('a.wpp_close_box').click(function () {
        $(this).parent().css({'display': 'none'});
    });
    $('input[name=wpp_media_smartresponder_button_style]').live('change', function () {
        $('#wpp_media_smartresponder_button_style_selected').attr('class', '').addClass('ps_button_' + $(this).val());
    });
    $('input[name=wpp_smartresponder_button_style]').live('change', function () {
        $('#wpp_smartresponder_button_style_selected').attr('class', '').addClass('ps_button_' + $(this).val());
    });


//------------------------------ wpp_smartresponder_form_version

    $('input[name=wpp_smartresponder_form_version]:checked').each(function () {
        $('.wpp_smartresponder_settings').css({'display': 'none'});
        $('#wpm_inner_tab_' + $(this).val()).css({'display': 'block'});
    });

    $('input[name=wpp_smartresponder_form_version]').live('change', function () {
        $('.wpp_smartresponder_settings').css({'display': 'none'});
        $('#wpm_inner_tab_' + $(this).val()).css({'display': 'block'});
    });


    $('input[name=wpp_media_smartresponder_form_version]:checked').each(function () {
        $('.wpp_media_smartresponder_settings_box').css({'display': 'none'});
        $('#wpm_media_inner_tab_' + $(this).val()).css({'display': 'block'});
    });

    $('input[name=wpp_media_smartresponder_form_version]').live('change', function () {
        $('.wpp_media_smartresponder_settings_box').css({'display': 'none'});
        $('#wpm_media_inner_tab_' + $(this).val()).css({'display': 'block'});
    });


//------------------------- find uid gid

    $('#coach_responder_code').bind('blur', function () {

        var uid = did = tid = '';
        $('#crc_temp_1').html($('#coach_responder_code').val());
        uid = $('#crc_temp_1').find('input[name="uid"]').val();
        did = $('#crc_temp_1').find('input[name="did[]"]').val();
        tid = $('#crc_temp_1').find('input[name="tid"]').val();


        $('#coach_responder_uid').attr('value', uid);
        $('#coach_responder_did').attr('value', did);
        $('#coach_responder_tid').attr('value', tid);

    });

    $('#wpp_getresponse_code').bind('blur', function () {

        var wid = '';
        $('#getresponse_code_temp').html($('#wpp_getresponse_code').val());
        var url = $('#getresponse_code_temp').find('script').attr('src');

        $('#wpp_getresponse_wid').attr('value', wid);

    });

    $('#smartresponder_code').live('change', (function () {
        var uid = did = tid = "";
        $('body').append('<div id="temp_code" style="display:none"></div>');
        $("#temp_code").html($('#smartresponder_code').val());

        uid = $("#temp_code").find("input[name='uid']").val();
        did = $("#temp_code").find("input[name='did[]']").val();
        tid = $("#temp_code").find("input[name='tid']").val();
        $("#r_uid").attr("value", uid);
        $("#r_did").attr("value", did);
        $("#r_tid").attr("value", tid);
        $('#temp_code').remove();
    }));

    $('#wpp_smartresponder_code').live('change', (function () {
        var uid = did = tid = "";
        $('body').append('<div id="crc_temp_2" style="display:none"></div>');
        $("#crc_temp_2").html($('#wpp_smartresponder_code').val());

        uid = $("#crc_temp_2").find("input[name='uid']").val();
        did = $("#crc_temp_2").find("input[name='did[]']").val();
        tid = $("#crc_temp_2").find("input[name='tid']").val();
        $("#wpp_smartresponder_uid").attr("value", uid);
        $("#wpp_smartresponder_did").attr("value", did);
        $("#wpp_smartresponder_tid").attr("value", tid);
        $('#crc_temp_2').remove();
    }));
    $('#wpp_media_smartresponder_code').live('change', (function () {
        var uid = did = tid = "";
        $('body').append('<div id="crc_temp_3" style="display:none"></div>');
        $("#crc_temp_3").html($('#wpp_media_smartresponder_code').val());

        uid = $("#crc_temp_3").find("input[name='uid']").val();
        did = $("#crc_temp_3").find("input[name='did[]']").val();
        tid = $("#crc_temp_3").find("input[name='tid']").val();
        $("#wpp_media_smartresponder_uid").attr("value", uid);
        $("#wpp_media_smartresponder_did").attr("value", did);
        $("#wpp_media_smartresponder_tid").attr("value", tid);
        $('#crc_temp_3').remove();
    }));
    $('#sortable_comments').sortable({
        update: function (event, ui) {
            var order = [];
            $('#sortable_comments > li input').each(function () {
                order.push($(this).val());
            });
            $('#use_comments_order').val(order);
        }
    });

//---------------

    $('#ps_background_image_selected').live('click', function () {
        var name = $(this).attr('box_id');
        $('.' + name).css({'display': 'block'});
    });

    $('#ps_background_image').bind('change', function () {
        $('#bg_preview').css({'background-image': 'url(' + $("#ps_background_image").val() + ')'});

    });

    $('.wpp_bg_images_content .wpp_bg').live('click', function () {
        var bg = $(this).children('input').val();
        if (bg != 'no_image') {
            $('#bg_preview').css({'background-image': 'url(' + bg + ')'});
            $('#ps_background_image').val(bg);

        } else {
            $('#bg_preview').css({'background-image': ''}).addClass('no_image');
            $('#ps_background_image').val('');
        }


    });

    $('#wpm_page_metabox').on('click', '#shift_is_on', function () {
        if($(this).prop('checked')) {
            $('#shift_value_label').removeClass('invisible');
        } else {
            $('#shift_value_label').addClass('invisible');
        }
    });

     wpmOptionsPage.on('change', '.letter_options', function () {
         var $this = $(this),
             holder = $('#' + $this.val() + '_api_key_label');

         $('.letter_options_label').addClass('invisible');

         if(holder.length) {
             holder.removeClass('invisible');
         }
     });

     wpmOptionsPage.on('change', '.wpm_comments_mode', function () {
         var $this = $(this),
             imageHolder = $('.wpm-comment-images-row'),
             cackleRow = $('.wpm-comment-cackle-row');

         imageHolder[$this.val() == 'cackle'?'slideUp':'slideDown']();
         cackleRow[$this.val() == 'cackle'?'slideDown':'slideUp']();
     });

    wpmOptionsPage.on('click', '#test_ses', function () {
        $.post(
            ajaxurl,
            {
                action : 'wpm_test_ses_mail',
                fields : {
                    access_key : $('#ses_access_key').val(),
                    secret_key : $('#ses_secret_key').val(),
                    email      : $('#ses_email').val(),
                    host       : $('#ses_host').val()
                }
            },
            function (respose) {
                if (respose.message) {
                    $('#test_ses_response').text(respose.message)
                }
            },
            "json"
        )
    });

    wpmOptionsPage.on('click', '#youtube_protected', function () {
        $('#jwplayer_code_row')[$(this).is(':checked') ? 'slideDown' : 'slideUp']();
    });

    wpmOptionsPage.on('change', '.jwplayer_version', function () {
        var version = $(this).val(),
            input6 = $('#jwplayer_code'),
            input7 = $('#jwplayer_code_7');

        if(version == '6') {
            input6.removeClass('hidden');
            input7.addClass('hidden');
        } else {
            input6.addClass('hidden');
            input7.removeClass('hidden');
        }
    });

    wpmOptionsPage.on('change', '[data-radio-toggle]', function() {
        var $this = $(this),
            $value = $this.val(),
            $id = $this.data('radio-toggle'),
            holderSelector = '[data-radio-toggle-holder="'+$id+'"]';

        $(holderSelector + ':visible').slideUp();

        $(holderSelector + '[data-value="'+$value+'"]').slideDown();
    });

    $('#wpm_page_metabox').on('click', '#is_homework', function () {
        if($(this).prop('checked')) {
            $('#homework_options').removeClass('invisible');
        } else {
            $('#homework_options').addClass('invisible');
        }
    });

    $('#wpm_page_metabox').on('click', 'input[name="page_meta[confirmation_method]"]', function () {
        if($(this).prop('value') == 'auto_with_shift') {
            $('#homework_shift_value_label').removeClass('disabled_field');
            $('#homework_shift_value').removeAttr('disabled');
        } else {
            $('#homework_shift_value_label').addClass('disabled_field');
            $('#homework_shift_value').attr('disabled', 'disabled');
        }
    });

    $('.wpm-move-keys').on('click', function () {
        $('#wpm-move-keys-tools').slideToggle();

        return false;
    });

    $('.wpm-copy-keys-trigger').on('click', function () {
        var $this = $(this),
            $term_from = $('[name="wpm_move_keys_term_id"]').val(),
            $duration_from = $('[name="wpm_move_keys_duration_from"]').val(),
            $units_from = $('[name="wpm_move_keys_units_from"]').val(),
            $holder = $('#wpm-move-keys-tools'),
            $term_to = $holder.find('[name="wpm_move_keys"]').val(),
            $duration_to = $holder.find('[name="wpm_move_keys_duration"]').val(),
            $units_to = $holder.find('[name="wpm_move_keys_units"]').val(),
            $TBAjaxContent = $('#TB_ajaxContent');
        $TBAjaxContent.addClass('wpm-loader');

        $this.prop('disabled', true);

        $.post(ajaxurl, {
            action        : "wpm_move_term_keys_action",
            term_from     : $term_from,
            duration_from : $duration_from,
            units_from    : $units_from,
            term_to       : $term_to,
            duration_to   : $duration_to,
            units_to      : $units_to
        }, function (data) {
            var $TBAjaxContent = $('#TB_ajaxContent');

            $TBAjaxContent.find('#user-level-keys').html('');
            $TBAjaxContent.removeClass('wpm-loader');
            $this.prop('disabled', false);
            $('#wpm-move-keys-tools').hide();

            $('#keys-list').html(data.html);
            tb_remove();
        }, "json");

        return false;
    });

    $('.wpm-copy-keys-cancel').on('click', function () {
        $('#wpm-move-keys-tools').slideUp();

        return false;
    });

});
function getParameterByName(name, string) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(string);
    if (results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}
</script>
<!-- // wpm js -->