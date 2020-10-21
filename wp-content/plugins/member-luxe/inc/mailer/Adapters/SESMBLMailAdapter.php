<?php

class SESMBLMailAdapter extends BaseMBLMailAdapter implements IMBLMailAdapter
{
    public function isAvailable()
    {
        return wpm_ses_is_on();
    }

    /**
     * @param $recipient
     * @param $message
     * @return bool
     */
    public function send($recipient, $message)
    {
        $arguments = array(
            'subject' => $this->getSubject(),
            'from_email' => $this->options['letters']['ses_email'],
            'from_name' => $this->getSenderName(),
            'reply_to' => ($this->getSenderName() . ' <' . $this->getSenderEmail() . '>'),
            'html' => $message,
            'to' => array(
                array('email' => $recipient)
            )
        );

        $result = wpm_ses_mail($arguments, $this->getAttachments());

        return $result;
    }
}