<?php
declare(strict_types=1);

namespace Niceshops\Bean\Factory;

use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListInterface;

/**
 * Interface BeanFactoryInterface
 * @package Niceshops\Bean\BeanFactory
 */
interface BeanFactoryInterface
{
    /**
     * @return BeanInterface
     */
    public function createBean(): BeanInterface;

    /**
     * @return BeanListInterface
     */
    public function createBeanList(): BeanListInterface;
}
