<?php

declare(strict_types=1);

namespace Pars\Bean\Converter;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Pattern\Attribute\AttributeAwareInterface;
use Pars\Pattern\Attribute\AttributeAwareTrait;
use Pars\Pattern\Option\OptionAwareInterface;
use Pars\Pattern\Option\OptionAwareTrait;

/**
 * Class AbstractBeanConverter
 * @package Pars\Bean\Converter
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
    public function initializedRawData(string $name): bool
    {
        return array_key_exists($name, $this->resetRawData());
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
