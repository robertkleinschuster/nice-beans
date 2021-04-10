<?php

declare(strict_types=1);

/**
 * @see       https://github.com/pars/pars-beans for the canonical source repository
 * @license   https://github.com/pars/pars-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Pars\Bean\Type\Base;

use Pars\Bean\Cache\BeanCacheTrait;
use Pars\Bean\Finder\FinderBeanListDecorator;


/**
 * Class AbstractBaseBean
 * @package Pars\Bean
 */
abstract class AbstractBaseBean implements BeanInterface
{

    private const ARRAY_KEY_CLASS = '__class';
    private const ARRAY_KEY_SERIALIZE = '__serialize';

    use BeanCacheTrait;

    /**
     * AbstractBaseBean constructor.
     * @param array $data
     * @throws BeanException
     */
    public function __construct(array $data = [])
    {
        $this->fromArray($data);
    }

    /**
     * @param string $name
     * @param        $value
     *
     * @return $this
     * @throws BeanException
     */
    public function set(string $name, $value): self
    {
        if (!$this->validateDataName($name)) {
            throw new BeanException("Invalid data name $name!", BeanException::ERROR_CODE_INVALID_DATA_NAME);
        }
        $this->{$name} = $value;
        $this->clearCache();
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    private function validateDataName(string $name): bool
    {
        static $valid = [];
        if (!isset($valid[static::class . $name])) {
            $valid[static::class . $name] = !(strpos($name, '*') !== false ||
                strpos($name, '\\') !== false ||
                strpos($name, 'phpunit') !== false);
        }
        return $valid[static::class . $name];
    }


    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->{$name};
    }


