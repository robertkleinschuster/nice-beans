<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Factory;

use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Option\OptionAwareInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Niceshops\Bean
 */
class AbstractBeanFactoryTest extends \Niceshops\Core\PHPUnit\DefaultTestCase
{


    /**
     * @var AbstractBeanFactory|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(AbstractBeanFactory::class)->disableOriginalConstructor()->getMockForAbstractClass();
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
        $this->assertTrue(class_exists(AbstractBeanFactory::class), "Class Exists");
        $this->assertTrue(is_a($this->object, AbstractBeanFactory::class), "Mock Object is set");
        $this->assertInstanceOf(BeanFactoryInterface::class, $this->object);
        $this->assertInstanceOf(OptionAwareInterface::class, $this->object);
        $this->assertInstanceOf(AttributeAwareInterface::class, $this->object);
    }
}