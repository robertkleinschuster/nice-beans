<?php

declare(strict_types=1);

namespace Pars\Bean\Processor;

use Pars\Bean\Saver\BeanSaverAwareInterface;
use Pars\Bean\Type\Base\BeanListAwareInterface;
use Pars\Bean\Validator\BeanValidatorInterface;
use Pars\Pattern\Attribute\AttributeAwareInterface;
use Pars\Pattern\Option\OptionAwareInterface;

/**
 * Interface ProcessorInterface
 * @package Pars\Library\Patterns
 */
interface BeanProcessorInterface extends BeanSaverAwareInterface,
    BeanListAwareInterface,
    OptionAwareInterface,
    AttributeAwareInterface
{

    /**
     * @return int
     */
    public function save(): int;

    /**
     * @return int
     */
    public function delete(): int;

    /**
     * @param MetaFieldHandlerInterface $fieldHandler
     * @return $this
     */
    public function addMetaFieldHandler(MetaFieldHandlerInterface $fieldHandler);

    /**
     * @param string $code
     * @return MetaFieldHandlerInterface
     */
    public function getMetaFieldHandler(string $code): MetaFieldHandlerInterface;

    /**
     * @param BeanValidatorInterface $validator
     * @return AbstractBeanProcessor
     */
    public function addSaveValidator(BeanValidatorInterface $validator);

    /**
     * @param string $code
     * @return BeanValidatorInterface
     */
    public function getSaveValidator(string $code): BeanValidatorInterface;

    /**
     * @param BeanValidatorInterface $validator
     * @return AbstractBeanProcessor
     */
    public function addDeleteValidator(BeanValidatorInterface $validator);

    /**
     * @param string $code
     * @return BeanValidatorInterface
     */
    public function getDeleteValidator(string $code): BeanValidatorInterface;


}
