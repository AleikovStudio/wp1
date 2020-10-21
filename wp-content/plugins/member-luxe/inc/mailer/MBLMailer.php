<?php
require_once(dirname(__FILE__) . '/Interfaces/IMBLMailAdapter.php');
require_once(dirname(__FILE__) . '/Adapters/BaseMBLMailAdapter.php');
require_once(dirname(__FILE__) . '/Adapters/SESMBLMailAdapter.php');
require_once(dirname(__FILE__) . '/Adapters/MandrillMBLMailAdapter.php');
require_once(dirname(__FILE__) . '/Adapters/WPMBLMailAdapter.php');

class MBLMailer
{
    private $recipients = array();
    private $message;
    private $callback;

    /** @var IMBLMailAdapter */
    private $mailAdapter;

    /**
     * MBLMailer constructor.
     */
    public function __construct()
    {
        $this->initAdapter();
    }

    public static function create()
    {
        return new self();
    }

    private function initAdapter()
    {
        foreach ($this->getAvailableAdapters() as $adapter) {
            if (!isset($this->mailAdapter) && $adapter->isAvailable()) {
                $this->mailAdapter = $adapter;
                break;
            }
        }
    }

    /**
     * @return array|IMBLMailAdapter[]
     */
    private function getAvailableAdapters()
    {
        return array(
            new SESMBLMailAdapter($this),
            new MandrillMBLMailAdapter($this),
            new WPMBLMailAdapter($this),
        );
    }

    /**
     * @return array
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     * @param array $recipients
     * @return $this
     */
    public function setRecipients($recipients)
    {
        $recipients = is_array($recipients) ? $recipients : array($recipients);
        $this->recipients = array_unique($recipients);

        return $this;
    }

    /**
     * @param string $recipient
     * @return $this
     */
    public function addRecipient($recipient)
    {
        $this->recipients[] = $recipient;
        $this->recipients = array_unique($this->recipients);

        return $this;
    }

    /**
     * @param mixed $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->mailAdapter->setSubject($subject);

        return $this;
    }

    /**
     * @param mixed $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = wpautop(stripslashes(mbl_auto_link_text($message, 'urls')));

        return $this;
    }

    /**
     * @param array $attachments
     * @return $this
     */
    public function setAttachments($attachments)
    {
        $this->mailAdapter->setAttachments($attachments);

        return $this;
    }

    /**
     * @param mixed $senderName
     * @return $this
     */
    public function setSenderName($senderName)
    {
        $this->mailAdapter->setSenderName($senderName);

        return $this;
    }

    /**
     * @param mixed $senderEmail
     * @return $this
     */
    public function setSenderEmail($senderEmail)
    {
        $this->mailAdapter->setSenderEmail($senderEmail);

        return $this;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;

        return $this;
    }

    private function performCallback()
    {
        return isset($this->callback)
            ? call_user_func($this->callback, $this->message)
            : $this->message;
    }

    public function send()
    {
        $result = null;
        add_filter('wp_mail_failed', 'wpm_mail_failed');
        add_filter('wp_mail_content_type', 'wpm_mail_content_type');

        try {
            foreach ($this->getRecipients() as $recipient) {
                $result = $this->mailAdapter->send($recipient, $this->performCallback());
            }
        } catch (Exception $e) {
            $result = null;
            wpm_mail_write_log($e->getMessage());
        }

        remove_filter('wp_mail_failed', 'wpm_mail_failed');
        remove_filter('wp_mail_content_type', 'wpm_mail_content_type');

        return $result;
    }
}