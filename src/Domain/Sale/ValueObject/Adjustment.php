<?php
namespace Karolak\EcoEngine\Domain\Sale\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;

/**
 * Class Adjustment
 * @package Karolak\EcoEngine\Domain\Sale\ValueObject
 */
class Adjustment implements ValueObjectInterface
{
    /** @var int */
    private $value;

    /** @var string */
    private $type;

    /** @var string */
    private $label;

    /**
     * Adjustment constructor.
     * @param int $value
     * @param string $type
     * @param string $label
     */
    public function __construct(int $value, string $type, string $label = '')
    {
        $this->value = $value;
        $this->type = $type;
        $this->label = $label;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Adjustment
            && $this->value === $object->getValue()
            && $this->type === $object->getType()
            && $this->label === $object->getLabel();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }
}