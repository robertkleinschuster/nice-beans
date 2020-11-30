<?php


namespace Niceshops\Bean\Processor;


use Niceshops\Bean\Type\Base\BeanInterface;

class TimestampMetaFieldHandler implements MetaFieldHandlerInterface
{

    /**
     * @var string
     */
    private string $editField;

    /**
     * @var string
     */
    private string $createField;

    /**
     * @var string
     */
    private string $format;

    /**
     * TimestampMetaFieldHandler constructor.
     * @param string $editField
     * @param string $createField
     * @param string $format
     */
    public function __construct(string $editField, string $createField, string $format = 'Y-m-d H:i:s')
    {
        $this->editField = $editField;
        $this->createField = $createField;
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function code(): string
    {
        return $this->editField . '_' . $this->createField . '_' . $this->format;
    }


    /**
     * @return string
     */
    protected function getEditField(): string
    {
        return $this->editField;
    }

    /**
     * @return string
     */
    protected function getCreateField(): string
    {
        return $this->createField;
    }

    /**
     * @param BeanInterface $bean
     * @return BeanInterface
     */
    public function handle(BeanInterface $bean): BeanInterface
    {
        $bean->set($this->getEditField(), new \DateTime());
        if ($bean->empty($this->getCreateField())) {
            $bean->set($this->getCreateField(), new \DateTime());
        }
        return $bean;
    }

}
