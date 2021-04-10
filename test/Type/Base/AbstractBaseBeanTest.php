<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace ParsTest\Bean\Type\Base;

use Pars\Bean\PHPUnit\DefaultTestCase;
use Pars\Bean\Type\Base\AbstractBaseBean;
use Pars\Bean\Type\Base\AbstractBaseBeanList;
use Pars\Bean\Type\Base\BeanException;
use Pars\Bean\Type\Base\BeanInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class AbstractBaseBeanTest
 * @package Pars\Bean
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
    protected function setUp(): void
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
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
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::type
     */
    public function testGetDataType()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $name = "foo";
        $this->object->set($name, 'test');
        $this->assertSame(BeanInterface::DATA_TYPE_UNKNOWN, $this->invokeMethod($this->object, "type", $name));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::type
     */
    public function testGetDataType_isString()
    {
        $this->object = new class() extends AbstractBaseBean {
            public ?string $foo = null;
        };
        $name = "foo";
        $this->object->set($name, 'test');
        $this->assertSame(BeanInterface::DATA_TYPE_STRING, $this->invokeMethod($this->object, "type", $name));
    }

    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::type
     */
    public function testGetDataType_isDateTime()
    {
        $this->object = new class() extends AbstractBaseBean {
            public ?\DateTime $date = null;
        };
        $name = "date";
        $this->object->set($name, new \DateTime());
        $this->assertSame(\DateTime::class, $this->invokeMethod($this->object, "type", $name));
    }

    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::get
     */
    public function testGetData_NameNotFound()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $name = "foo";

        $this->expectError();
        $this->object->get($name);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::get
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
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::get
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
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::exists
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
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::exists
     * @throws BeanException
     */
    public function testHasData_isFalse()
    {
        $object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();

        $this->assertFalse($object->exists('footest'));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::unset
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

        $this->expectError();
        $this->object->get($name);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::reset
     */
    public function testResetData()
    {
        $arrData = ["foo" => "bar", "baz" => "bat"];
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $this->object->fromArray($arrData);
        $this->object->reset();
        $this->expectError();
        $this->object->get('foo');
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::reset
     */
    public function testInitialized()
    {
        $arrData = ["foo" => "bar", "baz" => "bat"];

        $this->object = new class() extends AbstractBaseBean {
            public ?string $foo;
            public ?string $baz;
        };
        $this->object->fromArray($arrData);
        $this->object->reset();
        $this->object->set('foo', null);
        $this->assertTrue($this->object->initialized('foo'));
        $this->assertFalse($this->object->initialized('baz'));
    }

    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::toArray
     */
    public function testToArray()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $this->object->set('foo', 'bar');
        $arrData = $this->object->toArray();
        $this->assertContains('bar', $arrData);
        $this->assertEquals(['foo' => 'bar', '__class' => get_class($this->object)], $arrData);
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::toArray
     */
    public function testToArray_butNoData()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBean::class)->disableOriginalConstructor()->getMockForAbstractClass();
        $arrData = $this->object->toArray();
        $found = false;
        foreach ($arrData as $key => $arrDatum) {
            if (strpos($key, '__class') === false) {
                $found = true;
            }
        }
        $this->assertFalse($found);
    }

    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::type
     */
    public function testType()
    {
        $bean = new class() extends AbstractBaseBean {
            public ?string $test = null;
            public ?AbstractBaseBeanList $list = null;
        };
        $this->assertEquals($bean::DATA_TYPE_STRING, $bean->type('test'));
        $this->assertEquals(AbstractBaseBeanList::class, $bean->type('list'));
    }


    /**
     * @group  unit
     * @small
     *
     * @covers \ParsTest\Bean\Type\Base\AbstractBaseBean::reset
     */
    public function testSerialize()
    {
        $arrData = ["foo" => "bar", "baz" => "bat"];

        $this->object = new class() extends AbstractBaseBean {
            public ?string $foo;
            public ?string $baz;
        };
        $this->object->fromArray($arrData);
        $data = $this->object->toArray();
        $serialized = $this->object->serialize();
        $this->object->reset();
        $this->object->unserialize($serialized);
        $this->assertEquals($data, $this->object->toArray());
    }
}
