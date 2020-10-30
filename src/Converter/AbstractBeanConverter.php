<?php
declare(strict_types=1);

namespace Niceshops\Bean\Converter;


use Niceshops\Bean\Type\Base\BeanInterface;

abstract class AbstractBeanConverter implements BeanConverterInterface
{

    /**
     * @var array
     */
    private $rawData = [];

    /**
     * @param BeanInterface $bean
     * @param array $rawData
     * @return ConverterBeanDecorator
     */
    public function convert(BeanInterface $bean, array $rawData = []): ConverterBeanDecorator
    {
        $this->rawData = $rawData;
        return new ConverterBeanDecorator($bean, $this);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getRawData(string $name)
    {
        return $this->rawData[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasRawData(string $name): bool
    {
        return isset($this->rawData[$name]);
    }

    /**
     * @param string $name
     * @param $value
     * @return $this
     */
    public function setRawData(string $name, $value): self
    {
        $this->rawData[$name] = $value;
        return $this;
    }

    /**
     * @param string $name
     */
    public function removeRawData(string $name)
    {
        unset($this->rawData[$name]);
    }

    /**
     *
     */
    public function resetRawData()
    {
        $this->rawData = [];
    }
}
