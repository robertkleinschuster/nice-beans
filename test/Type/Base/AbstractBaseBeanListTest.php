<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Type\Base;

use Niceshops\Bean\PHPUnit\DefaultTestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class AbstractBaseBeanTest
 * @package Niceshops\Bean
 */
class AbstractBaseBeanListTest extends DefaultTestCase
{


    /**
     * @var AbstractBaseBeanList|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(AbstractBaseBeanList::class)->getMockForAbstractClass();
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
        $this->assertTrue(class_exists(AbstractBaseBeanList::class), "Class Exists");
        $this->assertTrue(is_a($this->object, AbstractBaseBeanList::class), "Mock Object is set");
    }

    public function mockBean(): BeanInterface
    {
        return $this->getMockBuilder(AbstractBaseBean::class)->getMockForAbstractClass();
    }

    /**
     * @group integration
     * @small
     */
    public function testList()
    {
        $this->object->push($this->mockBean()->set('foo', 'bar'));
        $this->object->push($this->mockBean()->set('foo', 'bar'));
        $this->object->push($this->mockBean()->set('foo', 'bar'));
        $this->assertCount(3, $this->object);
        $i = 0;
        foreach ($this->object as $key => $value) {
            $i++;
            $this->assertEquals('bar', $value->get('foo'));
        }
        $this->assertEquals(3, $i);
    }

}
