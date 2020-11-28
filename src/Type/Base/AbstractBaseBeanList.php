<?php

declare(strict_types=1);

namespace Niceshops\Bean\Type\Base;

use Ds\Vector;
use Traversable;

/**
 * Class AbstractBeanList
 * @package Niceshops\Library\Core
 */
abstract class AbstractBaseBeanList implements BeanListInterface
{
    /**
     * @var Vector
     */
    private Vector $vector;

    /**
     * AbstractBaseBeanList constructor.
     * @param array|Vector $data
     */
    public function __construct($data = null)
    {
        if ($data instanceof Vector) {
            $this->vector = $data;
        } elseif (is_array($data)) {
            $d = [];
            foreach ($data as $datum) {
                if ($datum instanceof BeanInterface) {
                    $d[] = $datum;
                } elseif (is_array($datum)) {
                    $d[] = AbstractBaseBean::createFromArray($datum);
                }
            }
            $this->vector = new Vector($d);
        } else {
            $this->vector = new Vector();
        }
    }

    /**
     *
     */
    public function clear(): self
    {
        $this->vector->clear();
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->vector->count();
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
        return $this->vector->isEmpty();
    }

    /**
     * @param bool $recursive
     * @return array
     */
    public function toArray(bool $recursive = false): array
    {
        if ($recursive) {
            $data = [];
            foreach ($this as $key => $item) {
                $data[$key] = $item->toArray($recursive);
            }
            return $data;
        } else {
            return $this->vector->toArray();
        }
    }


    /**
     * @return BeanIterator|Traversable
     */
    public function getIterator()
    {
        return new BeanIterator($this->vector->getIterator());
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->vector->offsetExists($offset);
    }

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->vector->offsetGet($offset);
    }

    /**
     * @param int $offset
     * @param mixed $value
     * @return $this
     */
    public function offsetSet($offset, $value): self
    {
        $this->vector->offsetSet($offset, $value);
        return $this;
    }

    /**
     * @param int $offset
     * @return self
     */
    public function offsetUnset($offset): self
    {
        $this->vector->offsetUnset($offset);
        return $this;
    }

    /**
     * @param int $capacity
     */
    public function allocate(int $capacity): self
    {
        $this->vector->allocate($capacity);
        return $this;
    }

    /**
     * @param callable $callback
     */
    public function apply(callable $callback): self
    {
        $this->vector->apply($callback);
        return $this;
    }

    /**
     * @return int
     */
    public function capacity(): int
    {
        return $this->vector->capacity();
    }

    /**
     * @param mixed ...$values
     * @return bool
     */
    public function contains(...$values): bool
    {
        return $this->vector->contains(...$values);
    }

    /**
     * @param callable|null $callback
     * @return self
     */
    public function filter(callable $callback = null): self
    {
        return new static($this->vector->filter($callback));
    }

    /**
     * @param mixed $value
     * @return bool|int|void
     */
    public function find($value)
    {
        return $this->vector->find($value);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return $this->vector->first();
    }

    /**
     * @param int $index
     * @return mixed|void
     */
    public function get(int $index)
    {
        return $this->vector->get($index);
    }

    /**
     * @param int $index
     * @param mixed ...$values
     * @return self
     */
    public function insert(int $index, ...$values): self
    {
        $this->vector->insert($index, ...$values);
        return $this;
    }

    /**
     * @param string|null $glue
     * @return string
     */
    public function join(string $glue = null): string
    {
        return $this->vector->join($glue);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return $this->vector->last();
    }

    /**
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback): self
    {
        return new static($this->vector->map($callback));
    }

    /**
     * @param array|Traversable $values
     * @return self
     */
    public function merge($values): self
    {
        return new static($this->vector->merge($values));
    }

    /**
     * @return BeanInterface
     */
    public function pop()
    {
        return $this->vector->pop();
    }

    /**
     * @param BeanInterface ...$values
     */
    public function push(...$values): self
    {
        $this->vector->push(...$values);
        return $this;
    }

    /**
     * @param callable $callback
     * @param null $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return $this->vector->reduce($callback, $initial);
    }

    /**
     * @param int $index
     * @return self
     */
    public function remove(int $index): self
    {
        $this->vector->remove($index);
        return $this;
    }

    /**
     * @return $this
     */
    public function reverse(): self
    {
        $this->vector->reverse();
        return $this;
    }

    /**
     * @return self
     */
    public function reversed(): self
    {
        return new static($this->vector->reversed());
    }

    /**
     * @param int $rotations
     */
    public function rotate(int $rotations): self
    {
        $this->vector->rotate($rotations);
        return $this;
    }

    /**
     * @param int $index
     * @param mixed $value
     * @return $this
     */
    public function set(int $index, $value): self
    {
        $this->vector->set($index, $value);
        return $this;
    }

    /**
     * @return BeanInterface|null
     */
    public function shift()
    {
        return $this->vector->shift();
    }

    /**
     * @param int $index
     * @param int|null $length
     * @return self
     */
    public function slice(int $index, int $length = null): self
    {
        return new static($this->vector->slice($index, $length));
    }

    /**
     * @param callable|null $comparator
     * @return $this
     */
    public function sort(callable $comparator = null): self
    {
        $this->vector->sort($comparator);
        return $this;
    }

    /**
     * @param callable|null $comparator
     * @return $this
     */
    public function sorted(callable $comparator = null): self
    {
        return new static($this->vector->sorted($comparator));
    }

    /**
     * @deprecated
     * @return float|int|void
     * @throws BeanListException
     */
    public function sum()
    {
        throw new BeanListException('Unsupported operation.');
    }

    /**
     * @param mixed ...$values
     * @return $this
     */
    public function unshift(...$values): self
    {
        $this->vector->unshift(...$values);
        return $this;
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
        $this->vector = clone $this->vector;
    }
}
