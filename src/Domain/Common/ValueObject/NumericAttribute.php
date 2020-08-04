<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Class NumericAttribute
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
class NumericAttribute implements AttributeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $value;

    /**
     * NumericAttribute constructor.
     * @param string $name
     * @param float $value
     */
    public function __construct(string $name, float $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof NumericAttribute
            && $this->name === $object->getName()
            && $this->value === $object->getValue();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->name . ':' . strval($this->value);
    }
}