<?php
/*

Plugin Name: MemberLux
Plugin URI: http://memberlux.ru
Description: MemberLux
Version: 2.0.2.6
Author: Виктор Левчук
Author URI: http://blog.pluginex.ru/author/vic_levchuk/

*/
/**
 *  If no Wordpress, go home
 */

if (!defined('ABSPATH')) { exit; }

define('WP_MEMBERSHIP_VERSION', '2.0.2.6');
define('WP_MEMBERSHIP_DIR', plugin_dir_path(__FILE__));

include_once('inc/functions.php');

function wpm_activate()
{
    wpm_install();
}

register_activation_hook(__FILE__, 'wpm_activate');
register_deactivation_hook(__FILE__, 'wpm_deactivate');