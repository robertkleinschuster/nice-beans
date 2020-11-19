<?php

declare(strict_types=1);

namespace Niceshops\Bean\Converter;

use ArrayIterator;
use Niceshops\Bean\Type\Base\BeanAwareInterface;
use Niceshops\Bean\Type\Base\BeanAwareTrait;
use Niceshops\Bean\Type\Base\BeanInterface;

/**
 * Class BeanDecorator
 * @package Niceshops\Bean\Converter
 */
class ConverterBeanDecorator implements
    BeanAwareInterface,
    BeanConverterAwareInterface,
    BeanInterface
{
    use BeanAwareTrait;
    use BeanConverterAwareTrait;

    /**
     * BeanDecorator constructor.
     * @param BeanInterface $bean
     * @param BeanConverterInterface $converter
     */
    public function __construct(BeanInterface $bean, BeanConverterInterface $converter)
    {
        $this->setBean($bean);
        $this->setBeanConverter($converter);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this|BeanInterface
     */
    public function set($name, $value): self
    {
        if (!$this->getBeanConverter()->hasRawData($name)) {
            $this->getBeanConverter()->setRawData($name, $value);
        }
        $this->getBean()->set(
            $name,
            $this->getBeanConverter()->convertValueToBean(
                $this->getBean(),
                $name,
                $value
            )
        );
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        if (!$this->getBean()->exists($name) && $this->getBeanConverter()->hasRawData($name)) {
            $this->set($name, $this->getBeanConverter()->getRawData($name));
        }
        return $this->getBeanConverter()->convertValueFromBean(
            $this->getBean(),
            $name,
            $this->getBean()->get($name)
        );
    }

    /**
     * @param string $name
     * @return bool
     */
    public function exists($name): bool
    {
        return $this->getBean()->exists($name) || $this->getBeanConverter()->hasRawData($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function unset($name): self
    {
        $this->getBeanConverter()->removeRawData($name);
        $this->getBean()->unset($name);
        return $this;
    }

    /**
     * @return $this|BeanInterface
     */
    public function reset(): self
    {
        $this->getBeanConverter()->resetRawData();
        $this->getBean()->reset();
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getType($name): string
    {
        return $this->getBean()->getType($name);
    }

    /**
     * @param bool $recursive
     * @return array
     */
    public function toArray(bool $recursive = false): array
    {
        $data = [];
        $bean = $this->toBean();
        foreach ($bean as $name => $value) {
            if ($this->exists($name)) {
                $data[$name] = $this->get($name);
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function fromArray(array $data): self
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }

    /**
     * @return BeanInterface
     */
    public function toBean(): BeanInterface
    {
        foreach ($this->getBeanConverter()->getRawDataMap() as $key => $value) {
            $this->set($key, $value);
        }
        return $this->getBean();
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset) && null !== $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return $this|BeanInterface|void
     */
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetUnset($offset)
    {
        return $this->unset($offset);
    }

    /**
     * @return ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * @return int|void
     */
    public function count()
    {
        return count($this->toArray());
    }

    /**
     * @param string $name
     * @return bool
     */
    public function empty(string $name): bool
    {
        return $this->getBean()->empty($name) && empty($this->getBeanConverter()->getRawData($name));
    }

    public function jsonSerialize()
    {
        return $this->toArray(true);
    }


}
