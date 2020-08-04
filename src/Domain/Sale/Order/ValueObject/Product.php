<?php
namespace Karolak\EcoEngine\Domain\Sale\Order\ValueObject;

use Karolak\EcoEngine\Domain\Common\Collection\AttributesCollection;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\AttributeInterface;
use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;

/**
 * Class Product
 * @package Karolak\EcoEngine\Domain\Sale\Order\ValueObject
 */
class Product implements ValueObjectInterface
{
    /** @var string */
    private $id;

    /** @var int */
    private $price;

    /** @var AttributesCollection */
    private $attributes;

    /**
     * Product constructor.
     * @param string $id
     * @param int $price
     * @param array $attributes
     * @throws InvalidPriceValueException
     */
    public function __construct(string $id, int $price, array $attributes = [])
    {
        if ($price < 0) {
            throw new InvalidPriceValueException();
        }
        $this->id = $id;
        $this->price = $price;
        $this->attributes = new AttributesCollection(...$attributes);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes->toArray();
    }

    /**
     * @param string $name
     * @return AttributeInterface
     * @throws AttributeNotFoundException
     */
    public function getAttributeByName(string $name): AttributeInterface
    {
        $result = $this->attributes->getByName($name);
        if (empty($result)) {
            throw new AttributeNotFoundException();
        }

        return $result;
    }

    /**
     * @param ValueObjectInterface|Product $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Product
            && $this->id == $object->getId()
            && $this->price == $object->getPrice()
            && $this->attributes->toArray() == $object->getAttributes();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->id;
    }
}