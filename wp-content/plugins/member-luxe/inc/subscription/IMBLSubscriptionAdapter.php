<?php

interface IMBLSubscriptionAdapter
{
    /**
     * @return bool
     */
    public function isAvailable();

    /**
     * @param int $levelId
     */
    public function setLevelId($levelId);

    /**
     * @param int $userId
     */
    public function setUserId($userId);

    /**
     * @return array
     */
    public function addUser();

    /**
     * @return array
     */
    public function updateUser();


    public function unsubscribe();

    /**
     * @return string
     */
    public function getError();
}