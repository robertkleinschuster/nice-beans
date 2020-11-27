<?php

declare(strict_types=1);

namespace Niceshops\Bean\Converter;

use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Option\OptionAwareInterface;
use Niceshops\Core\Option\OptionAwareTrait;

/**
 * Class AbstractBeanConverter
 * @package Niceshops\Bean\Converter
 */
abstract class AbstractBeanConverter implements BeanConverterInterface, OptionAwareInterface, AttributeAwareInterface
{
    use OptionAwareTrait;
    use AttributeAwareTrait;

    /**
     * @var array
     */
    private array $rawDataMao = [];

    /**
     * @param BeanInterface $bean
     * @param array $rawData
     * @return ConverterBeanDecorator
     */
    public function convert(BeanInterface $bean, array $rawData = []): ConverterBeanDecorator
    {
        $this->rawDataMao = $rawData;
        return new ConverterBeanDecorator($bean, $this);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getRawData(string $name)
    {
        return $this->rawDataMao[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function issetRawData(string $name): bool
    {
        return isset($this->rawDataMao[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    public function emptyRawData(string $name): bool
    {
        return empty($this->rawDataMao[$name]);
    }

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function setRawData(string $name, $value): self
    {
        $this->rawDataMao[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     */
    public function unsetRawData(string $name)
    {
        unset($this->rawDataMao[$name]);
    }

    /**
     *
     */
    public function resetRawData()
    {
        $this->rawDataMao = [];
    }

    /**
     * @return array
     */
    public function getRawDataMap(): array
    {
        return $this->rawDataMao;
    }

}
