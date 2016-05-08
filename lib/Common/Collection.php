<?php

namespace lib\Common;

/**
 * Class Collection
 * @package lib\Common
 */
class Collection implements ICollection, \Iterator, \Countable
{
    /** @var array */
    protected $elements = [];

    /** @var array */
    protected $arrayContainer = [];

    /** @var int */
    protected $cursor = 0;

    /** @var string */
    protected $className = null;

    /**
     * collect array of the objects
     * @param array|object $data
     * @param string $className
     */
    public function __construct($data = null, $className = '')
    {
        $this->cursor = 0;

        $this->reset($data, $className);
    }

    /**
     * @param $elements
     * @return Collection
     */
    public function addBatch($elements)
    {
        foreach ($elements as $object) {
            $this->add($object);
        }

        return $this;
    }

    /**
     * @param null $data
     * @param string $className
     * @return Collection
     */
    public function reset($data = null, $className = '')
    {
        if ($data == null) {
            return $this;
        }

        $this->setClassName($className);

        if (is_object($data)) {
            $this->add($data);
            return $this;
        } elseif (is_array($data)) {
            return $this->addBatch($data);
        }

        throw new \InvalidArgumentException(
            'argument must be object or array of the objects, ' . gettype($data) . ' given ' . __METHOD__
        );
    }

    /**
     * @param string $className
     * @return Collection
     */
    public function setClassName($className = '')
    {
        if (!empty($this->elements)) {
            throw new \LogicException(
                'Cannot set class name for type hinting if collection is not empty ' . __METHOD__
            );
        }

        if (!empty($className)) {
            $this->className = $className;
        }

        return $this;
    }

    /**
     * @param string|int $id
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
        $this->validateObject($object);

        if (!empty($this->className)) {
            if (!($object instanceOf $this->className)) {
                throw new \InvalidArgumentException(
                    'argument must be instance of ' . $this->className . ', in ' . __METHOD__
                );
            }
        }

        $this->elements[$this->getKeyByObjectId($object)] = $object;

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

    protected function positionMapInitialize()
    {
        $this->arrayContainer = array_values($this->elements);
    }

    /**
     * @param $object
     * @param $propertyName
     * @return bool
     */
    protected function publicPropertyExists($object, $propertyName)
    {
        $properties = get_object_vars($object);
        if (empty($properties)) {
            return false;
        }

        return in_array($propertyName, array_keys($properties));
    }

    /**
     * @param $object
     * @return mixed
     */
    protected function getKeyByObjectId($object)
    {
        if ($this->publicPropertyExists($object, 'id')) {
            return $object->id;
            //todo переписать, нужно проверять на наличие public методов, а не всех подряд
        } elseif (method_exists($object, 'getId')) {
            return $object->getId();
        } elseif (method_exists($object, 'get_id')) {
            return $object->get_id();
        }

        throw new \InvalidArgumentException(
            'object must have public property id or methods getId || get_id' . __METHOD__
        );
    }

    /**
     * @param $object
     * @return bool
     */
    protected function validateObject($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('argument must be object ' . __METHOD__);
        }

        if (method_exists($object, 'getId') ||
            method_exists($object, 'get_id') ||
            $this->publicPropertyExists($object, 'id')
        ) {
            return true;
        };

        throw new \InvalidArgumentException('object must have getId() or get_id() method or public property id ' . __METHOD__);
    }
}