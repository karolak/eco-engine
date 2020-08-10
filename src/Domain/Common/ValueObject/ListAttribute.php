<?php
namespace Karolak\EcoEngine\Domain\Common\ValueObject;

/**
 * Class ListAttribute
 * @package Karolak\EcoEngine\Domain\Common\ValueObject
 */
class ListAttribute implements AttributeInterface
{
    /** @var string */
    private $name;

    /** @var array */
    private $value;

    /**
     * ListAttribute constructor.
     * @param string $name
     * @param array $value
     */
    public function __construct(string $name, array $value)
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
     * @return array
     */
    public function getValue(): array
    {
        return $this->value;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof ListAttribute
            && $this->name === $object->getName()
            && $this->value === $object->getValue();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->name . ':' . implode(', ', $this->value);
    }
}