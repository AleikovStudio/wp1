<?php

class MBLTranslationParser
{
    public static function parse($source)
    {
        $i = -1;
        $key = '';
        $idx = 0;
        $entries = array();
        $template = array('#' => array(), 'id' => array(), 'str' => array(), 'ctxt' => array());
        foreach (preg_split('/[\r\n]+/', $source) as $_i => $line) {
            while ($line = trim($line, " \t")) {
                $c = $line{0};
                if ('"' === $c) {
                    if ($key && isset($entry)) {
                        if ('"' === substr($line, -1)) {
                            $line = substr($line, 1, -1);
                            $entry[$key][$idx][] = stripcslashes($line);
                        }
                    }
                } else {
                    if ('#' === $c) {
                        if (isset($entry['i'])) {
                            unset($entry);
                            $entry = $template;
                        }
                        $f = empty($line{1}) ? ' ' : $line{1};
                        $entry['#'][$f][] = trim(substr($line, 1 + strlen($f)), "/ \n\r\t");
                    } else {
                        if (preg_match('/^msg(id|str|ctxt|id_plural)(?:\[(\d+)\])?[ \t]+/', $line, $r)) {
                            $key = $r[1];
                            $idx = isset($r[2]) ? (int)$r[2] : 0;
                            if ('str' === $key) {
                                if (!isset($entry['i'])) {
                                    $entry['i'] = ++$i;
                                    $entries[$i] = &$entry;
                                }
                            } else {
                                if (!isset($entry) || isset($entry['i'])) {
                                    unset($entry);
                                    $entry = $template;
                                }
                            }
                            $line = substr($line, strlen($r[0]));
                            continue;
                        }
                    }
                }
                continue 2;
            }
        }
        unset($entry);
        $assets = array();
        foreach ($entries as $i => $entry) {
            if (empty($entry['id'])) {
                continue;
            }
            if (empty($entry['str'])) {
                $entry['str'] = array(array(''));
            }
            $asset = array('id' => null, 'source' => implode('', $entry['id'][0]), 'target' => implode('', $entry['str'][0]),);
            if (isset($entry['ctxt'][0])) {
                $asset['context'] = implode('', $entry['ctxt'][0]);
            }
            if (isset($entry['#'][' '])) {
                $asset['comment'] = implode("\n", $entry['#'][' ']);
            }
            if (isset($entry['#']['.'])) {
                $asset['notes'] = implode("\n", $entry['#']['.']);
            }
            if (isset($entry['#'][':'])) {
                if ($refs = implode(' ', $entry['#'][':'])) {
                    if ($refs = self::parseRefId($refs, $_id)) {
                        $asset['refs'] = $refs;
                    }
                    if ($_id) {
                        $asset['_id'] = $_id;
                    }
                }
            }
            if (isset($entry['#'][','])) {
                foreach ($entry['#'][','] as $flag) {
                    if (preg_match('/((?:no-)?\w+)-format/', $flag, $r)) {
                        $asset['format'] = $r[1];
                    } else {
                        if ($flag = self::parseFlag($flag)) {
                            $asset['flag'] = $flag;
                            break;
                        }
                    }
                }
            }
            $pidx = count($assets);
            $assets[] = $asset;
            if (isset($entry['id_plural']) || isset($entry['str'][1])) {
                $idx = 0;
                $num = max(2, count($entry['str']));
                while (++$idx < $num) {
                    $plural = array('id' => null, 'source' => '', 'target' => isset($entry['str'][$idx]) ? implode('', $entry['str'][$idx]) : '', 'plural' => $idx, 'parent' => $pidx,);
                    if (1 === $idx) {
                        $plural['source'] = isset($entry['id_plural'][0]) ? implode('', $entry['id_plural'][0]) : '';
                    }
                    $assets[] = $plural;
                }
            }
        }
        if (isset($assets[0]) && '' === $assets[0]['source']) {
            $headers = MBLTranslationPoHeaders::fromMsgstr($assets[0]['target']);
            $indexed = $headers['X-MBL-Lookup'];
            if ($indexed && 'text' !== $indexed) {
                foreach ($assets as $i => $asset) {
                    if (isset($asset['notes'])) {
                        $notes = $texts = array();
                        foreach (explode("\n", $asset['notes']) as $line) {
                            0 === strpos($line, 'Source text: ') ? $texts[] = substr($line, 13) : $notes[] = $line;
                        }
                        $assets[$i]['notes'] = implode("\n", $notes);
                        $assets[$i]['id'] = $asset['source'];
                        $assets[$i]['source'] = implode("\n", $texts);
                    }
                }
            }
        }

        return $assets;
    }

    public static function parseRefId($refs, &$_id)
    {
        if (false === ($n = strpos($refs, 'mbl:'))) {
            $_id = '';

            return $refs;
        }
        $_id = substr($refs, $n + 5, 24);
        $refs = substr_replace($refs, '', $n, 29);

        return trim($refs);
    }

    public static function parseFlag($text)
    {
        static $map;
        $flag = 0;
        foreach (explode(',', $text) as $needle) {
            if ($needle = trim($needle)) {
                if (!isset($map)) {
                    $map = unserialize('a:1:{i:4;s:8:"#, fuzzy";}');
                }
                foreach ($map as $mblFlag => $haystack) {
                    if (false !== stripos($haystack, $needle)) {
                        $flag = $mblFlag;
                        break 2;
                    }
                }
            }
        }

        return $flag;
    }
}