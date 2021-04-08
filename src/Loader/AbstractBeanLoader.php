<?php

declare(strict_types=1);

namespace Pars\Bean\Loader;

use Pars\Bean\Converter\BeanConverterAwareInterface;
use Pars\Bean\Converter\BeanConverterAwareTrait;
use Pars\Bean\Converter\ConverterBeanDecorator;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Patterns\Attribute\AttributeAwareInterface;
use Pars\Patterns\Attribute\AttributeAwareTrait;
use Pars\Patterns\Option\OptionAwareInterface;
use Pars\Patterns\Option\OptionAwareTrait;

/**
 * Class AbstractBeanLoader
 * @package Pars\Bean\Loader
 */
abstract class AbstractBeanLoader implements
    BeanLoaderInterface,
    BeanConverterAwareInterface,
    OptionAwareInterface,
    AttributeAwareInterface
{
    use BeanConverterAwareTrait;
    use OptionAwareTrait;
    use AttributeAwareTrait;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @var int
     */
    private $key = 0;

    /**
     * @return int
     */
    public function execute(): int
    {
        $this->rewind();
        if ($this->loaded) {
            return count($this->data);
        } else {
            if (count($this->data) > 0) {
                throw new \LogicException('BeanLoader can not be executed twice before it is fully loaded. Iterate over all elements first.');
            }
            $count = $this->init();
            $this->data[$this->key()] = $this->load();
            return $count;
        }
    }

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->rewind();
        $this->loaded = false;
        $this->data = [];
        return $this;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->key];
    }


    public function next()
    {
        $this->key++;
        if (!$this->loaded) {
            $this->data[$this->key()] = $this->load();
        }
    }

    /**
     * @return bool|float|int|string|null
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        $result = isset($this->data[$this->key()]) && null !== $this->data[$this->key()];
        if (!$result) {
            $this->loaded = true;
        }
        return $result;
    }

    /**
     *
     */
    public function rewind()
    {
        $this->key = 0;
    }

    /**
     *
     * @return int
     */
    abstract protected function init(): int;

    /**
     * load next dataset
     *
     * @return array
     */
    abstract protected function load(): ?array;

    /**
     * @param BeanInterface $bean
     * @param array $data
     * @return BeanInterface
     */
    public function initializeBeanWithData(BeanInterface $bean, array $data): BeanInterface
    {
        if ($this->hasBeanConverter()) {
            return $this->getBeanConverter()->convert($bean, $data);
        } else {
            return $bean->fromArray($data);
        }
    }
}
