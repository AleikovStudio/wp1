<?php

class MBLCrop
{
    public static function crop()
    {

        $uploads = wp_upload_dir();
        $src = wpm_array_get($_POST, 'orig');

        $w = round(floatval(wpm_array_get($_POST, 'w')), 2);
        $h = round(floatval(wpm_array_get($_POST, 'h')), 2);
        $x = round(floatval(wpm_array_get($_POST, 'x')), 2);
        $y = round(floatval(wpm_array_get($_POST, 'y')), 2);

        $iwidth = wpm_array_get($_POST, 'iwidth');
        $iheight = wpm_array_get($_POST, 'iheight');

        $oldPath = str_replace($uploads['baseurl'], $uploads['basedir'], $src);
        $baseName = self::_getFilename($src);
        $newBaseName = md5(time() . rand(1, 1000) . $baseName) ;
        $newUrl = str_replace($baseName, $newBaseName, $src);
        $newPath = str_replace($baseName, $newBaseName, $oldPath);
        $path = wp_crop_image($oldPath, $x, $y, $w, $h, $iwidth, $iheight, false, $newPath);

        if (!is_wp_error($path)) {
            $result = array(
                'result' => 'success',
                'path' => $newUrl,
            );
        } else {
            $result = array(
                'result' => 'error',
            );
        }

        return json_encode($result);
    }

    private static function _getFilename($path)
    {
        if (strpos($path, '/') === false) {
            $path_parts = pathinfo('a' . $path);
        } else {
            $path = str_replace('/', '/a', $path);
            $path_parts = pathinfo($path);
        }

        return substr($path_parts["filename"], 1);
    }

    private static function _getAttachmentId($url)
    {
        $attachment_id = 0;
        $dir = wp_upload_dir();
        if (false !== strpos($url, $dir['baseurl'] . '/')) { // Is URL in uploads directory?
            $file = basename($url);
            $query_args = array(
                'post_type' => 'attachment',
                'post_status' => 'inherit',
                'fields' => 'ids',
                'meta_query' => array(
                    array(
                        'value' => $file,
                        'compare' => 'LIKE',
                        'key' => '_wp_attachment_metadata',
                    ),
                )
            );
            $query = new WP_Query($query_args);
            if ($query->have_posts()) {
                foreach ($query->posts as $post_id) {
                    $meta = wp_get_attachment_metadata($post_id);
                    $original_file = basename($meta['file']);
                    $cropped_image_files = wp_list_pluck($meta['sizes'], 'file');
                    if ($original_file === $file || in_array($file, $cropped_image_files)) {
                        $attachment_id = $post_id;
                        break;
                    }
                }
            }
        }

        return $attachment_id;
    }
}