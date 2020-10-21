<?php

class MandrillMBLMailAdapter extends BaseMBLMailAdapter implements IMBLMailAdapter
{
    protected $key;

    /**
     * @var Mandrill
     */
    protected $mandrill;

    /**
     * MandrillMBLMailAdapter constructor.
     * @param MBLMailer $mailer
     */
    public function __construct($mailer)
    {
        parent::__construct($mailer);

        if ($this->isAvailable()) {
            $this->key = isset($this->options['letters']['mandrill_api_key'])
                ? $this->options['letters']['mandrill_api_key']
                : null;
            $this->mandrill = new Mandrill($this->key);
        }
    }

    public function setAttachments($attachments)
    {
        if (!is_array($attachments)) {
            $attachments = array($attachments);
        }

        $structuredAttachments = array();

        foreach ($attachments AS $attachment) {
            if (file_exists($attachment)) {
                $structuredAttachments[] = array(
                    'type' => MBLUtils::getMimeType($attachment),
                    'name' => pathinfo($attachment, PATHINFO_BASENAME),
                    'content' => base64_encode(file_get_contents($attachment))
                );
            }
        }

        $this->attachments = $structuredAttachments;
    }


    public function isAvailable()
    {
        return wpm_mandrill_is_on();
    }

    public function send($recipient, $message)
    {
        $arguments = array(
            'subject' => $this->getSubject(),
            'from_email' => $this->getSenderEmail(),
            'from_name' => $this->getSenderName(),
            'html' => $message,
            'inline_css' => true,
            'to' => array(
                array('email' => $recipient)
            )
        );

        if (count($this->getAttachments())) {
            $arguments['attachments'] = $this->getAttachments();
        }

        $result = $this->mandrill->messages->send($arguments, true);

        return $result;
    }

}