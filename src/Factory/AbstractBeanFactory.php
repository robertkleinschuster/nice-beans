<?php

declare(strict_types=1);

namespace Pars\Bean\Factory;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Bean\Type\Base\BeanListInterface;
use Pars\Patterns\Attribute\AttributeAwareInterface;
use Pars\Patterns\Attribute\AttributeAwareTrait;
use Pars\Patterns\Option\OptionAwareInterface;
use Pars\Patterns\Option\OptionAwareTrait;

/**
 * Class AbstractBeanFactory
 * @package Pars\Bean\Factory
 */
abstract class AbstractBeanFactory implements BeanFactoryInterface, OptionAwareInterface, AttributeAwareInterface
{
    use OptionAwareTrait;
    use AttributeAwareTrait;

    /**
     * @var BeanInterface
     */
    private $emptyBean = null;

    /**
     * @var BeanListInterface
     */
    private $emptyBeanList = null;

    /**
     * @param array $data
     * @return BeanInterface
     */
    public function getEmptyBean(array $data): BeanInterface
    {
        if (null === $this->emptyBean) {
            $this->emptyBean = $this->createBean($this->getBeanClass($data));
        }
        return clone $this->emptyBean;
    }

    /**
     * @return BeanListInterface
     */
    public function getEmptyBeanList(): BeanListInterface
    {
        if (null === $this->emptyBeanList) {
            $this->emptyBeanList = $this->createBeanList($this->getBeanListClass());
        }
        return clone $this->emptyBeanList;
    }

    /**
     * @param string $class
     * @return BeanInterface
     */
    protected function createBean(string $class): BeanInterface
    {
        return new $class();
    }

    /**
     * @param string $class
     * @return BeanListInterface
     */
    protected function createBeanList(string $class): BeanListInterface
    {
        return new $class();
    }

    /**
     * @param array $data
     * @return string
     */
    abstract protected function getBeanClass(array $data): string;

    /**
     * @return string
     */
    abstract protected function getBeanListClass(): string;
}
