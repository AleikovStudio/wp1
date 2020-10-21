<?php

interface IMBLMailAdapter
{
    /**
     * @return mixed
     */
    public function getOptions();

    /**
     * @param mixed|void $options
     */
    public function setOptions($options);

    /**
     * @return MBLMailer
     */
    public function getMailer();

    /**
     * @param MBLMailer $mailer
     */
    public function setMailer($mailer);

    /**
     * @return mixed
     */
    public function getSubject();

    /**
     * @param mixed $subject
     */
    public function setSubject($subject);

    /**
     * @return mixed
     */
    public function getSenderName();

    /**
     * @param mixed $senderName
     */
    public function setSenderName($senderName);

    /**
     * @return mixed
     */
    public function getSenderEmail();

    /**
     * @param mixed $senderEmail
     */
    public function setSenderEmail($senderEmail);

    /**
     * @return array
     */
    public function getAttachments();

    /**
     * @param array $attachments
     */
    public function setAttachments($attachments);

    /**
     * @return bool
     */
    public function isAvailable();

    /**
     * @param string $recipient
     * @param $message
     * @return bool
     */
    public function send($recipient, $message);
}