<?php

declare(strict_types=1);

namespace Niceshops\Bean\Type\Base;

use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Interface BeanInterface
 * @package Niceshops\Library\Core\Bean
 */
interface BeanInterface extends IteratorAggregate, ArrayAccess, Countable, \JsonSerializable
{
    public const DATA_TYPE_BOOL = 'bool';
    public const DATA_TYPE_INT = 'int';
    public const DATA_TYPE_FLOAT = 'float';
    public const DATA_TYPE_STRING = 'string';
    public const DATA_TYPE_ARRAY = 'array';
    public const DATA_TYPE_OBJECT = 'object';
    public const DATA_TYPE_RESOURCE = 'resource';
    public const DATA_TYPE_RESOURCE_CLOSED = 'resource_closed';
    public const DATA_TYPE_NULL = 'null';
    public const DATA_TYPE_UNKNOWN = 'unknown';

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return BeanInterface
     */
    public function set(string $name, $value): self;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exists(string $name): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function isset(string $name): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function empty(string $name): bool;

    /**
     * @param $name
     * @return $this
     */
    public function unset(string $name): self;

    /**
     * @param string $name
     * @return $this
     */
    public function nullify(string $name): self;

    /**
     * @return BeanInterface
     */
    public function reset(): self;

    /**
     * @param $name
     * @return mixed
     */
    public function type(string $name): string;

    /**
     * @param bool $recuresive
     * @return array
     */
    public function toArray(bool $recuresive = false): array;

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function fromArray(array $data): self;

    /**
     * @return mixed
     */
    public function clearCache();

    /**
     * @return array
     */
    public function keys(): array;

    /**
     * @return array
     */
    public function values(): array;
}
