<?php

namespace SimpleObjectCollection;

class Collection implements ICollection, \Iterator, \Countable
{
    use IteratorTrait;
    use ObjectCheckerTrait;
    use CountableImplementationTrait;

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
     * @param object[] $elements
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
        $this->elements = [];

        $this->setClassName($className);

        if ($data == null) {
            return $this;
        }

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
        if (!empty($className)) {
            if (!empty($this->elements)) {
                throw new \LogicException(
                    'Cannot set class name for type hinting if collection is not empty ' . __METHOD__
                );
            }
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

        $this->elements[$this->getKeyByObject($object)] = $object;

        $this->positionMapInitialize();

        return $this;
    }

    protected function positionMapInitialize()
    {
        $this->arrayContainer = array_values($this->elements);
    }

    /**
     * @param object $object
     * @return int
     */
    protected function getKeyByObject($object)
    {
        if ($this->publicPropertyExists($object, 'id')) {
            return $object->id;
        } elseif ($this->publicMethodExists($object, 'getId')) {
            return $object->getId();
        } elseif ($this->publicMethodExists($object, 'get_id')) {
            return $object->get_id();
        }

        throw new \InvalidArgumentException(
            'object must have public property id or methods getId || get_id' . __METHOD__
        );
    }
}