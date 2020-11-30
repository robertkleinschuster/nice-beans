<?php


namespace Niceshops\Bean\Processor;


use Niceshops\Bean\Finder\BeanFinderAwareInterface;
use Niceshops\Bean\Finder\BeanFinderAwareTrait;
use Niceshops\Bean\Finder\BeanFinderInterface;
use Niceshops\Bean\Type\Base\BeanInterface;

class OrderMetaFieldHandlerInterface implements MetaFieldHandlerInterface, BeanFinderAwareInterface
{
    use BeanFinderAwareTrait;

    /**
     * @var string
     */
    protected string $orderField;

    /**
     * @var string|null
     */
    protected ?string $orderReferenceField;

    /**
     * OrderMetaFieldHandlerInterface constructor.
     * @param BeanFinderInterface $beanFinder
     * @param string $orderField
     * @param string $orderReferenceField
     */
    public function __construct(BeanFinderInterface $beanFinder, string $orderField, string $orderReferenceField = null)
    {
        $this->setBeanFinder($beanFinder);
        $this->orderField = $orderField;
        $this->orderReferenceField = $orderReferenceField;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        $code = 'order_meta_field_' . $this->orderField;
        if (isset($this->orderReferenceField)) {
            $code .= '_' . $this->orderReferenceField;
        }
        return $code;
    }


    /**
     * @return string
     */
    protected function getOrderField(): string
    {
        return $this->orderField;
    }

    /**
     * @return string
     */
    protected function getOrderReferenceField(): string
    {
        return $this->orderReferenceField;
    }

    /**
    * @return bool
    */
    public function hasOrderReferenceField(): bool
    {
        return isset($this->orderReferenceField);
    }


    /**
     * @param BeanInterface $bean
     * @return BeanInterface
     */
    public function handle(BeanInterface $bean): BeanInterface
    {
        if ($bean->empty($this->getOrderField())) {
            if ($this->hasOrderReferenceField() && !$bean->empty($this->getOrderReferenceField())) {
                $this->getBeanFinder()->filter(
                    [$this->getOrderReferenceField() => $bean->get($this->getOrderReferenceField())]
                );
            }
            $this->getBeanFinder()->order([$this->getOrderField() => BeanFinderInterface::ORDER_MODE_DESC]);
            $this->getBeanFinder()->limit(1, 0);
            if ($this->getBeanFinder()->count()) {
                $lastOrder = $this->getBeanFinder()->getBean()->get($this->getOrderField());
                $bean->set($this->getOrderField(), $lastOrder + 1);
            } else {
                $bean->set($this->getOrderField(), 1);
            }
        }
        return $bean;
    }

}
