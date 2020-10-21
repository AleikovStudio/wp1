<?php

class MBLUpdates
{
    private static $updates = array(
        '1.1' => array(
            array('MBLUpdates', 'moveShortDescription')
        ),

    );

    public static function update()
    {
        foreach (self::$updates AS $version => $updates) {
            if (version_compare(get_option('wpm_version'), $version, '<')) {
                foreach ($updates AS $update) {
                    if (is_callable($update)) {
                        call_user_func($update);
                    }
                }
            }
        }
    }

    public static function moveShortDescription()
    {
        $postIds = get_posts(array(
                'post_type' => 'wpm-page',
                'posts_per_page' => -1,
                'fields' => 'ids'
            )
        );

        foreach ($postIds as $postId) {
            $postMeta = get_post_meta($postId, '_wpm_page_meta', true);
            $description = wpm_array_get($postMeta, 'description');
            if(!empty($description)) {
                update_post_meta($postId, 'mbl_short_description', $description);
            }
        }

    }

    public static function log($message)
    {
        $logDir = WP_PLUGIN_DIR . '/member-luxe/log';
        $logFile = $logDir . '/update.log';

        if(is_array($message)) {
            $message = print_r($message, true);
        }

        if (!file_exists($logDir)) {
            mkdir($logDir);
        }

        if (is_dir($logDir) && is_writable($logDir)) {
            file_put_contents($logFile, $message . "\n", FILE_APPEND);
        }
    }
}