<?php

class MBLActivationRow
{
    private $key;
    private $level;

    /**
     * @var MBLCategoryCollection
     */
    private $categories;

    /**
     * MBLActivationRow constructor.
     * @param $key
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->level = get_term($this->getField('term_id'), 'wpm-levels');
        $this->categories = new MBLCategoryCollection(0, true, false, $this->getField('term_id'));
    }

    public function getField($key = null)
    {
        return wpm_array_get($this->key, $key);
    }

    public function getKey()
    {
        return $this->getField('key');
    }

    /**
     * @return MBLCategory
     */
    public function getLevel()
    {
        return $this->level;
    }

    public function getLevelName()
    {
        return $this->level ? $this->level->name : '';
    }

    public function getDateStart()
    {
        return date("d.m.Y", strtotime($this->getField('date_start')));
    }

    public function getDateEnd()
    {
        return date("d.m.Y", strtotime($this->getField('date_end')));
    }

    public function getDaysLeft()
    {
        return floor((strtotime($this->getField('date_end')) - time())/(60*60*24));
    }

    public function isActive()
    {
        return $this->getDaysLeft() >= 0;
    }

    public function getCategories()
    {
        return $this->categories->getCategories();
    }
}