<?php

declare(strict_types=1);

namespace Pars\Bean\Type\Base;

use Ds\Map;
use Traversable;

/**
 * Class AbstractBeanList
 * @package Pars\Library\Patterns
 */
abstract class AbstractBaseBeanMap implements BeanMapInterface
{
    private const ARRAY_KEY_CLASS = '__class';

    /**
     * @var Map
     */
    private Map $map;

    /**
     * AbstractBaseBeanList constructor.
     * @param array|Map $data
     */
    public function __construct($data = null)
    {
        if ($data instanceof Map) {
            $this->map = $data;
        } elseif (is_array($data)) {
            $this->fromArray($data);
        } else {
            $this->map = new Map();
        }
    }

    /**
     *
     */
    public function clear(): self
    {
        $this->map->clear();
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->map->count();
    }

    /**
     * @param bool $recurive
     * @return $this
     */
    public function copy(bool $recurive = false): self
    {
        return new static($this->toArray($recurive));
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->map->isEmpty();
    }

    /**
     * @param bool $recursive
     * @return array
     */
    public function toArray(bool $recursive = false): array
    {
        if ($recursive) {
            $data = [];
            $data[self::ARRAY_KEY_CLASS] = static::class;
            foreach ($this as $key => $item) {
                $data[$key] = $item->toArray($recursive);
            }
            return $data;
        } else {
            $data = $this->map->toArray();
            $data[self::ARRAY_KEY_CLASS] = static::class;
            return $data;
        }
    }

    /**
     * @param string $key
     * @param BeanInterface $value
     * @return $this
     */
    public function put(string $key, BeanInterface $value): self
    {
        $this->map->put($key, $value);
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return $this->map->hasKey($key);
    }

    /**
     * @param BeanInterface $value
     * @return bool
     */
    public function hasValue(BeanInterface $value): bool
    {
        return $this->map->hasValue($value);
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        return $this->map->keys()->toArray();
    }

    /**
     * @param array $data
     * @return $this
     * @throws BeanException
     */
    public function fromArray(array $data): self
    {
        $d = [];
        unset($data[self::ARRAY_KEY_CLASS]);
        foreach ($data as $key => $datum) {
            if ($datum instanceof BeanInterface) {
                $d[$key] = $datum;
            } elseif (is_array($datum) && isset($datum[self::ARRAY_KEY_CLASS])) {
                $d[$key] = AbstractBaseBean::createFromArray($datum);
            }
        }
        $this->map = new Map($d);
        return $this;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public static function createFromArray(array $data): self
    {
        if (isset($data[self::ARRAY_KEY_CLASS])) {
            $class = $data[self::ARRAY_KEY_CLASS];
        } else {
            $class = static::class;
        }
        return new $class($data);
    }

    /**
     * @return BeanIterator|Traversable
     */
    public function getIterator()
    {
        return $this->map->getIterator(); # new BeanIterator();
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->map->offsetExists($offset);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->map->offsetGet($offset);
    }

    /**
     * @param int $offset
     * @param mixed $value
     * @return $this
     */
    public function offsetSet($offset, $value): self
    {
        $this->map->offsetSet($offset, $value);
        return $this;
    }

    /**
     * @param int $offset
     * @return self
     */
    public function offsetUnset($offset): self
    {
        $this->map->offsetUnset($offset);
        return $this;
    }

    /**
     * @param int $capacity
     */
    public function allocate(int $capacity): self
    {
        $this->map->allocate($capacity);
        return $this;
    }

    /**
     * @param callable $callback
     */
    public function apply(callable $callback): self
    {
        $this->map->apply($callback);
        return $this;
    }

    /**
     * @return int
     */
    public function capacity(): int
    {
        return $this->map->capacity();
    }

    /**
     * @param callable|null $callback
     * @return self
     */
    public function filter(callable $callback = null): self
    {
        return new static($this->map->filter($callback));
    }


    /**
     * @return mixed
     */
    public function first()
    {
        return $this->map->first();
    }

    /**
     * @param int $key
     * @return mixed|void
     */
    public function get(int $key)
    {
        return $this->map->get($key);
    }


    /**
     * @return mixed
     */
    public function last()
    {
        return $this->map->last();
    }

    /**
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback): self
    {
        return new static($this->map->map($callback));
    }

    /**
     * @param array|Traversable $values
     * @return self
     */
    public function merge($values): self
    {
        return new static($this->map->merge($values));
    }
    /**
     * @param callable $callback
     * @param null $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return $this->map->reduce($callback, $initial);
    }

    /**
     * @param int $key
     * @return self
     */
    public function remove(int $key): self
    {
        $this->map->remove($key);
        return $this;
    }

    /**
     * @return $this
     */
    public function reverse(): self
    {
        $this->map->reverse();
        return $this;
    }

    /**
     * @return self
     */
    public function reversed(): self
    {
        return new static($this->map->reversed());
    }


    /**
     * @param int $offset
     * @param int|null $length
     * @return self
     */
    public function slice(int $offset, int $length = null): self
    {
        return new static($this->map->slice($offset, $length));
    }

    /**
     * @param callable|null $comparator
     * @return $this
     */
    public function sort(callable $comparator = null): self
    {
        $this->map->sort($comparator);
        return $this;
    }

    /**
     * @param callable|null $comparator
     * @return $this
     */
    public function sorted(callable $comparator = null): self
    {
        return new static($this->map->sorted($comparator));
    }

    /**
     * @param bool $recureive
     * @return array|mixed
     */
    public function jsonSerialize(bool $recureive = false)
    {
        return $this->toArray($recureive);
    }

    /**
     * @param string|null $name
     * @param string|null $index_name
     * @return array
     */
    public function column(?string $name, ?string $index_name = null): array
    {
        return array_column($this->toArray(), $name, $index_name);
    }

    public function __clone()
    {
        $this->map = clone $this->map;
    }



}
