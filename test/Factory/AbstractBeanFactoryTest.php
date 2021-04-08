<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Bean\Factory;

use Pars\Patterns\Attribute\AttributeAwareInterface;
use Pars\Patterns\Option\OptionAwareInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Pars\Bean
 */
class AbstractBeanFactoryTest extends \Pars\Patterns\PHPUnit\DefaultTestCase
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
