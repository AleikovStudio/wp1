<?php
/*
Plugin Name: JQuery Html5 File Upload
Plugin URI: http://wordpress.org/extend/plugins/jquery-html5-file-upload/
Description: This plugin adds a file upload functionality to the front-end screen. It allows multiple file upload asynchronously along with upload status bar.
Version: 3.0
Author: sinashshajahan
Author URI: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
require_once( plugin_dir_path( __FILE__ ) . 'UploadHandler.php' );

function jquery_html5_file_upload_install() {
	add_option("wpm_jqhfu_accepted_file_types", 'gif|jpeg|jpg|png|pdf|zip|rar|mp3|mp4|wmv|doc|docx|xls|xlsx|ppt|pptx|pages|numbers|keynote', '', 'yes');
	add_option("wpm_jqhfu_inline_file_types", 'gif|jpeg|jpg|png|pdf|zip|rar|mp3|mp4|wmv|doc|docx|xls|xlsx|ppt|pptx|pages|numbers|keynote', '', 'yes');
	add_option("wpm_jqhfu_maximum_file_size", '5', '', 'yes');
	add_option("wpm_jqhfu_thumbnail_width", '80', '', 'yes');
	add_option("wpm_jqhfu_thumbnail_height", '80', '', 'yes');
	
	$upload_array = wp_upload_dir();
	$upload_dir=$upload_array['basedir'].'/files/';
	/* Create the directory where you upoad the file */
	if (!is_dir($upload_dir)) {
		$is_success=mkdir($upload_dir, '0755', true);
		if(!$is_success)
			die('Unable to create a directory within the upload folder');
	}
}

function jquery_html5_file_upload_remove() {
	/* Deletes the database field */
	delete_option('wpm_jqhfu_accepted_file_types');
	delete_option('wpm_jqhfu_inline_file_types');
	delete_option('wpm_jqhfu_maximum_file_size');
	delete_option('wpm_jqhfu_thumbnail_width');
	delete_option('wpm_jqhfu_thumbnail_height');
}

// Add settings link on plugin page
function jquery_html5_file_upload_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=jquery-html5-file-upload-setting.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 

function jquery_html5_file_upload_html_page() {
$args = array(
    'orderby'                 => 'display_name',
    'order'                   => 'ASC',
    'selected'                => $_POST['user']
);
?>
<h2>JQuery HTML5 File Upload Setting</h2>

<form method="post" >
<?php wp_nonce_field('update-options'); ?>

<table >
<tr >
<td>Accepted File Types</td>
<td >
<input type="text" name="accepted_file_types" value="<?php print(get_option('wpm_jqhfu_accepted_file_types')); ?>" />&nbsp;filetype seperated by | (e.g. gif|jpeg|jpg|png)
</td>
</tr>
<tr >
<td>Inline File Types</td>
<td >
<input type="text" name="inline_file_types" value="<?php print(get_option('wpm_jqhfu_inline_file_types')); ?>" />&nbsp;filetype seperated by | (e.g. gif|jpeg|jpg|png)
</td>
</tr>
<tr >
<td>Maximum File Size</td>
<td >
<input type="text" name="maximum_file_size" value="<?php print(get_option('wpm_jqhfu_maximum_file_size')); ?>" />&nbsp;MB
</td>
</tr>
<tr >
<td>Thumbnail Width </td>
<td >
<input type="text" name="thumbnail_width" value="<?php print(get_option('wpm_jqhfu_thumbnail_width')); ?>" />&nbsp;px
</td>
</tr
<tr >
<td>Thumbnail Height </td>
<td >
<input type="text" name="thumbnail_height" value="<?php print(get_option('wpm_jqhfu_thumbnail_height')); ?>" />&nbsp;px
</td>
</tr>
<tr>
<td colspan="2">
<input type="submit" name="savesetting" value="Save Setting" />
</td>
</tr>
</table>
<br/>
<hr/>
<h2>View Uploaded Files</h2>
<table >
<tr >
<td>Select User</td>
<td >
<?php wp_dropdown_users($args); ?> 
</td>
<td>
<input type="submit" name="viewfiles" value="View Files" /> &nbsp; <input type="submit" name="viewguestfiles" value="View Guest Files" />
</td>
</tr>
<tr>
</table>
<table>
<tr>
<td>
<?php
if(isset($_POST['viewfiles']) && $_POST['viewfiles']=='View Files')
{
if ($_POST['user']) {
	$upload_array = wp_upload_dir();
	$imgpath=$upload_array['basedir'].'/files/'.$_POST ['user'].'/';
	$filearray=glob($imgpath.'*');
	if($filearray && is_array($filearray))
	{
		foreach($filearray as $filename){
			if(basename($filename)!='thumbnail'){
			print('<a href="'.$upload_array['baseurl'].'/files/'.$_POST ['user'].'/'.basename($filename).'" target="_blank"/>'.basename($filename).'</a>');
			print('<br/>');
			}
		}
	}
} 
}
else
if(isset($_POST['viewguestfiles']) && $_POST['viewguestfiles']=='View Guest Files')
{
	$upload_array = wp_upload_dir();
	$imgpath=$upload_array['basedir'].'/files/guest/';
	$filearray=glob($imgpath.'*');
	if($filearray && is_array($filearray))
	{
		foreach($filearray as $filename){
			if(basename($filename)!='thumbnail'){
			print('<a href="'.$upload_array['baseurl'].'/files/guest/'.basename($filename).'" target="_blank"/>'.basename($filename).'</a>');
			print('<br/>');
			}
		}
	}
}
?>
</td>
</tr>
</table>
</form>
<?php
}

