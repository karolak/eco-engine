<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Class TextAttribute
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
class TextAttribute implements AttributeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * NumericAttribute constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
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
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof TextAttribute
            && $this->name === $object->getName()
            && $this->value === $object->getValue();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->name . ':' . $this->value;
    }
}