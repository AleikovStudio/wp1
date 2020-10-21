<?php

class WPMAudioShortCode
{
    private static $id;
    private static $options;

    public static function parse($options)
    {
        self::_init($options);

        $html = wpm_get_partial(__DIR__. '/tpl.php', array(
            'id' => self::$id,
            'color' => wpm_array_get(self::$options, 'color', 'black')
        ));

        $js = sprintf(
            '<script type="text/javascript">initAudio("%s", "%s", %d, "%s")</script>',
            self::$id,
            wpm_remove_protocol(wpm_array_get(self::$options, 'audio')),
            intval(wpm_array_get(self::$options, 'autoplay') == 'on'),
            wpm_array_get(self::$options, 'color', 'black')
        );

        return $html . $js;
    }

    private static function _init($options)
    {
        self::$options = $options;
        self::$id = 'audio_' . substr(md5(rand(0, 1000)), 0, 20);
    }
}