    /**
     * @param string $name
     * @return $this
     */
    public function unset(string $name): self
    {
        unset($this->{$name});
        $this->clearCache();
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     * @throws BeanException
     */
    public function nullify(string $name): self
    {
        if ($this->exists($name)) {
            $this->set($name, null);
        }
        return $this;
    }

    /**
     * @param string $name
     * @throws BeanException
     */
    protected function throwDataNotFoundException(string $name)
    {
        throw new BeanException(sprintf("Data '%s' not found!", $name), BeanException::ERROR_CODE_DATA_NOT_FOUND);
    }

    /**
     * @return $this
     */
    public function reset(): self
    {
        foreach ($this as $name => $value) {
            if ($this->validateDataName($name)) {
                $this->unset($name);
            }
        }
        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function exists(string $name): bool
    {
        static $exists = [];
        if (!isset($exists[static::class . $name])) {
            $exists[static::class . $name] = $this->getReflectionObject()->hasProperty($name);
        }
        return $exists[static::class . $name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isset(string $name): bool
    {
        return isset($this->{$name});
    }

    /**
     * @param string $name
     * @return bool
     */
    public function empty(string $name): bool
    {
        return empty($this->{$name});
    }

    /**
     * @param string $name
     * @return bool
     * @throws \ReflectionException
     */
    public function initialized(string $name): bool
    {
        if (null === $this->cache('initialized', $name)) {
            $obj = $this->getReflectionObject();
            $init = $obj->getProperty($name)->isInitialized($this);
            $this->cache('initialized', $name, $init);
        }
        return $this->cache('initialized', $name);
    }


    /**
     *
     * @param bool $recuresive
     * @return array
     */
    public function toArray(bool $recuresive = false): array
    {
        if ($this->cache('toArray', $recuresive) === null) {
            $data = [];
            if ($recuresive) {
                $iterator = $this->getIterator();
                foreach ($iterator as $name => $value) {
                    if ($this->validateDataName($name)) {
                        if ($value instanceof BeanInterface) {
                            $data[$name] = $value->toArray($recuresive);
                        } elseif (is_object($value)) {
                            if ($value instanceof FinderBeanListDecorator) {
                                $value = $value->toBeanList(true);
                            }
                            if ($value instanceof BeanListInterface) {
                                $data[$name] = $value->toArray(true);
                            } else {
                                try {
                                    $data[$name][self::ARRAY_KEY_SERIALIZE] = serialize($value);
                                } catch (\Throwable $exception) {
                                }
                            }
                        } else {
                            $data[$name] = $value;
                        }
                    }
                }
            } else {
                $data = array_filter((array)$this, function ($name) {
                    return $this->validateDataName($name);
                }, ARRAY_FILTER_USE_KEY);
            }
            $data[self::ARRAY_KEY_CLASS] = static::class;
            $this->cache('toArray', $recuresive, $data);
        }
        return $this->cache('toArray', $recuresive);
    }


    /**
     * @param array $data
     * @return $this|mixed
     * @throws BeanException
     */
    public function fromArray(array $data): self
    {
        unset($data[self::ARRAY_KEY_CLASS]);
        foreach ($data as $name => $value) {
            if (isset($value[self::ARRAY_KEY_CLASS])) {
                $class = $value[self::ARRAY_KEY_CLASS];
                $this->set($name, new $class($value));
            } elseif (isset($value[self::ARRAY_KEY_SERIALIZE])) {
                $this->set($name, unserialize($value[self::ARRAY_KEY_SERIALIZE]));
            } else {
                $this->set($name, $value);
            }
        }
        return $this;
    }


    /**
     * @param string $name data name
     *
     * @param string|null $type
     * @return null|string
     * @throws BeanException
     */
    public function type(string $name, ?string $type = null): string
    {
        static $type = [];
        if (!isset($type[static::class . $name])) {
            if ($this->exists($name)) {
                $obj = $this->getReflectionObject();
                $prop = $obj->getProperty($name);
                if ($prop->getType() !== null) {
                    $dataType = $prop->getType()->getName();
                } else {
                    $dataType = self::DATA_TYPE_UNKNOWN;
                }
            } else {
                $dataType = self::DATA_TYPE_UNKNOWN;
            }
            $type[static::class . $name] = $this->normalizeDataType($dataType);
        }
        return $type[static::class . $name];
    }

    protected function getReflectionObject(): \ReflectionObject
    {
        if (null === $this->cache(\ReflectionObject::class)) {
            $this->cache(\ReflectionObject::class, '', new \ReflectionObject($this));
        }
        return $this->cache(\ReflectionObject::class);
    }

    /**
     * @param string $dataType
     *
     * @return string
     */
    protected function normalizeDataType(string $dataType): string
    {
        static $name = [];
        if (!isset($name[$dataType])) {
            switch (strtolower(trim($dataType))) {
                case self::DATA_TYPE_BOOL:
                case "boolean":
                    $result = self::DATA_TYPE_BOOL;
                    break;
                case self::DATA_TYPE_INT:
                case "integer":
                    $result = self::DATA_TYPE_INT;
                    break;
                case self::DATA_TYPE_FLOAT:
                case "double":
                    $result = self::DATA_TYPE_FLOAT;
                    break;
                case self::DATA_TYPE_STRING:
                case "string":
                    $result = self::DATA_TYPE_STRING;
                    break;
                case self::DATA_TYPE_ARRAY:
                case "array":
                    $result = self::DATA_TYPE_ARRAY;
                    break;
                case self::DATA_TYPE_OBJECT:
                case "object":
                    $result = self::DATA_TYPE_OBJECT;
                    break;
                case self::DATA_TYPE_RESOURCE:
                case "resource":
                    $result = self::DATA_TYPE_RESOURCE;
                    break;
                case self::DATA_TYPE_RESOURCE_CLOSED:
                case "resource (closed)":
                    $result = self::DATA_TYPE_RESOURCE_CLOSED;
                    break;
                case self::DATA_TYPE_NULL:
                case "NULL":
                    $result = self::DATA_TYPE_NULL;
                    break;
                case "unknown type":
                    $result = self::DATA_TYPE_UNKNOWN;
                    break;
                default:
                    $result = $dataType;
            }
            $name[$dataType] = $result;
        }
        return $name[$dataType];
    }

    /**
     * @param string $offset
     *
     * @return mixed
     * @throws BeanException
     */
    public function offsetExists($offset): bool
    {
        return $this->exists($offset) && $this->isset($offset);
    }


    /**
     * @param mixed $offset
     *
     * @return mixed
     * @throws BeanException
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return $this
     * @throws BeanException
     */
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetUnset($offset)
    {
        return $this->unset($offset);
    }

    /**
     * @return int|void
     */
    public function count()
    {
        if ($this->cache('count', '') === null) {
            $this->cache('count', '', count($this->toArray()));
        }
        return $this->cache('count', '');
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     *
     * @param array $data
     *
     * @return static
     * @throws BeanException
     */
    public static function createFromArray(array $data): BeanInterface
    {
        try {
            if (isset($data[self::ARRAY_KEY_CLASS])) {
                $class = $data[self::ARRAY_KEY_CLASS];
                return new $class($data);
            } else {
                return new static($data);
            }
        } catch (\Throwable $ex) {
            throw new BeanException('Could not create from array.' . $ex->getMessage(), 1000, $ex);
        }
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray(true);
    }

    /**
     * @return array
     */
    public function keys(): array
    {
        if ($this->cache('keys', '') === null) {
            $this->cache('keys', '', array_keys($this->toArray()));
        }
        return $this->cache('keys', '');
    }

    /**
     * @return array
     */
    public function values(): array
    {
        if ($this->cache('values', '') === null) {
            $this->cache('values', '', array_values($this->toArray()));
        }
        return $this->cache('values', '');
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->toArray(true));
    }

    /**
     * @param string $serialized
     * @throws BeanException
     */
    public function unserialize($serialized)
    {
        $this->fromArray(unserialize($serialized));
    }
}
