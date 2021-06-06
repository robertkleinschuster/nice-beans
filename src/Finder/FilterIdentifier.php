<?php


namespace Pars\Bean\Finder;


class FilterIdentifier
{
    protected string $identifier;

    /**
     * FilterIdentifier constructor.
     * @param string $identifier
     */
    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public static function create(string $identifier)
    {
        return new static($identifier);
    }
}
