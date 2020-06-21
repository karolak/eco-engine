<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Class Country
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
class Country implements ValueObjectInterface
{
    /** @var string */
    private $code;

    /** @var string */
    private $name;

    /**
     * Country constructor.
     * @param string $code
     * @param string $name
     */
    public function __construct(string $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Country
            && $this->code === $object->getCode()
            && $this->name === $object->getName();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->code.' - '.$this->name;
    }
}