<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Bean\Type\Base;

use Ds\Collection;
use Traversable;

/**
 * Interface BeanMapInterface
 * @package Pars\Bean\BeanMap
 */
interface BeanMapInterface extends Collection, \ArrayAccess
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
     * @param array $data
     * @return $this
     */
    public function fromArray(array $data): self;

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
     * @return BeanMapInterface
     */
    public function allocate(int $capacity): self;

    /**
     * @param callable $callback
     * @return BeanMapInterface
     */
    public function apply(callable $callback): self;

    /**
     * @return int
     */
    public function capacity(): int;


    /**
     * @param callable|null $callback
     * @return self
     */
    public function filter(callable $callback = null): self;


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
     * @param bool $recureive
     * @return array|mixed
     */
    public function jsonSerialize(bool $recureive = false);

    /**
     * Return the values from a single column
     * @param string|null $name The column of values to return.
     * This value may be the key of the column you wish to retrieve.
     * It may also be NULL to return complete arrays (useful together with index_key to reindex the array).
     * @param string|null $index_name The column to use as the index/keys for the returned array.
     * @return array Returns an array of values representing a single column.
     */
    public function column(?string $name, ?string $index_name = null): array;

}
