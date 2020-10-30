<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Converter;

use Niceshops\Bean\Type\Base\AbstractBaseBean;
use Niceshops\Bean\Type\Base\BeanInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Niceshops\Bean
 */
class AbstractBeanConverterTest extends \Niceshops\Core\PHPUnit\DefaultTestCase
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
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(AbstractBeanConverter::class)->disableOriginalConstructor()->getMockForAbstractClass();
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }


    /**
     * @group unit
     * @small
     * @coversDefaultClass  \Niceshops\Bean\Converter\AbstractBeanConverter
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
     * @covers \Niceshops\Bean\Converter\AbstractBeanConverter::convert
     */
    public function testConvert()
    {
        $this->assertInstanceOf(BeanDecorator::class, $this->object->convert($this->createMockBean()));
    }

}
