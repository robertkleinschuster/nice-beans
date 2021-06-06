<?php


namespace Pars\Bean\Finder;


class FilterExpression
{

    public const OPERATOR_NOT_EQUAL = '!=';
    public const OPERATOR_EQUAL = '=';
    public const OPERATOR_GREATER_THAN = '>';
    public const OPERATOR_LESS_THAN_OR_EQUAL = '<=';

    protected $left;
    protected $right;
    protected string $operator;

    /**
     * FilterExpression constructor.
     * @param $left
     * @param $right
     * @param string $operator
     */
    public function __construct($left, $right, string $operator = self::OPERATOR_EQUAL)
    {
        $this->left = $left;
        $this->right = $right;
        $this->operator = $operator;
    }

    /**
     * @return mixed
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param mixed $left
     * @return FilterExpression
     */
    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param mixed $right
     * @return FilterExpression
     */
    public function setRight($right)
    {
        $this->right = $right;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return FilterExpression
     */
    public function setOperator(string $operator): FilterExpression
    {
        $this->operator = $operator;
        return $this;
    }


    /**
     * @param string $leftIdentifier
     * @param string $rightIdentifier
     * @return static
     */
    public static function equalIdentifier(string $leftIdentifier, string $rightIdentifier)
    {
        return new static(new FilterIdentifier($leftIdentifier), new FilterIdentifier($rightIdentifier), self::OPERATOR_EQUAL);
    }

    /**
     * @param string $leftIdentifier
     * @param string $rightIdentifier
     * @return static
     */
    public static function notEqualIdentifier(string $leftIdentifier, string $rightIdentifier)
    {
        return new static(new FilterIdentifier($leftIdentifier), new FilterIdentifier($rightIdentifier), self::OPERATOR_NOT_EQUAL);
    }

    /**
     * @param $left
     * @param $right
     * @return static
     */
    public static function greaterThan($left, $right)
    {
        return new static($left, $right, self::OPERATOR_GREATER_THAN);
    }

    /**
     * @param $left
     * @param $right
     * @return static
     */
    public static function lessThanOrEqual($left, $right)
    {
        return new static($left, $right, self::OPERATOR_LESS_THAN_OR_EQUAL);
    }


}
