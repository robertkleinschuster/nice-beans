<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Finder;

use Niceshops\Bean\Factory\AbstractBeanFactory;
use Niceshops\Bean\Loader\AbstractBeanLoader;
use Niceshops\Bean\Type\Base\AbstractBaseBean;
use Niceshops\Bean\Type\Base\AbstractBaseBeanList;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class DefaultTestCaseTest
 * @package Niceshops\Bean
 */
class FinderBeanListDecoratorTest extends \Niceshops\Core\PHPUnit\DefaultTestCase
{


    /**
     * @var FinderBeanListDecorator|MockObject
     */
    protected $object;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     */
    protected function setUp()
    {
        $this->object = $this->getMockBuilder(FinderBeanListDecorator::class)->setConstructorArgs([$this->getBeanFinderMock([])])->getMockForAbstractClass();
    }

    public function getBeanFinderMock(array $data)
    {
        $bean = $this->getMockBuilder(AbstractBaseBean::class)->getMockForAbstractClass();
        $beanList = $this->getMockBuilder(AbstractBaseBeanList::class)->getMockForAbstractClass();
        $factory = $this->getMockBuilder(AbstractBeanFactory::class)->setMethods(['createBean', 'createBeanList'])->disableOriginalConstructor()->getMockForAbstractClass();
        $factory->method('createBean')->willReturn($bean);
        $factory->method('createBeanList')->willReturn($beanList);
        $loader = $this->getMockBuilder(AbstractBeanLoader::class)->setMethods(['load', 'init'])->disableOriginalConstructor()->getMockForAbstractClass();
        $loader->method('init')->willReturn(3);
        $loader->method('load')->willReturnOnConsecutiveCalls(['foo' => 'bar'], ['foo' => 'baz'], ['foo' => 'bam'], null);

        $finder = $this->getMockBuilder(AbstractBeanFinder::class)->setMethods(['getBeanLoader', 'getBeanFactory'])->disableOriginalConstructor()->getMockForAbstractClass();
        $finder->method('getBeanFactory')->willReturn($factory);
        $finder->method('getBeanLoader')->willReturn($loader);
        return $finder;
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
        $this->assertTrue(class_exists(FinderBeanListDecorator::class), "Class Exists");
        $this->assertTrue(is_a($this->object, FinderBeanListDecorator::class), "Mock Object is set");
    }

    /**
     * @group unit
     * @small
     * @covers \Niceshops\Bean\Finder\FinderBeanListDecorator::getIterator
     */
    public function testIterator()
    {
        $this->assertCount(3, $this->object->toBeanList());
    }
}
