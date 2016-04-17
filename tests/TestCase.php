<?php

namespace tests;

use \lib\Common\Collection;

class TestCase extends \PHPUnit_Framework_TestCase
{

    public function testAdd_GivenAddsIntoCollection_elementsFills()
    {
        $collection = new Collection();
        $testObj1 = $this->getTestObject(1);
        $collection->add($testObj1);

        $reflectedCollecton = new \ReflectionClass($collection);
        $property = $reflectedCollecton->getProperty("elements");
        $property->setAccessible(true);
        $elements = $property->getValue($collection);

        $this->assertNotEmpty($elements);
        $this->assertEquals($testObj1, $elements[0]);

        $testObj2 = $this->getTestObject(2);
        $collection->add($testObj2);
        $this->assertCount(1, $elements);
        $property = $reflectedCollecton->getProperty("elements");
        $property->setAccessible(true);

        $elements = $property->getValue($collection);

        $this->assertCount(2, $elements);
        $this->assertEquals($testObj2, $elements[1]);
    }

    protected function getTestObject($id)
    {
        return new TestObject($id);
    }


}

class TestObject
{
    protected $id;

    public function __construct($id = null)
    {
        if ($id !== null) {
            $this->id = $id;
        }
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}