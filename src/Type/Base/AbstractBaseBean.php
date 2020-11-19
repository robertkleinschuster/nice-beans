<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Type\Base;

use DateTimeInterface;


/**
 * Class AbstractBaseBean
 * @package Niceshops\Bean
 */
abstract class AbstractBaseBean implements BeanInterface
{

    private const ARRAY_KEY_CLASS = '__class';
    private const ARRAY_KEY_SERIALIZE = '__serialize';

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
        if (strpos($name, '__') === 0) {
            throw new BeanException("Invalid data name $name!", BeanException::ERROR_CODE_INVALID_DATA_NAME);
        }
        $this->{$name} = $value;
        return $this;
    }


    /**
     * @param string $name
     *
     * @return mixed
     * @throws BeanException
     */
    public function get(string $name)
    {
        if (!$this->has($name)) {
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
        if ($this->has($name)) {
            unset($this->{$name});
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
     * @throws BeanException
     */
    public function reset(): self
    {
        foreach ($this as $name => $value) {
            $this->unset($name);
        }
        return $this;
    }


    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return property_exists($this, $name);
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
     *
     * @param bool $recuresive
     * @return array
     */
    public function toArray(bool $recuresive = false): array
    {
        if ($recuresive) {
            $data = [];
            foreach ($this as $name => $value) {
                if ($value instanceof BeanInterface) {
                    $data[$name][self::ARRAY_KEY_CLASS] = static::class;
                    $data[$name] = $value->toArray($recuresive);
                } elseif (is_object($value)) {
                    $data[$name][self::ARRAY_KEY_SERIALIZE] = serialize($value);
                } else {
                    $data[$name] = $value;
                }
            }
            return $data;
        } else {
            return (array) $this;
        }
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
     * @return null|string
     * @throws BeanException
     */
    public function getType(string $name): string
    {
        if (!$this->has($name)) {
            $this->throwDataNotFoundException($name);
        }
        $data = $this->get($name);
        $dataType = null;
        if (is_object($data)) {
            if ($data instanceof \Traversable) {
                $dataType = self::DATA_TYPE_TRAVERSABLE;
            } elseif ($data instanceof DateTimeInterface) {
                $dataType = self::DATA_TYPE_DATETIME;
            } elseif ($data instanceof \Closure) {
                $dataType = self::DATA_TYPE_CLOSURE;
            } else {
                $dataType = is_string(get_class($data)) ?: null;
            }
        }
        if ($dataType === null) {
            $dataType = gettype($this->{$name});
        }
        return $this->normalizeDataType($dataType);
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

            case self::DATA_TYPE_DATETIME:
                $dataType = self::DATA_TYPE_DATETIME;
                break;

            case self::DATA_TYPE_TRAVERSABLE:
                $dataType = self::DATA_TYPE_TRAVERSABLE;
                break;

            case self::DATA_TYPE_CLOSURE:
                $dataType = self::DATA_TYPE_CLOSURE;
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
        return $this->has($offset) && null !== $this->get($offset);
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
     * @throws BeanException
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
        return count($this->toArray());
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

    public function jsonSerialize()
    {
        return $this->toArray(true);
    }
}
