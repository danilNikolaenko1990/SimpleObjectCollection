<?php

namespace lib\Common;

class Collection implements ICollection
{
    protected $elements = [];

    public function setClassName($className)
    {
        // TODO: Implement setClassName() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
    }

    public function add($object)
    {
        $this->elements[] = $object;
        // TODO: Implement add() method.
    }

    public function count()
    {
        // TODO: Implement count() method.
    }
}