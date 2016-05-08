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

        $elements = $this->getPropertyValue($collection, "elements");

        $this->assertNotEmpty($elements);
        $this->assertEquals($testObj1, $elements[$testObj1->getId()]);

        $testObj2 = $this->getTestObject(2);
        $collection->add($testObj2);
        $this->assertCount(1, $elements);

        $elements = $this->getPropertyValue($collection, "elements");

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

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddGivenThrowExceptionIfArgumentConstructNotObjectNotArray_forExampleInteger()
    {
        new Collection(1);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddGivenThrowExceptionIfArgumentConstructNotObjectNotArray_forExampleArrayInteger()
    {
        new Collection([1]);
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

    public function testAddAddedObjectMustHaveIdProperty()
    {
        $collection = new Collection();
        $testObj1 = (object)['id' => 1];
        $collection->add($testObj1);

        $elements = $this->getPropertyValue($collection, "elements");

        $this->assertEquals($testObj1, $elements[$testObj1->id]);
    }

    public function test_Iterator()
    {
        $testObjects = $this->getTestObjects($quantity = 10);

        $collection = new Collection($testObjects);

        $iterationCount = 0;
        foreach ($collection as $object) {
            $iterationCount++;
            $this->assertInstanceOf(TestObject::class, $object);
        }

        if ($iterationCount !== $quantity) {
            $this->fail('there is no one iteration');
        }
    }

    public function testCollectOneObjectByConstructor()
    {
        $testObject = $this->getTestObject($id = 1);

        $collection = new Collection($testObject);

        $this->assertEquals($testObject, $collection->find($id));
    }

    public function testCollectSomeObjectsByConstructor()
    {
        $testObjects = $this->getTestObjects($quantity = 10);

        $collection = new Collection($testObjects);

        $this->assertEquals(count($testObjects), $collection->count());
    }

    public function testAddBatch()
    {
        $testObject = $this->getTestObject(11);

        $someTestObjects = $this->getTestObjects(10);

        $collection = new Collection($testObject);

        $collection->addBatch($someTestObjects);

        $elements = $this->getPropertyValue($collection, 'elements');

        $this->assertCount(11, $elements);

    }

    public function testResetCollectSomeObjects()
    {
        $testObjects = $this->getTestObjects($quantity = 10);

        $collection = new Collection();

        $collection->reset($testObjects);

        $this->assertEquals(count($testObjects), $collection->count());
    }

    public function testCount()
    {
        $testObjects = $this->getTestObjects($quantity = 10);

        $collection = new Collection($testObjects);

        $this->assertEquals(count($testObjects), count($collection));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testSetClassNameThrowsExceptionIfWrongObject()
    {
        $testObject = $this->getTestObject(1);

        $collection = new Collection();
        $collection->setClassName(TestObject::class);

        $collection->add($testObject);
        $collection->add(new WrongTestObject());
    }

    /**
     * @expectedException \LogicException
     */
    public function testSetClassNameThrowsExceptionIfCollectionNotEmty()
    {
        $testObject = $this->getTestObject(1);

        $collection = new Collection($testObject);
        $collection->setClassName(TestObject::class);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTypeHintByClassNameInConstructorThrowsException()
    {
        $testObjects = $this->getTestObjects(5);
        $wrongObj = new WrongTestObject();
        array_push($testObjects, $wrongObj);
        new Collection($testObjects, TestObject::class);

    }

    /**
     * @param $id
     * @return TestObject
     */
    protected function getTestObject($id)
    {
        return new TestObject($id);
    }

    /**
     * @param int $quantity
     * @return TestObject[]
     */
    protected function getTestObjects($quantity = 1)
    {
        $testObjects = [];
        for ($k = 1; $k <= $quantity; $k++) {
            $testObjects[] = $this->getTestObject($k);
        }

        return $testObjects;
    }

    /**
     * @param $object
     * @param $object
     * @return mixed
     */
    protected function getPropertyValue($object, $propertyName)
    {
        $reflectedObject = new \ReflectionClass($object);
        $property = $reflectedObject->getProperty($propertyName);
        $property->setAccessible(true);
        return $property->getValue($object);
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
};


class WrongTestObject
{
    public function getId()
    {
        return 1;
    }
}