function wpm_jqhfu_enqueue_styles()
{
    $stylepath=plugin_dir_url(__FILE__).'css/';

	wpm_enqueue_style ( 'blueimp-gallery-style', $stylepath.'blueimp-gallery.min.css' );
	if(is_admin()) {
	    wpm_enqueue_style ( 'jquery.fileupload-style', $stylepath.'jquery.fileupload-admin.css' );
    } else {
        wpm_enqueue_style ( 'jquery.fileupload-style', $stylepath.'jquery.fileupload.css' );
    }
	wpm_enqueue_style ( 'fontawesome', $stylepath.'fontawesome/css/font-awesome.min.css' );
}


function wpm_jqhfu_enqueue_scripts() {
	$scriptpath=plugin_dir_url(__FILE__).'js/';

	if(!is_admin()) {
        wpm_jqhfu_enqueue_styles();
    }

	wpm_enqueue_script ( 'jtmpl-script', $scriptpath . 'tmpl.min.js');
	wpm_enqueue_script ( 'load-image-all-script', $scriptpath . 'load-image.all.min.js');
	wpm_enqueue_script ( 'canvas-to-blob-script', $scriptpath . 'canvas-to-blob.min.js');
	wpm_enqueue_script ( 'jquery-blueimp-gallery-script', $scriptpath . 'jquery.blueimp-gallery.min.js');
	wpm_enqueue_script ( 'jquery-iframe-transport-script', $scriptpath . 'jquery.iframe-transport.js');
	wpm_enqueue_script ( 'jquery-fileupload-script', $scriptpath . 'jquery.fileupload.js');
	wpm_enqueue_script ( 'jquery-fileupload-process-script', $scriptpath . 'jquery.fileupload-process.js');
	wpm_enqueue_script ( 'jquery-fileupload-image-script', $scriptpath . 'jquery.fileupload-image.js');
	wpm_enqueue_script ( 'jquery-fileupload-audio-script', $scriptpath . 'jquery.fileupload-audio.js');
//	wpm_enqueue_script ( 'jquery-fileupload-video-script', $scriptpath . 'jquery.fileupload-video.js');
	wpm_enqueue_script ( 'jquery-fileupload-validate-script', $scriptpath . 'jquery.fileupload-validate.js');
	wpm_enqueue_script ( 'jquery-fileupload-ui-script', $scriptpath . 'jquery.fileupload-ui.js');
	wpm_enqueue_script ( 'jquery-fileupload-jquery-ui-script', $scriptpath . 'jquery.fileupload-jquery-ui.js');
}

function wpm_jqhfu_load_ajax_function()
{
	global $current_user;
	get_currentuserinfo();
	$current_user_id = $current_user->ID;

	if (!isset($current_user_id) || $current_user_id == '') {
		$current_user_id = 'guest';
	}

	if (isset($_POST['wpm_task'])) {
		$wpmTaskId = intval($_POST['wpm_task']);
	} elseif (isset($_GET['wpm_task'])) {
		$wpmTaskId = intval($_GET['wpm_task']);
	} elseif (isset($_DELETE['wpm_task'])) {
		$wpmTaskId = intval($_DELETE['wpm_task']);
	}

	if (isset($_POST['wpm_type'])) {
		$wpmType = $_POST['wpm_type'];
	} elseif (isset($_GET['wpm_type'])) {
		$wpmType = $_GET['wpm_type'];
	} elseif (isset($_DELETE['wpm_type'])) {
		$wpmType = $_DELETE['wpm_type'];
	}

	if (isset($wpmType) && is_array($wpmType)) {
        foreach ($wpmType as $key => $val) {
            UploadHandler::$wpmType[$key] = $val;
            if($key == 'wpm_material') {
                $current_user_id = $key . '_' . $val;
            } else {
                $current_user_id = $key . '_' . $val . '_' . $current_user_id;
            }
	    }
	} elseif (isset($wpmTaskId)) {
		UploadHandler::$wpmTaskId = $wpmTaskId;
		$current_user_id = 'wpm_task_' . $wpmTaskId . '_' . $current_user_id;
	}

	new UploadHandler(null, $current_user_id, true, null);

	die();
}

function wpm_jqhfu_add_inline_script() {
    wpm_render_partial('file-upload-js');
}

function wpm_jqhfu_add_inline_admin_script() {
    wpm_render_partial('file-upload-js', 'admin');
}

/* Block of code that need to be printed to the form*/
function jquery_html5_file_upload_hook() {
    wpm_render_partial('file-upload');
}

function jquery_file_upload_shortcode() {
      jquery_html5_file_upload_hook();
}

/* Add the resources */
if(wpm_is_interface_2_0()) {
    add_action('wpm_footer', 'wpm_jqhfu_enqueue_scripts', 940 );
    add_action('admin_print_footer_scripts', "wpm_jqhfu_enqueue_scripts", 10000);
    add_action('admin_print_footer_scripts', 'wpm_jqhfu_add_inline_admin_script', 1001 );
    add_action('admin_print_styles', "wpm_jqhfu_enqueue_styles", 1);
} else {
    add_action('wpm_head', 'wpm_jqhfu_enqueue_scripts', 1000);
}

/* Load the inline script */
add_action( 'wpm_footer', 'wpm_jqhfu_add_inline_script', 1000 );

/* Hook on ajax call */
add_action('wp_ajax_load_ajax_function', 'wpm_jqhfu_load_ajax_function');
add_action('wp_ajax_nopriv_load_ajax_function', 'wpm_jqhfu_load_ajax_function');

add_shortcode ('jquery_file_upload', 'jquery_file_upload_shortcode');