<?php

namespace lib\Common;

interface ICollection
{
    public function setClassName($className);
    /**
     * find element by id
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * remove element by id
     * @param $id
     * @return mixed
     */
    public function remove($id);

    /**
     * add element
     * @param $object
     * @return mixed
     */
    public function add($object);
}