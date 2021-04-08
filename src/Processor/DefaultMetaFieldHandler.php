<?php


namespace Pars\Bean\Processor;

use Pars\Bean\Type\Base\BeanInterface;

class DefaultMetaFieldHandler implements MetaFieldHandlerInterface
{
    /**
     * @var string
     */
    private string $field;
    /**
     * @var mixed
     */
    private $value;
    /**
     * @var bool
     */
    private bool $overwrite;

    /**
     * DefaultMetaFieldHandler constructor.
     * @param string $field
     * @param mixed $value
     * @param bool $overwrite
     */
    public function __construct(string $field, $value, bool $overwrite = false)
    {
        $this->field = $field;
        $this->value = $value;
        $this->overwrite = $overwrite;
    }

    /**
     * @param BeanInterface $bean
     * @return BeanInterface
     */
    public function handle(BeanInterface $bean): BeanInterface
    {
        if ($this->overwrite || $bean->empty($this->field)) {
            $bean->set($this->field, $this->value);
        }
        return $bean;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->field . '_' . $this->value . '_' . $this->overwrite;
    }


}
