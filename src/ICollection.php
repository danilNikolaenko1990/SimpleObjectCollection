<?php

namespace SimpleObjectCollection;

interface ICollection
{
    /**
     * @param string $className
     * @return Collection
     */
    public function setClassName($className);

    /**
     * find element by id
     * @param int|string $id
     * @return object
     */
    public function find($id);

    /**
     * remove element by id
     * @param int|string $id
     * @return Collection
     */
    public function remove($id);

    /**
     * add element
     * @param object $object
     * @return Collection
     */
    public function add($object);

    /**
     * @return integer
     */
    public function count();

    /**
     * @param object|object[] $data
     * @param string $className
     * @return Collection
     */
    public function reset($data = null, $className = '');

    /**
     * @param object[] $elements
     * @return Collection
     */
    public function addBatch(array $elements);
}