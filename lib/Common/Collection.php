<?php

namespace lib\Common;

/**
 * Class Collection
 * @package lib\Common
 */
class Collection implements ICollection
{
    protected $elements = [];

    public function setClassName($className)
    {
        // TODO: Implement setClassName() method.
    }

    /**
     * @param $id
     * @return object|null
     */
    public function find($id)
    {
        if (array_key_exists($id, $this->elements)) {
            return $this->elements[$id];
        }
        return null;

    }

    /**
     * @param $id
     * @return Collection
     */
    public function remove($id)
    {
        unset($this->elements[$id]);
        return $this;
    }

    /**
     * @param object $object
     * @return Collection
     */
    public function add($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('argument must be object ' . __METHOD__);
        }

        if (!in_array('getId', get_class_methods($object))) {
            throw new \InvalidArgumentException('object must have getId() method ' . __METHOD__);
        };

        $this->elements[$object->getId()] = $object;
        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }
}