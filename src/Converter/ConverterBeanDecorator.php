<?php
declare(strict_types=1);

namespace Niceshops\Bean\Converter;

use Niceshops\Bean\Type\Base\BeanAwareInterface;
use Niceshops\Bean\Type\Base\BeanAwareTrait;
use Niceshops\Bean\Type\Base\BeanInterface;

/**
 * Class BeanDecorator
 * @package Niceshops\Bean\Converter
 */
class ConverterBeanDecorator implements BeanAwareInterface, BeanConverterAwareInterface, BeanInterface
{
    use BeanAwareTrait;
    use BeanConverterAwareTrait;


    /**
     * BeanDecorator constructor.
     * @param BeanInterface $bean
     * @param BeanConverterInterface $converter
     */
    public function __construct(BeanInterface $bean, BeanConverterInterface $converter)
    {
        $this->setBean($bean);
        $this->setBeanConverter($converter);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return $this|BeanInterface
     */
    public function setData($name, $value)
    {
        if (!$this->getBeanConverter()->hasRawData($name)) {
            $this->getBeanConverter()->setRawData($name, $value);
        }
        $this->getBean()->setData($name, $this->getBeanConverter()->convertValueToBean($this->getBean(), $name, $value));
        return $this;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getData($name)
    {
        if (!$this->getBean()->hasData($name) && $this->getBeanConverter()->hasRawData($name)) {
            $this->setData($name, $this->getBeanConverter()->getRawData($name));
        }
        return $this->getBeanConverter()->convertValueFromBean($this->getBean(), $name, $this->getBean()->getData($name));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasData($name)
    {
        return $this->getBean()->hasData($name) || $this->getBeanConverter()->hasRawData($name);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function removeData($name)
    {
        $this->getBeanConverter()->removeRawData($name);
        return $this->getBeanConverter()->convertValueFromBean($this->getBean(), $name, $this->getBean()->removeData($name));
    }

    /**
     * @return $this|BeanInterface
     */
    public function resetData()
    {
        $this->getBeanConverter()->resetRawData();
        $this->getBean()->resetData();
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getDataType($name)
    {
        return $this->getBean()->getDataType($name);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];
        $names = $this->toBean()->getOriginalDataName_Map();
        foreach ($names as $name) {
            if ($this->hasData($name)) {
                $data[$name] = $this->getData($name);
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function fromArray(array $data)
    {
        foreach ($data as $key => $value) {
            $this->setData($key, $value);
        }
        return $this;
    }

    /**
     * @return BeanInterface
     */
    public function toBean(): BeanInterface
    {
        foreach ($this->getBeanConverter()->getRawData() as $key => $value) {
            $this->setData($key, $value);
        }
        return $this->getBean();
    }
}
