<?php
require_once(dirname(__FILE__) . '/IMBLSubscriptionAdapter.php');
require_once(dirname(__FILE__) . '/BaseMBLSubscriptionAdapter.php');
require_once(dirname(__FILE__) . '/Adapters/MBLJustClickAdapter.php');
require_once(dirname(__FILE__) . '/actions.php');


class MBLSubscription
{
    /**
     * @var IMBLSubscriptionAdapter[]
     */
    private $adapters;

    /**
     * @var int
     */
    private $levelId;
    /**
     * @var null
     */
    private $userId;

    /**
     * MBLSubscription constructor.
     * @param null $userId
     * @param int $levelId
     */
    public function __construct($userId = null, $levelId = null)
    {
        if ($userId !== null) {
            $this->userId = $userId;
        }

        if ($levelId !== null) {
            $this->levelId = $levelId;
        }

        $this->initAdapters();
    }

    /**
     * @return int
     */
    public function getLevelId()
    {
        return $this->levelId;
    }

    /**
     * @param int $levelId
     */
    public function setLevelId($levelId)
    {
        $this->levelId = $levelId;
    }

    private function initAdapters()
    {
        foreach ($this->getAvailableAdapters() as $key => $adapter) {
            if ($adapter->isAvailable()) {
                if ($this->levelId !== null) {
                    $adapter->setLevelId($this->levelId);
                }
                if ($this->userId !== null) {
                    $adapter->setUserId($this->userId);
                }
                $this->adapters[$key] = $adapter;
            }
        }
    }

    /**
     * @return array|IMBLSubscriptionAdapter[]
     */
    private function getAvailableAdapters()
    {
        return array(
            'justclick' => new MBLJustClickAdapter()
        );
    }

    /**
     * @param string $key
     * @return IMBLSubscriptionAdapter|false
     */
    public function getAdapter($key)
    {
        return $key !== null && array_key_exists($key, $this->adapters)
            ? $this->adapters[$key]
            : false;
    }

    /**
     * @param string $key
     * @return IMBLSubscriptionAdapter
     */
    public function forceGetAdapter($key)
    {
        return wpm_array_get($this->getAvailableAdapters(), $key, false);
    }

    public static function get($key, $method = null, $default = null)
    {
        $subscription = new self();
        $result = $adapter = $subscription->getAdapter($key);

        if ($method !== null && $adapter !== false && method_exists($adapter, $method)) {
            $result = call_user_func(array($adapter, $method));
        }

        if ($result === false) {
            $result = $default;
        }

        return $result;
    }

    public static function getWithLevel($key, $levelId, $method = null, $default = null)
    {
        $subscription = new self();
        $adapter = $subscription->getAdapter($key);
        $adapter->setLevelId($levelId);

        $result = $adapter;

        if ($method !== null && $adapter !== false && method_exists($adapter, $method)) {
            $result = call_user_func(array($adapter, $method));
        }

        if ($result === false) {
            $result = $default;
        }

        return $result;
    }

    public static function direct($key, $method = null, $default = null)
    {
        $subscription = new self();
        $result = $adapter = $subscription->forceGetAdapter($key);

        if ($method !== null && $adapter !== false && method_exists($adapter, $method)) {
            $result = call_user_func(array($adapter, $method));
        }

        if ($result === false) {
            $result = $default;
        }

        return $result;
    }

    public function addUser()
    {
        foreach ($this->adapters AS $adapter) {
            $adapter->addUser();
        }
    }

    public function updateUser()
    {
        foreach ($this->adapters AS $adapter) {
            $adapter->updateUser();
        }
    }

    public function unsubscribeUser()
    {
        foreach ($this->adapters AS $adapter) {
            $adapter->unsubscribe();
        }
    }

    public static function add($userId, $levelId = null)
    {
        $subscription = new self($userId, $levelId);
        $subscription->addUser();
    }

    public static function update($userId, $levelId = null)
    {
        $subscription = new self($userId, $levelId);
        $subscription->updateUser();
    }

    public static function unsubscribe($userId, $levelId = null)
    {
        $subscription = new self($userId, $levelId);
        $subscription->unsubscribeUser();
    }
}