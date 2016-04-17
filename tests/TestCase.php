<?php

namespace tests;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
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
        $this->assertEquals($testObj1, $elements[$testObj1->getId()]);

        $testObj2 = $this->getTestObject(2);
        $collection->add($testObj2);
        $this->assertCount(1, $elements);
        $property = $reflectedCollecton->getProperty("elements");
        $property->setAccessible(true);

        $elements = $property->getValue($collection);

        $this->assertCount(2, $elements);
        $this->assertEquals($testObj2, $elements[$testObj2->getId()]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddGivenThrowExceptionIfNotObject_forExampleArray()
    {
        $collection = new Collection();
        $collection->add([]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddGivenThrowExceptionIfNotObject_forExampleInteger()
    {
        $collection = new Collection();
        $collection->add(1);
    }

    public function testFind_GivenFindObjectById_ReturnsObject()
    {
        $collection = new Collection();
        $testObj1 = $this->getTestObject(1);
        $testObj2 = $this->getTestObject(2);

        $collection->add($testObj1);
        $collection->add($testObj2);

        $result = $collection->find($testObj2->getId());
        $this->assertEquals($testObj2, $result);
    }

    public function testRemove_GivenRemoveObjectById()
    {
        $collection = new Collection();
        $testObj1 = $this->getTestObject(1);
        $testObj2 = $this->getTestObject(2);

        $collection->add($testObj1);
        $collection->add($testObj2);
        //now collection contains two elements

        $collection->remove($testObj1->getId());

        $this->assertEquals($collection->count(), 1);
    }

    public function testFind_GivenFindObjectReturnsNullIfObjectNotExists()
    {
        $collection = new Collection();

        $result = $collection->find(1);

        $this->assertEquals($result, null);
    }

    public function testCount_GivenCount_ReturnsCountObjects()
    {
        $collection = new Collection();
        $testObj1 = $this->getTestObject(1);
        $collection->add($testObj1);

        $this->assertEquals($collection->count(), 1);
        $testObj2 = $this->getTestObject(2);
        $collection->add($testObj2);
        $this->assertEquals($collection->count(), 2);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAdd_GivenAddObjectThrowsExceptionIfObjectHasNotGetIdMethod()
    {
        $collection = new Collection();
        $testObj1 = (object)['test' => 'test'];
        $collection->add($testObj1);
    }

    /**
     * @param $id
     * @return TestObject
     */
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