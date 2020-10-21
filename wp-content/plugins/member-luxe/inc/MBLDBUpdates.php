<?php

class MBLDBUpdates
{
    private static $updates = array(
        '0.7.9.7.7' => array(
            'ALTER TABLE %prefix%memberlux_responses ADD `is_archived` tinyint(1) DEFAULT 0;',
            'CREATE INDEX `%prefix%memberlux_responses_is_archived_idx` ON %prefix%memberlux_responses (`is_archived`);',
        ),
        '0.7.9.9.4' => array(
            'CREATE TABLE %prefix%memberlux_logins (
		    `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) ,
            `logged_in_at` datetime,
            `logged_out_at` datetime,
            `last_seen_at` datetime,
            `ip` varchar(20),
            `browser` varchar(100),
            `os` varchar(100),
            `country_name` varchar(100),
            `country_code` varchar(20),
            `device` varchar(255),
            `brandname` varchar(255),
            `model` varchar(255),
            `user_agent` text,
            PRIMARY KEY (`id`));',
            'CREATE INDEX `%prefix%memberlux_logins_user_id_idx` ON %prefix%memberlux_logins (`user_id`);',
            'CREATE INDEX `%prefix%memberlux_logins_logged_in_at_idx` ON %prefix%memberlux_logins (`logged_in_at`);',
            'CREATE INDEX `%prefix%memberlux_logins_logged_out_at_idx` ON %prefix%memberlux_logins (`logged_out_at`);',
        ),
        '0.7.9.9.7' => array(
            'CREATE INDEX `%prefix%memberlux_logins_ip_idx` ON %prefix%memberlux_logins (`ip`);',
        ),
        '1.3' => array(
            'CREATE TABLE %prefix%memberlux_translations (
		    `id` INT(11) NOT NULL AUTO_INCREMENT,
            `language_code` VARCHAR(20),
            `hash` VARCHAR(255),
            `msgid` TEXT,
            `msgstr` TEXT,
            PRIMARY KEY (`id`),
            INDEX `%prefix%memberlux_translations_language_code_idx` (`language_code`),
            UNIQUE KEY `%prefix%memberlux_translations_hash_idx` (`hash`)
            )
            DEFAULT CHARACTER SET utf8
            DEFAULT COLLATE utf8_unicode_ci
            ENGINE=InnoDB;',
        ),
    );

    public static function update()
    {
        global $wpdb;

        foreach (self::$updates AS $version => $updates) {
            if(version_compare(get_option('wpm_version'), $version, '<')) {
                foreach ($updates AS $update) {
                    $wpdb->query(str_replace('%prefix%', $wpdb->prefix, $update));
                }
                update_option('wpm_last_db_update', $version);
            }
        }
    }
}