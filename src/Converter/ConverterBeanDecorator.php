<?php

declare(strict_types=1);

namespace Niceshops\Bean\Converter;

use ArrayIterator;
use Niceshops\Bean\Cache\BeanCacheTrait;
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
    use BeanCacheTrait;

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
        $this->clearCache();
        if (!$this->getBeanConverter()->issetRawData($name)) {
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
        if ($this->cache(__METHOD__, $name) === null) {
            if (!$this->getBean()->exists($name) && $this->getBeanConverter()->issetRawData($name)) {
                $this->set($name, $this->getBeanConverter()->getRawData($name));
            }
            $val = $this->getBeanConverter()->convertValueFromBean(
                $this->getBean(),
                $name,
                $this->getBean()->get($name)
            );
            $this->cache(__METHOD__, $name, $val);
        }
        return $this->cache(__METHOD__, $name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function exists($name): bool
    {
        return $this->getBean()->exists($name) || $this->getBeanConverter()->issetRawData($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function unset($name): self
    {
        $this->clearCache();
        $this->getBeanConverter()->unsetRawData($name);
        $this->getBean()->unset($name);
        return $this;
    }

    public function nullify(string $name): self
    {
        $this->clearCache();
        $this->getBeanConverter()->setRawData($name, null);
        $this->getBean()->nullify($name);
        return $this;
    }

    /**
     * @return $this|BeanInterface
     */
    public function reset(): self
    {
        $this->clearCache();
        $this->getBeanConverter()->resetRawData();
        $this->getBean()->reset();
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function type($name): string
    {
        return $this->getBean()->type($name);
    }

    /**
     * @param bool $recursive
     * @return array
     */
    public function toArray(bool $recursive = false): array
    {
        if ($this->cache(__METHOD__, $recursive) === null) {
            $data = [];
            $bean = $this->toBean();
            foreach ($bean as $name => $value) {
                if ($this->exists($name)) {
                    $data[$name] = $this->get($name);
                }
            }
            $this->cache(__METHOD__, $recursive, $data);
        }
        return $this->cache(__METHOD__, $recursive);
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
        if ($this->cache(__METHOD__) === null) {
            foreach ($this->getBeanConverter()->getRawDataMap() as $key => $value) {
                $this->set($key, $value);
            }
            $this->cache(__METHOD__, '', $this->getBean());
        }
        return $this->cache(__METHOD__);
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
        if ($this->cache(__METHOD__, '') === null) {
            $this->cache(__METHOD__, '', count($this->toArray()));
        }
        return $this->cache(__METHOD__, '');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function empty(string $name): bool
    {
        return $this->getBean()->empty($name) && empty($this->getBeanConverter()->getRawData($name));
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray(true);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isset(string $name): bool
    {
        return $this->getBean()->isset($name) || $this->getBeanConverter()->issetRawData($name);
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        if ($this->cache(__METHOD__, '') === null) {
            $this->cache(__METHOD__, '', array_keys($this->toArray()));
        }
        return $this->cache(__METHOD__, '');
    }

    /**
     * @return array
     */
    public function values(): array
    {
        if ($this->cache(__METHOD__, '') === null) {
            $this->cache(__METHOD__, '', array_values($this->toArray()));
        }
        return $this->cache(__METHOD__, '');
    }

}
