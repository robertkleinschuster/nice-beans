<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Converter;

use Niceshops\Bean\Type\Base\AbstractBaseBean;
use Niceshops\Bean\Type\Base\BeanAwareInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Niceshops\Bean
 * @uses \Niceshops\Bean\Converter\BeanConverterAwareTrait
 * @uses \Niceshops\Bean\Converter\AbstractBeanConverter
 */
class BeanDecoratorTest extends \Niceshops\Core\PHPUnit\DefaultTestCase
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
     * @coversDefaultClass \Niceshops\Bean\Converter\ConverterBeanDecorator
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
        yield 'setData' => ['setData', ['foo', 'bar'], ['convertValueToBean']];
        yield 'getData' => ['getData', ['foo'], ['convertValueFromBean']];
        yield 'hasData' => ['hasData', ['foo']];
        yield 'removeData' => ['removeData', ['foo'], ['convertValueFromBean']];
        yield 'resetData' => ['resetData', []];
        yield 'getDataType' => ['getDataType', ['foo']];
    }

    /**
     * @group unit
     * @small
     * @covers       \Niceshops\Bean\Converter\ConverterBeanDecorator::setData
     * @covers       \Niceshops\Bean\Converter\ConverterBeanDecorator::getData
     * @covers       \Niceshops\Bean\Converter\ConverterBeanDecorator::hasData
     * @covers       \Niceshops\Bean\Converter\ConverterBeanDecorator::removeData
     * @covers       \Niceshops\Bean\Converter\ConverterBeanDecorator::resetData
     * @covers       \Niceshops\Bean\Converter\ConverterBeanDecorator::getDataType
     * @dataProvider dataProvider_CallBeanMethod
     * @param string $method
     * @param array $params
     */
    public function testCallBeanMethod(string $method, array $params, array $additionalMethods = null)
    {
        $mockBean = $this->getMockBuilder(AbstractBaseBean::class)->setMethods([$method])->getMockForAbstractClass();
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
