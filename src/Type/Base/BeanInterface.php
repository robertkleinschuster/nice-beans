<?php

declare(strict_types=1);

/**
 * @see       https://github.com/niceshops/nice-beans for the canonical source repository
 * @license   https://github.com/niceshops/nice-beans/blob/master/LICENSE BSD 3-Clause License
 */

namespace Niceshops\Bean\Type\Base;

/**
 * Interface BeanInterface
 * @package Niceshops\Library\Core\Bean
 */
interface BeanInterface
{
    public const DATA_TYPE_CALLABLE = 'callable';
    public const DATA_TYPE_STRING = 'string';
    public const DATA_TYPE_ARRAY = 'array';
    public const DATA_TYPE_INT = 'int';
    public const DATA_TYPE_FLOAT = 'float';
    public const DATA_TYPE_BOOL = 'bool';
    public const DATA_TYPE_ITERABLE = 'iterable';
    public const DATA_TYPE_DATE = 'date';
    public const DATA_TYPE_DATETIME_PHP = 'datetime';
    public const DATA_TYPE_OBJECT = 'object';
    public const DATA_TYPE_RESOURCE = 'resource';
    public const DATA_KEY_WILDCARD = "*";


    /**
     * @param string $name
     * @param mixed $value
     *
     * @return BeanInterface
     */
    public function setData($name, $value);


    /**
     * @param string $name
     *
     * @return mixed
     */
    public function getData($name);


    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasData($name);


    /**
     * @param string $name
     *
     * @return mixed    the removed data or NULL if data couldn't be found
     */
    public function removeData($name);


    /**
     * @return BeanInterface
     */
    public function resetData();

    /**
     * @param $name
     * @return mixed
     */
    public function getDataType($name);

    /**
     * @return array
     */
    public function toArray(): array;


    /**
     * @param array $data
     *
     * @return mixed
     */
    public function fromArray(array $data);
}
