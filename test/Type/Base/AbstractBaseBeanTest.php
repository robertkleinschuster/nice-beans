<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Type\Base;

use ArrayIterator;
use ArrayObject;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Generator;
use IteratorAggregate;
use Niceshops\Bean\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use stdClass;

/**
 * Class AbstractBaseBeanTest
 * @package Niceshops\Bean
 */
class AbstractBaseBeanTest extends DefaultTestCase
{


    /**
     * @var AbstractBaseBean|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }


    /**
     * @group integration
     * @small
     */
    public function testTestClassExists()
    {
        $this->assertTrue(class_exists(AbstractBaseBean::class), "Class Exists");
        $this->assertTrue(is_a($this->object, AbstractBaseBean::class), "Mock Object is set");
    }

    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::type
     */
    public function testGetDataType_isString()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $name = "foo";
        $this->object->set($name, 'test');
        $this->assertSame(BeanInterface::DATA_TYPE_STRING, $this->invokeMethod($this->object, "getType", $name));
    }




    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::get
     */
    public function testGetData_NameNotFound()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $name = "foo";

        $this->expectException(BeanException::class);
        $this->expectExceptionCode(BeanException::ERROR_CODE_DATA_NOT_FOUND);

        $this->object->get($name);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::get
     * @throws BeanException
     */
    public function testGetData_NameFoundButNoValue()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $name = "foo";
        $this->object->set($name, null);

        $this->assertNull($this->object->get($name));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::get
     * @throws BeanException
     */
    public function testGetData_ValueFound()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $name = "foo";
        $this->object->set($name, 'bar');

        $this->assertSame("bar", $this->object->get($name));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::exists
     * @throws BeanException
     */
    public function testHasData_isTrue()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $name = "foo";

        $this->object->set($name, 'bar');
        $this->assertTrue($this->object->exists($name));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::exists
     * @throws BeanException
     */
    public function testHasData_isFalse()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $name = "foo";

        $this->assertFalse($this->object->exists($name));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::unset
     * @throws BeanException
     */
    public function testRemoveData_DataFound()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $arrData = ["foo" => "bar", "baz" => "bat"];
        $this->object->fromArray($arrData);
        $name = 'foo';
        $this->object->unset($name);
        $this->assertEquals('bat', $this->object->get('baz'));

        $this->expectException(BeanException::class);
        $this->object->get($name);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::reset
     */
    public function testResetData()
    {
        $arrData = ["foo" => "bar", "baz" => "bat"];
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $this->object->fromArray($arrData);
        $this->object->reset();

        $this->expectException(BeanException::class);
        $this->object->get('foo');
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::toArray
     */
    public function testToArray()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $this->object->set('foo', 'bar');
        $arrData = $this->object->toArray();
        $this->assertContains('bar', $arrData);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \Niceshops\Bean\Type\Base\AbstractBaseBean::toArray
     */
    public function testToArray_butNoData()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $arrData = $this->object->toArray();
        $found = false;
        foreach ($arrData as $key => $arrDatum) {
            if (strpos($key, 'Mock_') === false) {
                $found = true;
            }
        }
        $this->assertFalse($found);
    }
}
