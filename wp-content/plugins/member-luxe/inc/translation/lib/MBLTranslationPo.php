<?php

abstract class MBLTranslationPo
{
    public static function pair($key, $text, $width = 79)
    {
        if (!$text && '0' !== $text) {
            return $key . ' ""';
        }
        $text = addcslashes($text, "\t\x0B\x0C\x07\x08\\\"");
        $text = preg_replace('/\R/u', "\\n\n", $text, -1, $nbr);
        if ($nbr) {
        } else {
            if ($width && $width < mb_strlen($text, 'UTF-8') + strlen($key) + 3) {
            } else {
                return $key . ' "' . $text . '"';
            }
        }
        $lines = array($key . ' "');
        if ($width) {
            $width -= 2;
            $a = '/^.{0,' . ($width - 1) . '}[-– \\.,:;\\?!\\)\\]\\}\\>]/u';
            $b = '/^[^-– \\.,:;\\?!\\)\\]\\}\\>]+/u';
            foreach (explode("\n", $text) as $unwrapped) {
                $length = mb_strlen($unwrapped, 'UTF-8');
                while ($length > $width) {
                    if (preg_match($a, $unwrapped, $r)) {
                        $line = $r[0];
                    } else {
                        if (preg_match($b, $unwrapped, $r)) {
                            $line = $r[0];
                        } else {
                            throw new Exception('Wrapping error');
                        }
                    }
                    $lines[] = $line;
                    $trunc = mb_strlen($line, 'UTF-8');
                    $length -= $trunc;
                    $unwrapped = substr($unwrapped, strlen($line));
                    if ((false === $unwrapped && 0 !== $length) || (0 === $length && false !== $unwrapped)) {
                        throw new Exception('Truncation error');
                    }
                }
                if (0 !== $length) {
                    $lines[] = $unwrapped;
                }
            }
        } else {
            foreach (explode("\n", $text) as $unwrapped) {
                $lines[] = $unwrapped;
            }
        }

        return implode("\"\n\"", $lines) . '"';
    }

    public static function refs($text, $width = 76)
    {
        $text = preg_replace('/\s+/', ' ', $text);

        return '#: ' . wordwrap($text, $width, "\n#: ", false);
    }

    public static function prefix($text, $prefix)
    {
        $lines = preg_split('/\\R/u', $text, -1);

        return $prefix . implode("\n" . $prefix, $lines);
    }
}