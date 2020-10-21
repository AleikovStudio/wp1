<?php

class MBLJustClickAdapter extends BaseMBLSubscriptionAdapter implements IMBLSubscriptionAdapter
{
    private $url = 'http://%username%.justclick.ru/api/';
    private $autogroups = array('allleads');
    protected $configKey = 'justclick';


    public function getAllGroups($except = array())
    {
        $groups = $this->send('GetAllGroups');

        $result = array();
        $except = (array)$except;

        foreach ($groups as $group) {
            $groupId = wpm_array_get($group, 'rass_name');
            $isExcept = in_array($groupId, $except);
            $isAuto = in_array($groupId, $this->autogroups);

            if (!$isExcept && !$isAuto) {
                $result[] = $group;
            }
        }

        return $result;
    }

    private function getUrl($action = '')
    {
        return str_replace('%username%', $this->getUsername(), $this->url) . $action;
    }

    private function send($action, $data = array())
    {
        $this->writeLog('Action: ' . $action);
        $this->writeLog('Data: ' . print_r($data, true));

        $url = $this->getUrl($action);
        $data['hash'] = $this->getHash($data);

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $res = curl_exec($ch);
            $this->writeLog('Response:' . $res);

            curl_close($ch);

            if (!$res) {
                throw new Exception(__('Не получен ответ от сервера', 'wpm'));
            }

            $decodedRes = json_decode($res, true);

            if (!$decodedRes) {
                throw new Exception(__('Формат ответа сервера не распознан', 'wpm'));
            }

            if ($decodedRes['error_code'] == 0) {
                if (!$this->checkHash($decodedRes)) {
                    throw new Exception(__('Подпись к ответу сервера не верна', 'wpm'));
                }
                $response = $decodedRes['result'];
            } else {
                throw new Exception("({$decodedRes['error_code']}) {$decodedRes['error_text']}");
            }
//            $this->writeLog(print_r($decodedRes, true));
        } catch (Exception $e) {
            $response = false;
            self::$error = $e->getMessage();
            $this->writeLog($e->getMessage());
        }

        return $response;
    }

    private function checkHash($response)
    {
        $hash = md5("{$response['error_code']}::{$response['error_text']}::{$this->getRPSKey()}");

        return (bool)($hash == $response['hash']);
    }

    private function getUsername()
    {
        return trim($this->getConfig('user_id'));
    }

    private function getRPSKey()
    {
        return trim($this->getConfig('user_rps_key'));
    }

    private function getHash($params = array())
    {
        $params = http_build_query($params);
        $userName = $this->getUsername();
        $secret = $this->getRPSKey();
        $params = "$params::$userName::$secret";

        return md5($params);
    }

    /**
     * @return array
     */
    public function addUser()
    {
        $addTo = $this->getConfig('rid');
        $deleteFromIds = $this->getConfig('del_rid');

        $result = $this->send('AddLeadToGroup', array(
            'rid[0]' => $addTo,
            'lead_name' => $this->userInfo->first_name,
            'lead_email' => $this->userInfo->user_email,
            'doneurl2' => $this->getConfig('doneurl2')
        ));

        if ($deleteFromIds) {
            $userGroups = $this->getUserGroups(array($addTo));
            $deleteFrom = array();

            if ($deleteFromIds == 'all') {
                $deleteFrom = $userGroups;
            } elseif (is_array($deleteFromIds)) {
                foreach ($userGroups as $group) {
                    if (in_array(wpm_array_get($group, 'rass_name'), $deleteFromIds)) {
                        $deleteFrom[] = $group;
                    }
                }
            }

            foreach ($deleteFrom as $group) {
                $this->deleteSubscribe(wpm_array_get($group, 'rass_name'));
            }
        }

        return $result;
    }

    public function getUserGroups($except = array())
    {
        $groups = $this->send('GetLeadGroupStatuses', array(
            'email' => $this->userInfo->user_email
        ));

        $result = array();
        $except = (array)$except;

        foreach ($groups as $group) {
            $groupId = wpm_array_get($group, 'rass_name');
            $isExcept = in_array($groupId, $except);
            $isAuto = in_array($groupId, $this->autogroups);
            if (!$isExcept && !$isAuto) {
                $result[] = $group;
            }
        }

        return $result;
    }

    public function updateUser()
    {
        $data = array(
            'lead_email' => $this->userInfo->user_email,
            'lead_name' => $this->userInfo->first_name,
            'lead_phone' => $this->userInfo->phone,
        );

        return $this->send('UpdateSubscriberData', $data);
    }

    public function unsubscribe()
    {
        if ($this->getConfig('auto_disable') == 'on') {
            $this->deleteSubscribe($this->getConfig('rid'));
        }
    }

    private function deleteSubscribe($rid)
    {
        return $this->send('DeleteSubscribe', array(
            'lead_email' => $this->userInfo->user_email,
            'rass_name' => $rid
        ));
    }

    public function credentialsFilled()
    {
        return $this->getUsername() != '' && $this->getRPSKey() != '';
    }
}