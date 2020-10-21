<?php

abstract class BaseMBLMailAdapter
{
    protected $options = array();
    /**
     * @var MBLMailer
     */
    protected $mailer;

    protected $subject;
    protected $senderName;
    protected $senderEmail;
    protected $attachments = array();

    /**
     * BaseMBLMailAdapter constructor.
     * @param MBLMailer $mailer
     */
    public function __construct($mailer)
    {
        $this->options = get_option('wpm_main_options');
        $this->mailer = $mailer;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed|void $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return MBLMailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @param MBLMailer $mailer
     */
    public function setMailer($mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getSenderName()
    {
        return isset($this->senderName) ? $this->senderName : get_bloginfo("name");
    }

    /**
     * @param mixed $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return mixed
     */
    public function getSenderEmail()
    {
        return isset($this->senderEmail) ? $this->senderEmail : ('no-reply@' . $_SERVER['SERVER_NAME']);
    }

    /**
     * @param mixed $senderEmail
     */
    public function setSenderEmail($senderEmail)
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments($attachments)
    {
        if(!is_array($attachments)) {
            $attachments = array($attachments);
        }

        $this->attachments = $attachments;
    }
}