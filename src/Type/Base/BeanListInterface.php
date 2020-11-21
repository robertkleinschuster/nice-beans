<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Type\Base;

use Ds\Sequence;
use Traversable;

/**
 * Interface BeanListInterface
 * @package Niceshops\Bean\BeanList
 */
interface BeanListInterface extends Sequence
{
    /**
     *
     */
    public function clear(): self;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param bool $recurive
     * @return $this
     */
    public function copy(bool $recurive = false): self;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @param bool $recursive
     * @return array
     */
    public function toArray(bool $recursive = false): array;

    /**
     * @return BeanIterator|Traversable
     */
    public function getIterator();

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool;

    /**
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset);

    /**
     * @param int $offset
     * @param mixed $value
     * @return self
     */
    public function offsetSet($offset, $value): self;

    /**
     * @param int $offset
     * @return self
     */
    public function offsetUnset($offset): self;

    /**
     * @param int $capacity
     * @return BeanListInterface
     */
    public function allocate(int $capacity): self;

    /**
     * @param callable $callback
     * @return BeanListInterface
     */
    public function apply(callable $callback): self;

    /**
     * @return int
     */
    public function capacity(): int;

    /**
     * @param mixed ...$values
     * @return bool
     */
    public function contains(...$values): bool;

    /**
     * @param callable|null $callback
     * @return self
     */
    public function filter(callable $callback = null): self;

    /**
     * @param mixed $value
     * @return bool|int|void
     */
    public function find($value);

    /**
     * @return mixed
     */
    public function first();

    /**
     * @param int $index
     * @return mixed|void
     */
    public function get(int $index);

    /**
     * @param int $index
     * @param mixed ...$values
     * @return self
     */
    public function insert(int $index, ...$values): self;

    /**
     * @param string|null $glue
     * @return string
     */
    public function join(string $glue = null): string;

    /**
     * @return mixed
     */
    public function last();

    /**
     * @param callable $callback
     * @return self
     */
    public function map(callable $callback): self;

    /**
     * @param array|Traversable $values
     * @return self
     */
    public function merge($values): self;

    /**
     * @return BeanInterface
     */
    public function pop();

    /**
     * @param BeanInterface ...$values
     */
    public function push(...$values): self;

    /**
     * @param callable $callback
     * @param null $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null);

    /**
     * @param int $index
     * @return self
     */
    public function remove(int $index): self;

    /**
     * @return $this
     */
    public function reverse(): self;

    /**
     * @return self
     */
    public function reversed(): self;

    /**
     * @param int $rotations
     * @return self
     */
    public function rotate(int $rotations): self;

    /**
     * @param int $index
     * @param mixed $value
     * @return self
     */
    public function set(int $index, $value): self;

    /**
     * @return BeanInterface|null
     */
    public function shift();

    /**
     * @param int $index
     * @param int|null $length
     * @return self
     */
    public function slice(int $index, int $length = null): self;

    /**
     * @param callable|null $comparator
     * @return $this
     */
    public function sort(callable $comparator = null): self;

    /**
     * @param callable|null $comparator
     * @return $this
     */
    public function sorted(callable $comparator = null): self;

    /**
     * @return float|int|void
     * @throws BeanListException
     * @deprecated
     */
    public function sum();

    /**
     * @param mixed ...$values
     * @return $this
     */
    public function unshift(...$values): self;

    /**
     * @param bool $recureive
     * @return array|mixed
     */
    public function jsonSerialize(bool $recureive = false);
}
