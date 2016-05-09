<?php

namespace SimpleObjectCollection;

trait ObjectCheckerTrait
{
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
     * @param $methodName
     * @return bool
     */
    protected function publicMethodExists($object, $methodName)
    {
        return in_array($methodName, get_class_methods($object));
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

        if ($this->publicMethodExists($object, 'getId') ||
            $this->publicMethodExists($object, 'get_id') ||
            $this->publicPropertyExists($object, 'id')
        ) {
            return true;
        };

        throw new \InvalidArgumentException('object must have getId() or get_id() method or public property id ' . __METHOD__);
    }
}