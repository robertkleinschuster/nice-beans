<?php


namespace Niceshops\Bean\Finder;


use Iterator;
use Niceshops\Bean\Converter\BeanConverterAwareInterface;
use Niceshops\Bean\Converter\BeanConverterAwareTrait;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Option\OptionAwareInterface;
use Niceshops\Core\Option\OptionAwareTrait;

abstract class AbstractBeanLoader implements BeanLoaderInterface, Iterator, OptionAwareInterface, AttributeAwareInterface, BeanConverterAwareInterface
{
    use OptionAwareTrait;
    use AttributeAwareTrait;
    use BeanConverterAwareTrait;

    private $data;

    /**
     * @var bool
     */
    private $loaded;

    /**
     * @var int
     */
    private $key;

    /**
     * @return int
     */
    public function execute(): int
    {
        $this->rewind();
        if ($this->loaded) {
            return count($this->data);
        } else {
            return $this->init();
        }
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->key];
    }

    public function next()
    {
        $this->key++;
        if (!$this->loaded) {
            $this->data[$this->key()] = $this->load();
        }
    }

    /**
     * @return bool|float|int|string|null
     */
    public function key()
    {
       return $this->key;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        $result = isset($this->data[$this->key()]) && null !== $this->data[$this->key()];
        if (!$result) {
            $this->loaded = true;
        }
        return $result;
    }

    /**
     *
     */
    public function rewind()
    {
        $this->key = 0;
    }

    /**
     *
     * @return int
     */
    abstract protected function init(): int;

    /**
     * load next dataset
     *
     * @return array
     */
    abstract protected function load(): ?array;

    /**
     * @param BeanInterface $bean
     * @param array $data
     * @return BeanInterface
     */
    public function initializeBeanWithData(BeanInterface $bean, array $data): BeanInterface
    {
        if ($this->hasBeanConverter()) {
            return $this->getBeanConverter()->convert($bean)->fromArray($data);
        } else {
            return $bean->fromArray($data);
        }
    }


}
