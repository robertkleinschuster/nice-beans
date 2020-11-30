<?php


namespace Niceshops\Bean\Processor;


use Niceshops\Bean\Factory\BeanFactoryAwareInterface;
use Niceshops\Bean\Finder\BeanFinderAwareInterface;
use Niceshops\Bean\Finder\BeanFinderAwareTrait;
use Niceshops\Bean\Finder\BeanFinderInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListAwareInterface;

class BeanOrderProcessor implements BeanProcessorAwareInterface, BeanFinderAwareInterface
{
    use BeanProcessorAwareTrait;
    use BeanFinderAwareTrait;

    private string $orderField;
    private ?string $orderReferenceField;

    /**
     * BeanOrderProcessor constructor.
     * @param BeanProcessorInterface $processor
     * @param BeanFinderInterface $finder
     * @param string $orderField
     * @param string|null $orderReferenceField
     */
    public function __construct(
        BeanProcessorInterface $processor,
        BeanFinderInterface $finder,
        string $orderField,
        ?string $orderReferenceField = null
    ) {
        $this->orderField = $orderField;
        $this->orderReferenceField = $orderReferenceField;
        $this->setBeanProcessor($processor);
        $this->setBeanFinder($finder);
    }


    /**
     * @return string
     */
    protected function getOrderField(): string
    {
        return $this->orderField;
    }

    /**
     * @return string|null
     */
    protected function getOrderReferenceField(): ?string
    {
        return $this->orderReferenceField;
    }

    /**
     * @param BeanInterface $bean
     * @param int $steps
     * @param null $orderReferenceValue
     */
    public function move(
        BeanInterface $bean,
        int $steps,
        $orderReferenceValue = null
    ) {
        $finder = $this->getBeanFinder();
        $orderField = $this->getOrderField();
        $orderReferenceField  = $this->getOrderReferenceField();
        $processor = $this->getBeanProcessor();
        if ($bean->exists($orderField)) {
            if ($finder instanceof BeanFactoryAwareInterface) {
                if (!empty($orderReferenceField) && !empty($orderReferenceValue)) {
                    $finder->filter([$orderReferenceField => $orderReferenceValue]);
                }
                $currentOrder = $bean->get($orderField);
                $newOrder = $currentOrder + $steps;
                $reorder_List = [];
                if ($currentOrder < $newOrder) {
                    $finder->order([$orderField => BeanFinderInterface::ORDER_MODE_ASC]);
                    for ($i = $currentOrder + 1; $i <= $newOrder; $i++) {
                        $reorder_List[] = $i;
                    }
                }
                if ($currentOrder > $newOrder) {
                    $finder->order([$orderField => BeanFinderInterface::ORDER_MODE_DESC]);
                    for ($i = $currentOrder - 1; $i >= $newOrder; $i--) {
                        $reorder_List[] = $i;
                    }
                }
                $finder->filter([$orderField => $reorder_List]);

                $beanList = $finder->getBeanList();
                if ($newOrder > 0) {
                    foreach ($beanList as $previousBean) {
                        if ($currentOrder < $newOrder) {
                            $previousBean->set($orderField, $previousBean->get($orderField) - 1);

                        }
                        if ($currentOrder > $newOrder) {
                            $previousBean->set($orderField, $previousBean->get($orderField) + 1);
                        }
                    }
                    $bean->set($orderField, $newOrder);
                    if ($currentOrder < $newOrder) {
                        $beanList->push($bean);
                    }
                    if ($currentOrder > $newOrder) {
                        $beanList->unshift($bean);
                    }
                }
                if ($processor instanceof BeanListAwareInterface) {
                    $processor->setBeanList($beanList);
                }
            }
            $processor->save();
        }
    }
}
