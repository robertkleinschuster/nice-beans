<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace ParsTest\Bean\Converter;

use Pars\Bean\Converter\AbstractBeanConverter;
use Pars\Bean\Converter\BeanConverterInterface;
use Pars\Bean\Converter\ConverterBeanDecorator;
use Pars\Bean\Type\Base\AbstractBaseBean;
use Pars\Bean\Type\Base\BeanInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Pars\Bean
 */
class AbstractBeanConverterTest extends \Pars\Bean\PHPUnit\DefaultTestCase
{


    /**
     * @var AbstractBeanConverter|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp(): void
    {
        $this->object = $this->getMockBuilder(AbstractBeanConverter::class)->disableOriginalConstructor()->getMockForAbstractClass();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }


    /**
     * @group unit
     * @small
     * @coversDefaultClass  AbstractBeanConverter
     */
    public function testTestClassExists()
    {
        $this->assertTrue(class_exists(AbstractBeanConverter::class), "Class Exists");
        $this->assertTrue(is_a($this->object, AbstractBeanConverter::class), "Mock Object is set");
        $this->assertInstanceOf(BeanConverterInterface::class, $this->object);
    }

    /**
     * @return BeanInterface
     */
    private function createMockBean(): BeanInterface
    {
        return $this->getMockBuilder(AbstractBaseBean::class)->getMockForAbstractClass();
    }

    /**
     * @group unit
     * @small
     * @covers AbstractBeanConverter::convert
     */
    public function testConvert()
    {
        $this->assertInstanceOf(ConverterBeanDecorator::class, $this->object->convert($this->createMockBean()));
    }
}
