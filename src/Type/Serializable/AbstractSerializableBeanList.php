<?php

declare(strict_types=1);

namespace Niceshops\Bean\Type\Serializable;

use Niceshops\Bean\Type\Base\AbstractBaseBeanList;

/**
 * Class AbstractSerializableBeanList
 * @package Niceshops\Bean\BeanList\Serializable
 */
class AbstractSerializableBeanList extends AbstractBaseBeanList implements SerializableBeanListInterface
{
    use SerializableBeanListTrait;
}
