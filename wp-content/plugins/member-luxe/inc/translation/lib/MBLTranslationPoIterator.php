<?php

class MBLTranslationPoIterator implements Iterator
{
    private $po;
    private $headers;
    private $i;
    private $t;
    private $j;
    private $z;
    private $m;

    public function __construct($po)
    {
        $this->po = $po;
        $this->t = count($po);
        if (!isset($po[0])) {
            throw new InvalidArgumentException('Empty PO data');
        }
        $h = $po[0];
        if ('' === $h['source'] && empty($h['context'])) {
            $this->z = 0;
        } else {
            $this->z = -1;
        }
    }

    public static function fromSource( $src ){
        return new MBLTranslationPoIterator( MBLTranslationParser::parse($src) );
    }

    public function msgfmt(){
        $mo = new MBLTranslationMo( $this, $this->getHeaders() );
        return $mo->compile();
    }

    /**
     * Get final UTF-8 string for writing to file
     * @return string
     */
    public function msgcat(){
        $po = (string) $this;
//        // Prepend byte order mark only if configured
//        if( Loco_data_Settings::get()->po_utf8_bom ){
//            $po = "\xEF\xBB\xBF".$po;
//        }
        return $po;
    }

    public function rewind()
    {
        $this->i = $this->z;
        $this->j = -1;
        $this->next();
    }

    public function key()
    {
        return $this->j;
    }

    public function valid()
    {
        return is_int($this->i);
    }

    public function next()
    {
        $i = $this->i;
        while (++$i < $this->t) {
            $this->j++;
            $this->i = $i;

            return;
        }
        $this->i = null;
        $this->j = null;
    }

    public function current()
    {
        $i = $this->i;
        $po = $this->po;
        $parent = new MBLTranslationPoMessage($po[$i]);
        $plurals = array();
        while (isset($po[++$i]['parent'])) {
            $this->i = $i;
            $plurals[] = new MBLTranslationPoMessage($po[$i]);
        }
        if ($plurals) {
            $parent['plurals'] = $plurals;
        }

        return $parent;
    }

    public function getArrayCopy()
    {
        $po = $this->po;
        if (0 === $this->z) {
            $po[0]['target'] = (string)$this->getHeaders();
        }

        return $po;
    }

    public function getHeaders()
    {
        if (!$this->headers) {
            $header = $this->po[0];
            if (0 === $this->z) {
                $this->headers = MBLTranslationPoHeaders::fromMsgstr($header['target']);
            } else {
                $this->headers = new MBLTranslationPoHeaders;
            }
        }

        return $this->headers;
    }

    public function initPo()
    {
        if (0 === $this->z) {
            unset($this->po[0]['flag']);
        }

        return $this;
    }

    public function initPot()
    {
        if (0 === $this->z) {
            $this->po[0]['flag'] = 4;
        }

        return $this;
    }

    public function strip()
    {
        $po = $this->po;
        $i = count($po);
        $z = $this->z;
        while (--$i > $z) {
            $po[$i]['target'] = '';
        }
        $this->po = $po;

        return $this;
    }

    public function __toString()
    {
        try {
            if (0 === $this->z) {
                $h = $this->po[0];
            } else {
                $h = array('source' => '');
            }
            $h['target'] = (string)$this->getHeaders();
            $msg = new MBLTranslationPoMessage($h);
            $s = $msg->__toString();
            foreach ($this as $msg) {
                $s .= "\n" . $msg->__toString();
            }
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
            $s = '';
        }

        return $s;
    }

    public function getHashes()
    {
        $a = array();
        foreach ($this as $msg) {
            $a[] = $msg->getHash();
        }
        sort($a, SORT_STRING);

        return $a;
    }

    public function equalSource(MBLTranslationPoIterator $that)
    {
        $a = $this->getHashes();
        $b = $that->getHashes();
        if (count($a) !== count($b)) {
            return false;
        }
        foreach ($a as $i => $hash) {
            if ($hash !== $b[$i]) {
                return false;
            }
        }

        return true;
    }

    public function set($key, $value)
    {
        $this->rewind();

        while ($this->valid()) {
            if ($this->current()->getHash() == $key) {
                $this->po[$this->i]['target'] = $value;
            }
            $this->next();
        }

        $this->rewind();

        return $this;
    }

    public function setByMD5($key, $value)
    {
        $this->rewind();

        while ($this->valid()) {
            if ($this->current()->getMD5Hash() == $key) {
                $this->po[$this->i]['target'] = $value;
            }
            $this->next();
        }

        $this->rewind();

        return $this;
    }

    public function getByMD5($key)
    {
        $this->rewind();

        while ($this->valid()) {
            if ($this->current()->getMD5Hash() == $key) {
                $result = $this->current();
                $this->rewind();
                return $result;
            }
            $this->next();
        }

        $this->rewind();

        return null;
    }
}