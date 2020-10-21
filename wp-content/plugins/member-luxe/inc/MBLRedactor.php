<?php

class MBLRedactor
{
    public static function summernote($content, $id, $options = array(), $required = false, $name = null, $empty = false)
    {
        if($name === null) {
            $name = $id;
        }
        $defaultOptions = array(
            'lang' => 'ru-RU',
            'height' => 200,
            'dialogsInBody' => true,
            'toolbar' => ($empty
                ? array()
                : array(
                    array('font', array('bold', 'italic', 'underline')),
                    array('color', array('color')),
                    array('para', array('paragraph', 'link'))
                )
            )
        );
        $options = json_encode(array_merge($defaultOptions, $options));
        $requiredText = $required ? 'required' : '';
        $html = <<<HTML
    <textarea id="{$id}" name="{$name}" {$requiredText}>{$content}</textarea>
    <script type="text/javascript">
        jQuery(function ($) {
            $('#{$id}').summernote({$options});
        });
    </script>
HTML;

        return $html;
    }

    public static function getWpPageTinyMCEOptions()
    {
        if (version_compare(get_bloginfo('version'), '3.9', '>=')) {
            $wppage_tinymce_options = array(
                'quicktags' => false,
                'media_buttons' => false,
                'editor_height' => 100,
                'editor_class' => 'wppage-footer-content',
                'tinymce' => array(
                    'toolbar1' => 'bold italic underline strikethrough | forecolor backcolor | justifyleft justifycenter justifyright | bullist numlist outdent indent |removeformat | link unlink hr',
                    'toolbar2' => false,
                    'toolbar3' => false,
                    'forced_root_block' => 'p',
                    'force_br_newlines' => false,
                    'force_p_newlines' => true,
                    'remove_linebreaks' => true,
                    'wpautop' => true,
                    'content_css_force' => (plugins_url() . '/member-luxe/templates/base/bootstrap/css/bootstrap.min.css'
                        . ', ' . plugins_url() . '/member-luxe/css/editor-style-wpm-homework.css?' . time()
                        . ', ' . plugins_url() . '/member-luxe/css/bullets.css'
                    )
                )
            );

        } else {
            $wppage_tinymce_options = array(
                'media_buttons' => false,
                'teeny' => false,
                'quicktags' => false,
                'textarea_rows' => 20,
                'editor_class' => 'wppage-footer-content',
                'content_css' => '',
                'tinymce' => array(
                    'theme_advanced_buttons1' => 'bold,italic,underline,strikethrough,|,forecolor,backcolor,|,justifyleft,justifycenter,justifyright,|,bullist,numlist,outdent,indent,|,removeformat,|,link,unlink,hr',
                    'theme_advanced_buttons2' => '',
                    'theme_advanced_buttons3' => '',
                    'theme_advanced_buttons4' => '',
                    'theme_advanced_font_sizes' => '10px,11px,12px,13px,14px,15px,16px,17px,18px,19px,20px,21px,22px,23px,24px,25px,26px,27px,28px,29px,30px,32px,42px,48px,52px',
                    'forced_root_block' => 'p',
                    'force_br_newlines' => false,
                    'force_p_newlines' => true,
                    'remove_linebreaks' => true,
                    'wpautop' => true,
                    'content_css_force' => (plugins_url() . '/member-luxe/templates/base/bootstrap/css/bootstrap.min.css'
                        . ', ' . plugins_url() . '/member-luxe/css/editor-style-wpm-homework.css?' . time()
                        . ', ' . plugins_url() . '/member-luxe/css/bullets.css'
                    )
                )
            );
        }

        return $wppage_tinymce_options;
    }
}