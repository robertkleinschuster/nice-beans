<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace ParsTest\Bean\Finder;

use Pars\Bean\Finder\BeanFinderLink;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Pars\Bean
 */
class BeanFinderLinkTest extends \Pars\Pattern\PHPUnit\DefaultTestCase
{


    /**
     * @var BeanFinderLink|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp(): void
    {
        $this->object = $this->getMockBuilder(BeanFinderLink::class)->disableOriginalConstructor()->getMock();
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
        $this->assertTrue(class_exists(BeanFinderLink::class), "Class Exists");
        $this->assertTrue(is_a($this->object, BeanFinderLink::class), "Mock Object is set");
    }
}
