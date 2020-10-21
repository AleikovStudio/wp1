<?php

class MBLTranslator
{
    private static $path_po;
    private static $path_mo;
    private static $language = 'ru';

    private static $db_values = array();

    /**
     * @var MBLTranslationPoIterator
     */
    private static $iterator;

    public static function getIterator()
    {
        self::_initIterator();

        return self::$iterator;
    }

    private static function _init()
    {
        self::$path_po = WP_PLUGIN_DIR . '/member-luxe/languages/mbl_ru.po';
        self::$path_mo = WP_PLUGIN_DIR . '/member-luxe/languages/mbl_ru.mo';
    }

    private static function _initIterator()
    {
        self::_init();

        if (!isset(self::$iterator)) {
            self::$iterator = new MBLTranslationPoIterator(MBLTranslationParser::parse(file_get_contents(self::$path_po)));
        }
    }

    public static function save()
    {
        self::_init();

        if (isset(self::$iterator)) {
            //writing po
            file_put_contents(
                self::$path_po,
                self::$iterator->msgcat()
            );

            //writing mo
            file_put_contents(
                self::$path_mo,
                self::$iterator->msgfmt()
            );
        }
    }

    public static function updateByMD5($key, $value)
    {
        self::_initIterator();

        self::$iterator->setByMD5($key, $value);
    }


    public static function load()
    {
        self::_init();
        self::_initDBValues();
        load_textdomain('mbl', self::$path_mo);
    }

    public static function saveTranslations($values)
    {
        self::_initIterator();

        foreach ($values as $hash => $value) {
            self::updateByMD5($hash, $value);
            self::_updateDBByMD5($hash, $value);
        }

        self::save();

        unload_textdomain('mbl');
        self::load();
    }

    public static function updateAll()
    {
        global $wpdb;
        self::load();

        foreach (self::$db_values as $hash => $row) {
            self::updateByMD5($hash, $row->msgstr);
        }

        self::save();
        unload_textdomain('mbl');
        self::load();

        $wpdb->query('START TRANSACTION');
        foreach (self::getIterator() as $translationRow) {
            self::_updateDBByMD5($translationRow->getMD5Hash(), __($translationRow->getHash(), 'mbl'));
        }
        $wpdb->query('COMMIT');

        self::save();
        unload_textdomain('mbl');
        self::load();
    }

    private static function _initDBValues()
    {
        global $wpdb;

        $table = self::_getTable();
        $lang = self::$language;

        if(version_compare(get_option('wpm_last_db_update', '0.1'), '1.3', '>=')) {
            $values = $wpdb->get_results("SELECT t.* FROM {$table} t WHERE `language_code`='{$lang}'", OBJECT);

            foreach ($values as $value) {
                self::$db_values[$value->hash] = $value;
            }
        }
    }

    private static function _updateDBByMD5($hash, $value)
    {
        global $wpdb;
        $table = self::_getTable();
        $lang = self::$language;
        $iterator = clone self::$iterator;
        $msg = $iterator->getByMD5($hash);

        if (isset(self::$db_values[$hash]) && self::$db_values[$hash]->msgstr != $value) {
            $row = self::$db_values[$hash];
            $id = $row->id;
            $sql = $wpdb->prepare("UPDATE {$table} SET `msgstr` = %s WHERE `id` = {$id};", $value);

            return $wpdb->query($sql);
        } elseif (!isset(self::$db_values[$hash]) && $msg && $msg->getHash()) {
            $sql = $wpdb->prepare("INSERT INTO {$table} (`language_code`, `hash`, `msgid`, `msgstr`) VALUES (%s, %s, %s, %s);",
                $lang,
                $hash,
                $msg->getHash(),
                $value
            );

            return $wpdb->query($sql);
        }

        return false;
    }

    private static function _getTable()
    {
        global $wpdb;

        return $wpdb->prefix . "memberlux_translations";
    }

    public static function getDBValues()
    {
        return self::$db_values;
    }
}