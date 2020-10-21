<?php

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class MBLStatsDetector
{
    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var DeviceDetector
     */
    private $dd;

    /**
     * MBLStatsDetector constructor.
     * @param string $userAgent
     */
    public function __construct($userAgent = null)
    {
        if ($userAgent === null) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $this->userAgent = $userAgent;

        DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);

        $this->dd = new DeviceDetector($this->userAgent);
        $this->dd->skipBotDetection();
        $this->dd->parse();
    }

    public function getDeviceDetector()
    {
        return $this->dd;
    }

    public static function detect($userAgent = null)
    {
        $instance = new self($userAgent);

        return $instance->getDeviceDetector();
    }
}