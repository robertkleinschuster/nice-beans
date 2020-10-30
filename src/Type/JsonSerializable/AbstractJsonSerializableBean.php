<?php
declare(strict_types=1);


namespace Niceshops\Bean\Type\JsonSerializable;



use Niceshops\Bean\Type\Serializable\AbstractSerializableBean;

/**
 * Class AbstractJsonSerializableBean
 * @package Niceshops\Bean\JsonSerializable
 */
class AbstractJsonSerializableBean extends AbstractSerializableBean implements JsonSerializableBeanInterface
{
    use JsonSerializableBeanTrait;
}
