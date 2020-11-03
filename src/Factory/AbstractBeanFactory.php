<?php
declare(strict_types=1);

namespace Niceshops\Bean\Factory;




use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Option\OptionAwareInterface;
use Niceshops\Core\Option\OptionAwareTrait;

/**
 * Class AbstractBeanFactory
 * @package Niceshops\Bean\Factory
 */
abstract class AbstractBeanFactory implements BeanFactoryInterface, OptionAwareInterface, AttributeAwareInterface
{
    use OptionAwareTrait;
    use AttributeAwareTrait;

}
