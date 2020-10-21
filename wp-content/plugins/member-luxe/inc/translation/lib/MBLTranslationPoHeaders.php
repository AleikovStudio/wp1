<?php

class MBLTranslationPoHeaders extends MBLTranslationHeaders
{
    public static function fromMsgstr($str)
    {
        $headers = new MBLTranslationPoHeaders;
        foreach (explode("\n", $str) as $line) {
            $i = strpos($line, ':') and $key = trim(substr($line, 0, $i)) and $headers->add($key, trim(substr($line, ++$i)));
        }

        return $headers;
    }

    public static function fromSource($raw)
    {
        while (preg_match('/^.*[\r\n]+/u', $raw, $r)) {
            $line = $r[0];
            if ('#' === $line{0}) {
                $raw = substr($raw, strlen($line));
                continue;
            }
            if (preg_match('/^msgid\s+""\s+msgstr\s+/', $raw, $r)) {
                $raw = substr($raw, strlen($r[0]));
                $str = array();
                while (preg_match('/^"(.*)"\s*/', $raw, $r)) {
                    $raw = substr($raw, strlen($r[0]));
                    $chunk = $r[1];
                    if ('' !== $chunk) {
                        $str[] = stripcslashes($r[1]);
                    }
                }
                if ($str) {
                    return self::fromMsgstr(implode('', $str));
                }
                break;
            } else {
                break;
            }
        }
        throw new Exception('Invalid PO header');
    }
}