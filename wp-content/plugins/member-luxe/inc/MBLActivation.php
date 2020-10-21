<?php

class MBLActivation
{
    /**
     * @var null
     */
    private $userId;

    /**
     * @var MBLActivationRow[]
     */
    private $rows = array();


    /**
     * MBLActivation constructor.
     * @param null $userId
     */
    public function __construct($userId = null)
    {
        if ($userId === null) {
            $userId = get_current_user_id();
        }

        $this->userId = $userId;

        $userKeys = MBLTermKeysQuery::find(
            array(
                'user_id' => $userId,
                'is_banned' => 0,
                'key_type' => 'wpm_term_keys'
            ), null,
            array('date_end' => 'desc')
        );

        foreach ($userKeys as $key) {
            $this->rows[] = new MBLActivationRow($key);
        }

    }

    public function getBreadcrumbs()
    {
        return array(
            array(
                'name' => __('Активация', 'mbl'),
                'link' => wpm_activation_link(),
                'icon' => 'key'
            )
        );
    }

    /**
     * @return MBLActivationRow[]
     */
    public function getRows()
    {
        return $this->rows;
    }
}