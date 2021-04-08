<?php


namespace Pars\Bean\Processor;


use Pars\Bean\Finder\BeanFinderAwareInterface;
use Pars\Bean\Finder\BeanFinderAwareTrait;
use Pars\Bean\Finder\BeanFinderInterface;
use Pars\Bean\Type\Base\BeanInterface;

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
        $finder = clone $this->getBeanFinder();
        $finder->reset();
        if ($bean->empty($this->getOrderField())) {
            if ($this->hasOrderReferenceField()) {
                if ($bean->isset($this->getOrderReferenceField())) {
                    $finder->filter(
                        [$this->getOrderReferenceField() => $bean->get($this->getOrderReferenceField())]
                    );
                } else {
                    $finder->filter(
                        [$this->getOrderReferenceField() => null]
                    );
                }
            }
            $finder->order([$this->getOrderField() => BeanFinderInterface::ORDER_MODE_DESC]);
            $finder->limit(1, 0);
            if ($finder->count()) {
                $lastOrder = $finder->getBean()->get($this->getOrderField());
                $bean->set($this->getOrderField(), $lastOrder + 1);
            } else {
                $bean->set($this->getOrderField(), 1);
            }
        }
        return $bean;
    }

}
