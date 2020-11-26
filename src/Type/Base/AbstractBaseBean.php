<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Type\Base;

use DateTimeInterface;
use Niceshops\Bean\Cache\BeanCacheTrait;


/**
 * Class AbstractBaseBean
 * @package Niceshops\Bean
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
        return strpos($name, 'phpunit') === false
            && strpos($name, '*') === false
            && strpos($name, '\\') === false;
    }



    /**
     * @param string $name
     *
     * @return mixed
     * @throws BeanException
     */
    public function get(string $name)
    {
        if (!$this->exists($name)) {
            $this->throwDataNotFoundException($name);
        }
        return $this->{$name};
    }


    /**
     * @param string $name
     * @return $this
     */
    public function unset(string $name): self
    {
        if ($this->exists($name)) {
            unset($this->{$name});
            $this->clearCache();
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
        if (null === $this->cache(__METHOD__, $name)) {
            $ret = property_exists($this, $name) && $this->validateDataName($name);
            $this->cache(__METHOD__, $name, $ret);
        }
        return $this->cache(__METHOD__, $name);
    }

    /**
     * @param string $name
     * @return bool
     * @throws BeanException
     */
    public function isset(string $name): bool
    {
        if (!$this->exists($name)) {
            $this->throwDataNotFoundException($name);
        }
        return isset($this->{$name});
    }

    /**
     * @param string $name
     * @return bool
     * @throws BeanException
     */
    public function empty(string $name): bool
    {
        if (!$this->exists($name)) {
            $this->throwDataNotFoundException($name);
        }
        if ($this->cache(__METHOD__, $name) === null) {
            $this->cache(__METHOD__, $name, empty($this->{$name}));
        }
        return $this->cache(__METHOD__, $name);
    }

    /**
     *
     * @param bool $recuresive
     * @return array
     */
    public function toArray(bool $recuresive = false): array
    {
        if ($this->cache(__METHOD__, $recuresive) === null) {
            $data = [];
            if ($recuresive) {
                foreach ($this as $name => $value) {
                    if ($this->validateDataName($name)) {
                        if ($value instanceof BeanInterface) {
                            $data[$name][self::ARRAY_KEY_CLASS] = static::class;
                            $data[$name] = $value->toArray($recuresive);
                        } elseif (is_object($value)) {
                            $data[$name][self::ARRAY_KEY_SERIALIZE] = serialize($value);
                        } else {
                            $data[$name] = $value;
                        }
                    }
                }
            } else {
                $data = array_filter((array) $this, function ($name) {
                    return $this->validateDataName($name);
                }, ARRAY_FILTER_USE_KEY);
            }
            $this->cache(__METHOD__, $recuresive, $data);
        }
        return $this->cache(__METHOD__, $recuresive);
    }


    /**
     * @param array $data
     * @return $this|mixed
     * @throws BeanException
     */
    public function fromArray(array $data): self
    {
        foreach ($data as $name => $value) {
            if (isset($value[self::ARRAY_KEY_CLASS])) {
                $class = $value[self::ARRAY_KEY_CLASS];
                $this->set($name, new $class($data));
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
        if ($this->cache(__METHOD__, $name) === null) {
            if (!$this->exists($name)) {
                $this->throwDataNotFoundException($name);
            }
            if (null === $this->cache(\ReflectionObject::class)) {
                $this->cache(\ReflectionObject::class, '', new \ReflectionObject($this));
            }
            $obj = $this->cache(\ReflectionObject::class);
            $prop = $obj->getProperty($name);
            if ($prop->getType() !== null) {
                $dataType = $prop->getType()->getName();
            } else {
                $dataType = self::DATA_TYPE_UNKNOWN;
            }
            $this->cache(__METHOD__, $name, $this->normalizeDataType($dataType));
        }
        return $this->cache(__METHOD__, $name);
    }


    /**
     * @param string $dataType
     *
     * @return string
     */
    protected function normalizeDataType(string $dataType): string
    {
        $dataType = trim($dataType);
        switch (strtolower($dataType)) {
            case self::DATA_TYPE_BOOL:
            case "boolean":
                $dataType = self::DATA_TYPE_BOOL;
                break;

            case self::DATA_TYPE_INT:
            case "integer":
                $dataType = self::DATA_TYPE_INT;
                break;

            case self::DATA_TYPE_FLOAT:
            case "double":
                $dataType = self::DATA_TYPE_FLOAT;
                break;

            case self::DATA_TYPE_STRING:
            case "string":
                $dataType = self::DATA_TYPE_STRING;
                break;

            case self::DATA_TYPE_ARRAY:
            case "array":
                $dataType = self::DATA_TYPE_ARRAY;
                break;

            case self::DATA_TYPE_OBJECT:
            case "object":
                $dataType = self::DATA_TYPE_OBJECT;
                break;

            case self::DATA_TYPE_RESOURCE:
            case "resource":
                $dataType = self::DATA_TYPE_RESOURCE;
                break;

            case self::DATA_TYPE_RESOURCE_CLOSED:
            case "resource (closed)":
                $dataType = self::DATA_TYPE_RESOURCE_CLOSED;
                break;

            case self::DATA_TYPE_NULL:
            case "NULL":
                $dataType = self::DATA_TYPE_NULL;
                break;

            case "unknown type":
                $dataType = self::DATA_TYPE_UNKNOWN;
                break;
        }

        return $dataType;
    }


    /**
     * @param string $offset
     *
     * @return mixed
     * @throws BeanException
     */
    public function offsetExists($offset): bool
    {
        return $this->exists($offset) && null !== $this->get($offset);
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
        if ($this->cache(__METHOD__, '') === null) {
            $this->cache(__METHOD__, '', count($this->toArray()));
        }
        return $this->cache(__METHOD__, '');
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
            if (isset($arrData[self::ARRAY_KEY_CLASS])) {
                $class = $arrData[self::ARRAY_KEY_CLASS];
                return new $class($data);
            } else {
                return new static($data);
            }
        } catch (\Throwable $ex) {
            throw new BeanException('Could not create from array.', 1000, $ex);
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
