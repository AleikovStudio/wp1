<?php

class MBLTranslationMo
{
    private $bin;
    private $msgs;
    private $head;
    private $hash;
    private $use_fuzzy = false;

    public function __construct(Iterator $export, Iterator $head = null)
    {
        if ($head) {
            $this->head = $head;
        } else {
            $this->head = new MBLTranslationHeaders(array(
                'Project-Id-Version' => 'MemberLux',
                'Language' => 'Russian',
                'Plural-Forms' => 'nplurals=2; plural=(n!=1);',
                'MIME-Version' => '1.0',
                'Content-Type' => 'text/plain; charset=UTF-8',
                'Content-Transfer-Encoding' => '8bit',
            ));
        }
        $this->msgs = $export;
        $this->bin = '';
    }

//    public function enableHash()
//    {
//        return $this->hash = new LocoMoTable;
//    }

//    public function useFuzzy()
//    {
//        $this->use_fuzzy = true;
//    }

    public function setHeader($key, $val)
    {
        $this->head->add($key, $val);

        return $this;
    }

//    public function setProject(LocoProject $Proj)
//    {
//        return $this->setHeader('Project-Id-Version', $Proj->proj_name)->setHeader($key, $val);
//    }

//    public function setLocale(LocoProjectLocale $Loc)
//    {
//        return $this->setHeader('Language', $Loc->label)->setHeader('Plural-Forms', (string)$Loc->getPlurals());
//    }

    public function count()
    {
        return count($this->msgs);
    }

    public function compile()
    {
        $table = array('');
        $sources = array('');
        $targets = array((string)$this->head);
        $fuzzy_flag = 4;
        $skip_fuzzy = !$this->use_fuzzy;
        foreach ($this->msgs as $r) {
            if (isset($r['flag']) && $skip_fuzzy && $fuzzy_flag === $r['flag']) {
                continue;
            }
            $msgid = $r['key'];
            if (isset($r['context'])) {
                $msgctxt = $r['context'];
                if (is_string($msgctxt) && '' !== $msgctxt) {
                    if (!$msgid && '0' !== $msgid) {
                        $msgid = '(' . $msgctxt . ')';
                    }
                    $msgid = $msgctxt . "\x04" . $msgid;
                }
            }
            if (!$msgid && '0' !== $msgid) {
                continue;
            }
            $msgstr = $r['target'];
            if (!$msgstr && '0' !== $msgstr) {
                continue;
            }
            $table[] = $msgid;
            if (isset($r['plurals'])) {
                foreach ($r['plurals'] as $i => $p) {
                    if ($i === 0) {
                        $msgid .= "\0" . $p['key'];
                    }
                    $msgstr .= "\0" . $p['target'];
                }
            }
            $sources[] = $msgid;
            $targets[] = $msgstr;
        }
        asort($sources, SORT_STRING);
        $this->bin = "\xDE\x12\x04\x95\x00\x00\x00\x00";
        $n = count($sources);
        $this->writeInteger($n);
        $offset = 28;
        $this->writeInteger($offset);
        $offset += $n * 8;
        $this->writeInteger($offset);
        if ($this->hash) {
            sort($table, SORT_STRING);
            $this->hash->compile($table);
            $s = $this->hash->count();
        } else {
            $s = 0;
        }
        $this->writeInteger($s);
        $offset += $n * 8;
        $this->writeInteger($offset);
        if ($s) {
            $offset += $s * 4;
        }
        $source = '';
        foreach ($sources as $i => $str) {
            $source .= $str . "\0";
            $this->writeInteger($strlen = strlen($str));
            $this->writeInteger($offset);
            $offset += $strlen + 1;
        }
        $target = '';
        foreach (array_keys($sources) as $i) {
            $str = $targets[$i];
            $target .= $str . "\0";
            $this->writeInteger($strlen = strlen($str));
            $this->writeInteger($offset);
            $offset += $strlen + 1;
        }
        if ($this->hash) {
            $this->bin .= $this->hash->__toString();
        }
        $this->bin .= $source;
        $this->bin .= $target;

        return $this->bin;
    }

    private function writeInteger($num)
    {
        $this->bin .= pack('V', $num);

        return $this;
    }
}