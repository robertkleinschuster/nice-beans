<?php
declare(strict_types=1);

namespace Niceshops\Bean\Factory;


use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListInterface;
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
        return new $class;
    }

    /**
     * @param string $class
     * @return BeanListInterface
     */
    protected function createBeanList(string $class): BeanListInterface
    {
        return new $class;
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
