<?php

namespace lib\Common;

/**
 * Class Collection
 * @package lib\Common
 */
class Collection implements ICollection, \Iterator, \Countable
{
    protected $elements = [];

    protected $arrayContainer = [];

    protected $position = 0;

    /**
     * collect array of the objects
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        $this->position = 0;

        if ($data == null) {
            return;
        }
        if (is_object($data)) {
            $this->add($data);
            return;
        } elseif (is_array($data)) {
            foreach ($data as $object) {
                $this->add($object);
            }
            return;
        }

        throw new \InvalidArgumentException(
            'argument must be object or array of the objects, ' . gettype($data) . ' given ' . __METHOD__);
    }

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
        if ($this->elements[$id]) {
            unset($this->elements[$id]);
            $this->positionMapInitialize();
        }

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

        $this->positionMapInitialize();

        return $this;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    public function current()
    {
        return $this->arrayContainer[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->arrayContainer[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    protected function positionMapInitialize()
    {
        $this->arrayContainer = array_values($this->elements);
    }
}