<?php

namespace SimpleObjectCollection;

trait IteratorTrait
{
    public function current()
    {
        return $this->arrayContainer[$this->cursor];
    }

    public function next()
    {
        ++$this->cursor;
    }

    public function key()
    {
        return $this->cursor;
    }

    public function valid()
    {
        return isset($this->arrayContainer[$this->cursor]);
    }

    public function rewind()
    {
        $this->cursor = 0;
    }
}