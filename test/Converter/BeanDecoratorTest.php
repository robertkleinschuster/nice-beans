<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Bean\Converter;

use Pars\Bean\Type\Base\AbstractBaseBean;
use Pars\Bean\Type\Base\BeanAwareInterface;
use Pars\Bean\Type\Base\BeanInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Pars\Bean
 * @uses \Pars\Bean\Converter\BeanConverterAwareTrait
 * @uses \Pars\Bean\Converter\AbstractBeanConverter
 */
class BeanDecoratorTest extends \Pars\Pattern\PHPUnit\DefaultTestCase
{


    /**
     * @var ConverterBeanDecorator|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(ConverterBeanDecorator::class)->setMethods(['getBean'])->disableOriginalConstructor()->getMockForAbstractClass();
        $this->object->setBeanConverter($this->createMockConverter());
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @return BeanConverterInterface
     */
    private function createMockConverter(): BeanConverterInterface
    {
        $mockConverter = $this->getMockBuilder(AbstractBeanConverter::class)->disableOriginalConstructor()->setMethods(['convertValueFromBean', 'convertValueToBean'])->getMockForAbstractClass();
        $mockConverter->method('convertValueFromBean')->willReturnArgument(0);
        $mockConverter->method('convertValueToBean')->willReturnArgument(0);
        return $mockConverter;
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
     * @coversDefaultClass \Pars\Bean\Converter\ConverterBeanDecorator
     */
    public function testTestClassExists()
    {
        $this->assertTrue(class_exists(ConverterBeanDecorator::class), "Class Exists");
        $this->assertTrue(is_a($this->object, ConverterBeanDecorator::class), "Mock Object is set");
        $this->assertInstanceOf(BeanInterface::class, $this->object);
        $this->assertInstanceOf(BeanAwareInterface::class, $this->object);
        $this->assertInstanceOf(BeanConverterAwareInterface::class, $this->object);
    }

    public function dataProvider_CallBeanMethod()
    {
        yield 'set' => ['set', ['foo', 'bar'], ['convertValueToBean']];
        yield 'get' => ['get', ['foo'], ['convertValueFromBean']];
        yield 'has' => ['exists', ['foo']];
        yield 'unset' => ['unset', ['foo']];
        yield 'reset' => ['reset', []];
        yield 'getType' => ['type', ['foo']];
    }

    /**
     * @group unit
     * @small
     * @covers       \Pars\Bean\Converter\ConverterBeanDecorator::set
     * @covers       \Pars\Bean\Converter\ConverterBeanDecorator::get
     * @covers       \Pars\Bean\Converter\ConverterBeanDecorator::exists
     * @covers       \Pars\Bean\Converter\ConverterBeanDecorator::unset
     * @covers       \Pars\Bean\Converter\ConverterBeanDecorator::reset
     * @covers       \Pars\Bean\Converter\ConverterBeanDecorator::type
     * @dataProvider dataProvider_CallBeanMethod
     * @param string $method
     * @param array $params
     */
    public function testCallBeanMethod(string $method, array $params, array $additionalMethods = null)
    {
        $mockBean = $this->getMockBuilder(AbstractBaseBean::class)->setMethods([$method])->getMockForAbstractClass();
        $mockBean->set('foo', 'bar');
        $this->object->method('getBean')->willReturn($mockBean);
        if (null !== $additionalMethods) {
            foreach ($additionalMethods as $additionalMethod) {
                $this->object->getBeanConverter()->expects($this->once())->method($additionalMethod);
            }
        }
        $mockBean->expects($this->once())->method($method);
        call_user_func_array([$this->object, $method], $params);
    }
}
