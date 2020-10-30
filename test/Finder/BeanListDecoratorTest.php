<?php
declare(strict_types=1);
/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Finder;

use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Niceshops\Bean
 */
class BeanListDecoratorTest extends \Niceshops\Core\PHPUnit\DefaultTestCase
{


    /**
     * @var BeanListDecorator|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(BeanListDecorator::class)->disableOriginalConstructor()->getMock();
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
        $this->assertTrue(class_exists(BeanListDecorator::class), "Class Exists");
        $this->assertTrue(is_a($this->object, BeanListDecorator::class), "Mock Object is set");
    }
}
