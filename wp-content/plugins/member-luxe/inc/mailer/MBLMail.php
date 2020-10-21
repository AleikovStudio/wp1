<?php

class MBLMail
{
    private static function replaceParams($text, $params)
    {
        foreach ($params AS $param => $value) {
            $text = str_replace('[' . $param . ']', $value, $text);
        }

        return $text;
    }

    private static function getViewPath($alias)
    {
        return dirname(__FILE__) . "/templates/{$alias}.php";
    }

    private static function getPartial($alias, $params)
    {
        return wpm_get_partial(self::getViewPath($alias), $params);
    }

    public static function fromDB($key, $to, $params, $files = array())
    {
        $title = self::replaceParams(wpm_get_option("letters.{$key}.title"), $params);
        $text = self::replaceParams(wpm_get_option("letters.{$key}.content"), $params);

        return MBLMailer::create()
                        ->addRecipient($to)
                        ->setSubject($title)
                        ->setMessage($text)
                        ->setAttachments($files)
                        ->send();
    }


    public static function askForm($to, $senderName, $senderEmail, $message, $title)
    {
        $text = self::getPartial('ask_form', compact('senderName', 'message'));
        $subject = __('Вопрос', 'wpm') . ': ' . $title;

        return MBLMailer::create()
                        ->addRecipient($to)
                        ->setSubject($subject)
                        ->setMessage($text)
                        ->setSenderName($senderName)
                        ->setSenderEmail($senderEmail)
                        ->send();
    }

    public static function newKey($to, $subject, $message, $params)
    {
        if (empty($subject)) {
            $subject = __('Новые материалы!', 'wpm');
        }
        if (empty($message)) {
            $message = self::getPartial('new_key', $params);
        }

        $subject = self::replaceParams($subject, $params);
        $message = self::replaceParams($message, $params);

        return MBLMailer::create()
                        ->addRecipient($to)
                        ->setSubject($subject)
                        ->setMessage($message)
                        ->send();
    }

    public static function massRegistration($to, $subject, $message, $params)
    {
        if (empty($subject)) {
            $subject = __('Вы зарегистрированы!', 'wpm');
        }

        if (empty($message)) {
            $message = self::getPartial('mass_registration', $params);
        }

        $subject = self::replaceParams($subject, $params);
        $message = self::replaceParams($message, $params);

        return MBLMailer::create()
                        ->addRecipient($to)
                        ->setSubject($subject)
                        ->setMessage($message)
                        ->send();
    }

    public static function registration($to, $params)
    {
        $subject = __('Регистрация нового пользователя', 'wpm');
        $message = self::getPartial('registration', $params);

        return MBLMailer::create()
                        ->addRecipient($to)
                        ->setSubject($subject)
                        ->setMessage($message)
                        ->send();
    }
}