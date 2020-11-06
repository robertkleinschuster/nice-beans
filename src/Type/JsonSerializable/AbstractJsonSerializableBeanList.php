<?php

declare(strict_types=1);

namespace Niceshops\Bean\Type\JsonSerializable;

use Niceshops\Bean\Type\Serializable\AbstractSerializableBeanList;

/**
 * Class AbstractJsonSerializableBeanList
 * @package Niceshops\Bean\BeanList\JsonSerializable
 */
class AbstractJsonSerializableBeanList extends AbstractSerializableBeanList implements JsonSerializableBeanListBeanInterface
{
    use JsonSerializableBeanListTrait;
}
