<?php

declare(strict_types=1);

namespace Niceshops\Bean\Processor;

use Countable;
use Niceshops\Bean\Factory\BeanFactoryAwareInterface;
use Niceshops\Bean\Finder\BeanFinderInterface;
use Niceshops\Bean\Saver\BeanSaverAwareInterface;
use Niceshops\Bean\Saver\BeanSaverAwareTrait;
use Niceshops\Bean\Saver\BeanSaverInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListAwareInterface;
use Niceshops\Bean\Type\Base\BeanListAwareTrait;
use Niceshops\Bean\Type\Base\BeanListInterface;
use Niceshops\Core\Attribute\AttributeAwareInterface;
use Niceshops\Core\Attribute\AttributeAwareTrait;
use Niceshops\Core\Option\OptionAwareInterface;
use Niceshops\Core\Option\OptionAwareTrait;

/**
 * Class AbstractBeanProcessor
 * @package Niceshops\Bean\BeanProcessor
 */
abstract class AbstractBeanProcessor implements
    BeanProcessorInterface,
    BeanSaverAwareInterface,
    BeanListAwareInterface,
    OptionAwareInterface,
    AttributeAwareInterface
{
    use BeanSaverAwareTrait;
    use BeanListAwareTrait;
    use OptionAwareTrait;
    use AttributeAwareTrait;

    /**
     *
     */
    public const OPTION_SAVE_NON_EMPTY_ONLY = "non_empty_only";
    public const OPTION_IGNORE_VALIDATION = "ignore_validation";


    /**
     * AbstractBeanProcessor constructor.
     *
     * @param BeanSaverInterface $saver
     */
    public function __construct(BeanSaverInterface $saver)
    {
        $this->setBeanSaver($saver);
    }

    /**
     * Returns the processed bean list.
     */
    public function save(): int
    {
        $beanList = $this->getBeanListForSave();
        foreach ($beanList as $bean) {
            $this->beforeSave($bean);
        }
        $saver = $this->getBeanSaver();
        if ($saver instanceof BeanListAwareInterface) {
            $saver->setBeanList($beanList);
        }
        $result = $saver->save();
        foreach ($beanList as $bean) {
            $this->afterSave($bean);
        }
        return $result;
    }


    /**
     * @param BeanFinderInterface $finder
     * @param BeanInterface $bean
     * @param string $orderField
     * @param int $steps
     * @param string|null $orderReferenceField
     * @param null $orderReferenceValue
     */
    public function move(
        BeanFinderInterface $finder,
        BeanInterface $bean,
        string $orderField,
        int $steps,
        string $orderReferenceField = null,
        $orderReferenceValue = null
    ) {
        if ($bean->has($orderField)) {
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
                            $previousBean->setData($orderField, $previousBean->getData($orderField) - 1);

                        }
                        if ($currentOrder > $newOrder) {
                            $previousBean->setData($orderField, $previousBean->getData($orderField) + 1);
                        }
                    }
                    $bean->set($orderField, $newOrder);
                    if ($currentOrder < $newOrder) {
                        $beanList->addBean($bean);
                    }
                    if ($currentOrder > $newOrder) {
                        $beanList->unshift($bean);
                    }
                }
                $this->setBeanList($beanList);
            }
            $this->save();
        }
    }


    /**
     * Returns the processed bean list.
     */
    public function delete(): int
    {
        $beanList = $this->getBeanListForDelete();
        foreach ($beanList as $bean) {
            $this->beforeDelete($bean);
        }
        $saver = $this->getBeanSaver();
        if ($saver instanceof BeanListAwareInterface) {
            $saver->setBeanList($beanList);
        }
        $result = $saver->delete();
        foreach ($beanList as $bean) {
            $this->afterDelete($bean);
        }
        return $result;
    }

    /**
     * @param BeanInterface $bean
     */
    protected function beforeSave(BeanInterface $bean)
    {
    }

    /**
     * @param BeanInterface $bean
     */
    protected function afterSave(BeanInterface $bean)
    {
    }

    /**
     * @param BeanInterface $bean
     */
    protected function beforeDelete(BeanInterface $bean)
    {
    }

    /**
     * @param BeanInterface $bean
     */
    protected function afterDelete(BeanInterface $bean)
    {
    }


    /**
     * Returns a filtered copy of the source bean list.
     * All saving operations are applied on this filtered copy.
     *
     */
    protected function getBeanListForSave(): BeanListInterface
    {
        return (clone $this->getBeanList())->filter(function (BeanInterface $bean) {
            return $this->isBeanAllowedToSave($bean);
        });
    }

    /**
     * @param BeanInterface $bean
     *
     * @return bool
     */
    protected function isBeanAllowedToSave(BeanInterface $bean): bool
    {
        if ($this->hasOption(self::OPTION_SAVE_NON_EMPTY_ONLY) && $bean instanceof Countable && $bean->count() == 0) {
            return false;
        }
        if ($this->hasOption(self::OPTION_IGNORE_VALIDATION)) {
            return true;
        }
        return $this->validateForSave($bean);
    }


    /**
     * Returns a filtered copy of the source bean list.
     * All saving operations are applied on this filtered copy.
     *
     */
    protected function getBeanListForDelete(): BeanListInterface
    {
        return (clone $this->getBeanList())->filter(function (BeanInterface $bean) {
            return $this->isBeanAllowedToDelete($bean);
        });
    }

    /**
     * @param BeanInterface $bean
     *
     * @return bool
     */
    protected function isBeanAllowedToDelete(BeanInterface $bean): bool
    {
        if ($this->hasOption(self::OPTION_IGNORE_VALIDATION)) {
            return true;
        }
        return $this->validateForDelete($bean);
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function validateForSave(BeanInterface $bean): bool
    {
        return true;
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        return true;
    }
}
