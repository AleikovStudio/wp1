<?php

require_once(dirname(__FILE__) . '/lib/MBLTranslationHeaders.php');
require_once(dirname(__FILE__) . '/lib/MBLTranslationPoHeaders.php');
require_once(dirname(__FILE__) . '/lib/MBLTranslationPoIterator.php');
require_once(dirname(__FILE__) . '/lib/MBLTranslationPoMessage.php');
require_once(dirname(__FILE__) . '/lib/MBLTranslationPo.php');
require_once(dirname(__FILE__) . '/lib/MBLTranslationMo.php');
require_once(dirname(__FILE__) . '/lib/MBLTranslationParser.php');
require_once(dirname(__FILE__) . '/lib/MBLTranslator.php');

add_action('plugins_loaded', array('MBLTranslator', 'load'));