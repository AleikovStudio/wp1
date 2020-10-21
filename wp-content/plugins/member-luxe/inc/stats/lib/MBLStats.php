<?php

class MBLStats
{
    public static function getTable()
    {
        global $wpdb;

        return $wpdb->prefix . 'memberlux_logins';
    }

    public static function updateLastSeen()
    {
        global $wpdb;

        $currentUser = wp_get_current_user();
        $table = self::getTable();
        $userId = $currentUser->ID;

        if (!$userId) {
            return false;
        }

        $sql = "UPDATE {$table} SET last_seen_at = NOW() WHERE user_id = {$userId}";

        return $wpdb->query($sql);
    }

    /**
     * @param WP_User|null $user
     * @return bool|int
     */
    public static function saveUserLogin($user = null)
    {
        global $wpdb, $current_user;

        $detector = MBLStatsDetector::detect();

        $table = self::getTable();
        $ipAddress = self::getIP();

        $userId = $user ? $user->ID : $current_user->ID;
        if (!$userId) {
            return false;
        }

        $browser = implode(' ', array_filter(array($detector->getClient('name'), $detector->getClient('version'))));
        $operatingSystem = implode(' ', array_filter(array($detector->getOs('name'), $detector->getOs('version'), $detector->getOs('platform'))));

        $visitorCountryInfo = self::getCountryInfo();
        $countryName = isset($visitorCountryInfo->geoplugin_countryName) ? $visitorCountryInfo->geoplugin_countryName : null;
        $countryCode = isset($visitorCountryInfo->geoplugin_countryCode) ? $visitorCountryInfo->geoplugin_countryCode : null;

        $sqlLoggedIn = "UPDATE {$table} SET logged_out_at = NOW() WHERE logged_out_at IS NULL AND user_id = {$userId}";
        $wpdb->query($sqlLoggedIn);

        $sql = $wpdb->prepare(
            "INSERT INTO {$table}(user_id,logged_in_at,ip,browser,os,country_name,country_code,device,brandname,model,user_agent) VALUES(%d,NOW(),%s,%s,%s,%s,%s,%s,%s,%s,%s);",
            $userId,
            $ipAddress,
            $browser,
            $operatingSystem,
            $countryName,
            $countryCode,
            $detector->getDeviceName(),
            $detector->getBrandName(),
            $detector->getModel(),
            $detector->getUserAgent()
        );

        return $wpdb->query($sql);
    }

    private static function getIP()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    private static function getBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $browsers = array(
            'Opera' => 'Opera',
            'Firefox' => '(Firebird)|(Firefox)',
            'Galeon' => 'Galeon',
            'Chrome' => 'Chrome',
            'MyIE' => 'MyIE',
            'Lynx' => 'Lynx',
            'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',
            'Konqueror' => 'Konqueror',
            'SearchBot' => '(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp/cat)|(msnbot)|(ia_archiver)',
            'Internet Explorer 8' => '(MSIE 8\.[0-9]+)',
            'Internet Explorer 9' => '(MSIE 9\.[0-9]+)',
            'Internet Explorer 7' => '(MSIE 7\.[0-9]+)',
            'Internet Explorer 6' => '(MSIE 6\.[0-9]+)',
            'Internet Explorer 5' => '(MSIE 5\.[0-9]+)',
            'Internet Explorer 4' => '(MSIE 4\.[0-9]+)',
        );

        foreach ($browsers as $browser => $pattern) {
            if (preg_match('#' . $pattern . '#i', $userAgent)) {
                return $browser;
            }
        }

        return null;
    }

    private static function getOS()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $os = null;

        $osArray = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $os = $value;
            }
        }

        return $os;
    }

    private static function getCountryInfo()
    {
        $apiUrl = "http://www.geoplugin.net/json.gp?ip=" . self::getIP();

        $result = file_get_contents($apiUrl);

        return $result ? json_decode($result) : null;
    }

    public static function saveUserLogout()
    {
        global $wpdb, $current_user;

        $userId = $current_user->ID;
        $table = self::getTable();
        $sql = "SELECT id FROM {$table} WHERE user_id='{$userId}' AND logged_out_at IS NULL ORDER BY id DESC limit 1;";

        $result = wpm_array_get($wpdb->get_results($sql), '0');
        $id = isset($result->id) ? $result->id : null;

        if ($id) {
            $sql = "UPDATE {$table} SET logged_out_at = NOW() WHERE id = {$id};";
            $wpdb->query($sql);
        }
    }
}