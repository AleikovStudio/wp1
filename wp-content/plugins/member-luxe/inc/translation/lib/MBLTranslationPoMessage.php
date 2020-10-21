<?php

class MBLTranslationPoMessage extends ArrayObject
{
    public function __construct(array $r)
    {
        $r['key'] = $r['source'];
        parent::__construct($r);
    }

    public function __get($prop)
    {
        return isset($this[$prop]) ? $this[$prop] : null;
    }

    private function _getFlags()
    {
        $flags = array();
        $plurals = $this->__get('plurals');
        if (4 === $this->__get('flag')) {
            $flags[] = 'fuzzy';
        } else {
            if ($plurals) {
                foreach ($plurals as $child) {
                    if (4 === $child->__get('flag')) {
                        $flags[] = 'fuzzy';
                        break;
                    }
                }
            }
        }
        if ($f = $this->__get('format')) {
            $flags[] = $f . '-format';
        } else {
            if (isset($plurals[0]) && ($f = $plurals[0]->__get('format'))) {
                $flags[] = $f . '-format';
            }
        }

        return $flags;
    }

    public function getHash()
    {
        $msgid = $this['source'];
        if (isset($this['context'])) {
            $msgctxt = $this['context'];
            if (is_string($msgctxt) && '' !== $msgctxt) {
                if (!$msgid && '0' !== $msgid) {
                    $msgid = '(' . $msgctxt . ')';
                }
                $msgid = $msgctxt . "\x04" . $msgid;
            }
        }
        if (isset($this['plurals'])) {
            foreach ($this['plurals'] as $p) {
                $msgid .= "\0" . $p->getHash();
                break;
            }
        }

        return $msgid;
    }

    public function getMD5Hash()
    {
        return md5($this->getHash());
    }

    public function __toString()
    {
        $s = '';
        try {
            if ($text = $this->__get('comment')) {
                $s .= MBLTranslationPo::prefix($text, '# ') . "\n";
            }
            if ($text = $this->__get('notes')) {
                $s .= MBLTranslationPo::prefix($text, '#. ') . "\n";
            }
            if ($text = $this->__get('refs')) {
                $s .= MBLTranslationPo::refs($text) . "\n";
            }
            if ($texts = $this->_getFlags()) {
                $s .= '#, ' . implode(', ', $texts) . "\n";
            }
            $text = $this->__get('context');
            if (is_string($text) && isset($text{0})) {
                $s .= MBLTranslationPo::pair('msgctxt', $text) . "\n";
            }
            $s .= MBLTranslationPo::pair('msgid', $this['key']) . "\n";
            $target = $this['target'];
            if (is_array($plurals = $this->__get('plurals'))) {
                if ($plurals) {
                    foreach ($plurals as $i => $p) {
                        if (0 === $i) {
                            $s .= MBLTranslationPo::pair('msgid_plural', $p['key']) . "\n";
                            $s .= MBLTranslationPo::pair('msgstr[0]', $target) . "\n";
                        }
                        $s .= MBLTranslationPo::pair('msgstr[' . (++$i) . ']', $p['target']) . "\n";
                    }
                } else {
                    if (isset($this['plural_key'])) {
                        $s .= MBLTranslationPo::pair('msgid_plural', $this['plural_key']) . "\n";
                        $s .= MBLTranslationPo::pair('msgstr[0]', $target) . "\n";
                    } else {
                        trigger_error('Missing plural_key in zero plural export');
                        $s .= MBLTranslationPo::pair('msgstr', $target) . "\n";
                    }
                }
            } else {
                $s .= MBLTranslationPo::pair('msgstr', $target) . "\n";
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }

        return $s;
    }
}