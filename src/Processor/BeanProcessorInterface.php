<?php

declare(strict_types=1);

namespace Niceshops\Bean\Processor;

use Niceshops\Bean\Validator\BeanValidatorInterface;

/**
 * Interface ProcessorInterface
 * @package Niceshops\Library\Core
 */
interface BeanProcessorInterface
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
