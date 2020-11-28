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
     * @param array $data
     * @return BeanInterface
     */
    public function getEmptyBean(array $data): BeanInterface;

    /**
     * @return BeanListInterface
     */
    public function getEmptyBeanList(): BeanListInterface;
}
