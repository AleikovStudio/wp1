<?php

class BaseMBLSubscriptionAdapter
{
    /**
     * @var int
     */
    protected $levelId;

    /**
     * @var int
     */
    protected $userId;

    protected $userInfo;
    protected $configKey;
    protected $levelConfig;
    protected $debug = false;
    protected static $error;

    /**
     * BaseMBLSubscriptionAdapter constructor.
     * @param null $userId
     * @param int $levelId
     */
    public function __construct($userId = null, $levelId = null)
    {
        if ($levelId !== null) {
            $this->setLevelId($levelId);
        }
        if ($userId !== null) {
            $this->setUserId($userId);
        }
    }

    /**
     * @return string
     */
    public function getError()
    {
        return self::$error;
    }

    /**
     * @param int $levelId
     */
    public function setLevelId($levelId)
    {
        $this->levelId = $levelId;

        $termMeta = get_option("taxonomy_term_" . $levelId);

        $this->levelConfig = wpm_array_get($termMeta, 'auto_subscriptions.' . $this->configKey);
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        $this->userInfo = get_userdata($userId);
    }

    public function isAvailable()
    {
        return $this->getConfig('active') == 'on';
    }

    protected function getConfig($key = null, $default = null)
    {
        $value = wpm_array_get($this->levelConfig, $key);

        if($value === null || $value == '') {
            $value = wpm_array_get(wpm_get_option('auto_subscriptions.' . $this->configKey), $key, $default);
        }

        return $value;
    }

    protected function writeLog($message)
    {
        if ($this->debug) {
            $logDir = WP_PLUGIN_DIR . '/member-luxe/inc/subscription/log';
            $logFile = $logDir . '/' . $this->configKey . '.log';

            if (!file_exists($logDir)) {
                mkdir($logDir);
            }

            if (is_dir($logDir) && is_writable($logDir)) {
                file_put_contents($logFile, $message . "\n", FILE_APPEND);
            }
        }
    }
}