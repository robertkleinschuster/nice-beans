<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace ParsTest\Bean\Loader;

use Pars\Bean\Loader\AbstractBeanLoader;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Pars\Bean
 */
class AbstractBeanLoaderTest extends \Pars\Pattern\PHPUnit\DefaultTestCase
{


    /**
     * @var AbstractBeanLoader|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp(): void
    {
        $this->object = $this->getMockBuilder(AbstractBeanLoader::class)->setMethods(['load', 'init'])->disableOriginalConstructor()->getMockForAbstractClass();
        $this->object->method('init')->willReturn(3);
        $this->object->method('load')->willReturnOnConsecutiveCalls(['foo' => 'bar'], ['foo' => 'baz'], ['foo' => 'bam'], null);
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
        $this->assertTrue(class_exists(AbstractBeanLoader::class), "Class Exists");
        $this->assertTrue(is_a($this->object, AbstractBeanLoader::class), "Mock Object is set");
    }

    /**
     * @group unit
     * @small
     * @covers \ParsTest\Bean\Loader\AbstractBeanLoader::execute
     * @covers \ParsTest\Bean\Loader\AbstractBeanLoader::valid
     * @covers \ParsTest\Bean\Loader\AbstractBeanLoader::next
     * @covers \ParsTest\Bean\Loader\AbstractBeanLoader::current
     * @covers \ParsTest\Bean\Loader\AbstractBeanLoader::key
     * @covers \ParsTest\Bean\Loader\AbstractBeanLoader::rewind
     */
    public function testIterator()
    {
        $this->object->execute();
        $data = [];
        foreach ($this->object as $item) {
            $data[] = $item;
        }
        $this->assertSame([['foo' => 'bar'], ['foo' => 'baz'], ['foo' => 'bam']], $data);
        // Iterate again
        $data = [];
        foreach ($this->object as $item) {
            $data[] = $item;
        }
        $this->assertSame([['foo' => 'bar'], ['foo' => 'baz'], ['foo' => 'bam']], $data);
        // Iterate again, execute call is ignored and just rewinds the iterator
        $this->object->execute();
        $data = [];
        foreach ($this->object as $item) {
            $data[] = $item;
        }
        $this->assertSame([['foo' => 'bar'], ['foo' => 'baz'], ['foo' => 'bam']], $data);
    }

    /**
     * @group unit
     * @small
     * @covers \ParsTest\Bean\Loader\AbstractBeanLoader::execute
     */
    public function testExecuteException()
    {
        $this->expectException(\LogicException::class);
        $this->object->execute();
        $this->object->execute();
    }
}
