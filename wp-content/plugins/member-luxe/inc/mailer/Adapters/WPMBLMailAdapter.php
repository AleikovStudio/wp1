<?php

class WPMBLMailAdapter extends BaseMBLMailAdapter implements IMBLMailAdapter
{
    public function isAvailable()
    {
        return true;
    }

    public function send($recipient, $message)
    {
        $headers = 'From: ' . $this->getSenderName() . ' <' . $this->getSenderEmail() . '>' . "\r\n";
        $result = wp_mail($recipient, $this->getSubject(), $message, $headers, $this->getAttachments());

        return $result;
    }
}