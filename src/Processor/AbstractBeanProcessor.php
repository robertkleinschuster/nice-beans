<?php

declare(strict_types=1);

namespace Niceshops\Bean\Processor;

use Countable;
use Niceshops\Bean\Saver\BeanSaverAwareInterface;
use Niceshops\Bean\Saver\BeanSaverAwareTrait;
use Niceshops\Bean\Saver\BeanSaverInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Niceshops\Bean\Type\Base\BeanListAwareInterface;
use Niceshops\Bean\Type\Base\BeanListAwareTrait;
use Niceshops\Bean\Type\Base\BeanListInterface;
use Niceshops\Bean\Validator\BeanValidatorInterface;
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
     * @var MetaFieldHandlerInterface[]
     */
    private array $metaHandler = [];

    /**
     * @var BeanValidatorInterface[]
     */
    private array $saveValidators = [];

    /**
     * @var BeanValidatorInterface[]
     */
    private array $deleteValidators = [];

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
     * @param MetaFieldHandlerInterface $fieldHandler
     * @return $this
     */
    public function addMetaFieldHandler(MetaFieldHandlerInterface $fieldHandler)
    {
        $this->metaHandler[$fieldHandler->code()] = $fieldHandler;
        return $this;
    }

    /**
     * @param string $code
     * @return MetaFieldHandlerInterface
     */
    public function getMetaFieldHandler(string $code): MetaFieldHandlerInterface
    {
        return $this->metaHandler[$code];
    }

    /**
     * @param BeanValidatorInterface $validator
     * @return AbstractBeanProcessor
     */
    public function addSaveValidator(BeanValidatorInterface $validator)
    {
        $this->saveValidators[$validator->code()] = $validator;
        return $this;
    }

    /**
     * @param string $code
     * @return BeanValidatorInterface
     */
    public function getSaveValidator(string $code): BeanValidatorInterface
    {
        return $this->saveValidators[$code];
    }

    /**
     * @param BeanValidatorInterface $validator
     * @return AbstractBeanProcessor
     */
    public function addDeleteValidator(BeanValidatorInterface $validator)
    {
        $this->deleteValidators[$validator->code()] = $validator;
        return $this;
    }

    /**
     * @param string $code
     * @return BeanValidatorInterface
     */
    public function getDeleteValidator(string $code): BeanValidatorInterface
    {
        return $this->deleteValidators[$code];
    }

    /**
     * Returns the processed bean list.
     */
    public function save(): int
    {
        $beanList = $this->getBeanListForSave();
        foreach ($beanList as $bean) {
            $this->beforeSave($bean);
            foreach ($this->metaHandler as $item) {
                $item->handle($bean);
            }
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
        $result = [];
        foreach ($this->saveValidators as $saveValidator) {
            $result[] = $saveValidator->validate($bean);
        }
        return !in_array(false, $result);
    }

    /**
     * @param BeanInterface $bean
     * @return bool
     */
    protected function validateForDelete(BeanInterface $bean): bool
    {
        $result = [];
        foreach ($this->deleteValidators as $deleteValidator) {
            $result[] = $deleteValidator->validate($bean);
        }
        return !in_array(false, $result);
    }
}
