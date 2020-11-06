<?php

declare(strict_types=1);

namespace Niceshops\Bean\Type\Serializable;

use Niceshops\Bean\Type\Base\AbstractBaseBean;

/**
 * Class AbstractSerializableBean
 * @package Niceshops\Bean\Serializable
 */
class AbstractSerializableBean extends AbstractBaseBean implements SerializeableBeanInterface
{
    use SerializableBeanTrait;
}
