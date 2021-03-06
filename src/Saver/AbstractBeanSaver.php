<?php

declare(strict_types=1);

namespace Pars\Bean\Saver;

use Pars\Bean\Type\Base\BeanException;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Bean\Type\Base\BeanListAwareInterface;
use Pars\Bean\Type\Base\BeanListAwareTrait;
use Pars\Bean\Type\Base\BeanListInterface;
use Pars\Pattern\Attribute\AttributeAwareInterface;
use Pars\Pattern\Attribute\AttributeAwareTrait;
use Pars\Pattern\Option\OptionAwareInterface;
use Pars\Pattern\Option\OptionAwareTrait;
use Throwable;

/**
 * Class AbstractBeanSaver
 * @package Pars\Library\Patterns
 */
abstract class AbstractBeanSaver implements BeanSaverInterface, BeanListAwareInterface, OptionAwareInterface, AttributeAwareInterface
{
    use BeanListAwareTrait;
    use OptionAwareTrait;
    use AttributeAwareTrait;

    /**
     * @return int number of successfully saved beans
     * @throws BeanException
     */
    public function save(): int
    {
        if (!$this->hasBeanList()) {
            throw new BeanException('No bean list set in bean saver.');
        }
        $affectdRows = 0;
        try {
            $affectdRows = $this->saveBeanList($this->getBeanList());
        } catch (Throwable $error) {
            $this->onError($error);
        }
        return $affectdRows;
    }

    /**
     * @return int number of successfully saved beans
     * @throws BeanException
     */
    public function delete(): int
    {
        if (!$this->hasBeanList()) {
            throw new BeanException('No bean list set in bean saver.');
        }
        $affectdRows = 0;
        try {
            $affectdRows = $this->deleteBeanList($this->getBeanList());
        } catch (Throwable $error) {
            $this->onError($error);
        }
        return $affectdRows;
    }

    /**
     * @param BeanListInterface $beanList
     *
     * @return int number of successfully saved beans
     */
    protected function saveBeanList(BeanListInterface $beanList): int
    {
        $affectdRows = 0;
        foreach ($beanList as $bean) {
            if ($this->saveBean($bean)) {
                $affectdRows++;
            }
        }
        return $affectdRows;
    }

    /**
     * @param BeanListInterface $beanList
     *
     * @return int number of successfully saved beans
     */
    protected function deleteBeanList(BeanListInterface $beanList): int
    {
        $affectdRows = 0;
        foreach ($beanList as $bean) {
            if ($this->deleteBean($bean)) {
                $affectdRows++;
            }
        }
        return $affectdRows;
    }

    /**
     * @param BeanInterface $bean
     *
     * @return bool true on success
     */
    abstract protected function saveBean(BeanInterface $bean): bool;

    /**
     * @param BeanInterface $bean
     *
     * @return bool true on success
     */
    abstract protected function deleteBean(BeanInterface $bean): bool;

    /**
     * @param Throwable $error
     *
     * @throws BeanException
     */
    protected function onError(Throwable $error)
    {
        throw new BeanException($error->getMessage());
    }
}
