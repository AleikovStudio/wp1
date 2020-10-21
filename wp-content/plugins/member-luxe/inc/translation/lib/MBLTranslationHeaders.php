<?php

class MBLTranslationHeaders extends ArrayIterator
{
    private $map = array();

    public function __construct(array $raw = array())
    {
        if ($raw) {
            $keys = array_keys($raw);
            $this->map = array_combine(array_map('strtolower', $keys), $keys);
            parent::__construct($raw);
        }
    }

    public function normalize($key)
    {
        $k = strtolower($key);

        return isset($this->map[$k]) ? $this->map[$k] : null;
    }

    public function add($key, $val)
    {
        $this->offsetSet($key, $val);

        return $this;
    }

    public function __toString()
    {
        $pairs = array();
        foreach ($this as $key => $val) {
            $pairs[] = trim($key) . ': ' . $val;
        }

        return implode("\n", $pairs);
    }

    public function trimmed($prop)
    {
        return trim($this->__get($prop));
    }

    public function has($key)
    {
        $k = strtolower($key);

        return isset($this->map[$k]);
    }

    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    public function __set($key, $val)
    {
        $this->offsetSet($key, $val);
    }

    public function offsetExists($k)
    {
        return !is_null($this->normalize($k));
    }

    public function offsetGet($k)
    {
        $k = $this->normalize($k);
        if (is_null($k)) {
            return '';
        }

        return parent::offsetGet($k);
    }

    public function offsetSet($key, $v)
    {
        $k = strtolower($key);
        if (isset($this->map[$k]) && $key !== $this->map[$k]) {
            parent::offsetUnset($this->map[$k]);
        }
        $this->map[$k] = $key;

        return parent::offsetSet($key, $v);
    }

    public function offsetUnset($key)
    {
        $k = strtolower($key);
        if (isset($this->map[$k])) {
            parent::offsetUnset($this->map[$k]);
            unset($this->map[$k]);
        }
    }

    public function export()
    {
        return $this->getArrayCopy();
    }

    public function jsonSerialize()
    {
        return $this->getArrayCopy();
    }

    public function toArray()
    {
        return $this->getArrayCopy();
    }

    public function keys()
    {
        return array_values($this->map);
    }
}
