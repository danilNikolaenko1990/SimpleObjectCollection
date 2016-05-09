<?php

namespace SimpleObjectCollection;

interface ICollection
{
    /**
     * @param $className
     * @return Collection
     */
    public function setClassName($className);

    /**
     * find element by id
     * @param $id
     * @return object
     */
    public function find($id);

    /**
     * remove element by id
     * @param $id
     * @return Collection
     */
    public function remove($id);

    /**
     * add element
     * @param $object
     * @return Collection
     */
    public function add($object);

    /**
     * @return integer
     */
    public function count();

    /**
     * @param null $data
     * @param string $className
     * @return Collection
     */
    public function reset($data = null, $className = '');

    /**
     * @param object[] $elements
     * @return Collection
     */
    public function addBatch($elements);
}