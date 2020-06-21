<?php
namespace Karolak\EcoEngine\Domain\Sale\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;
use Karolak\EcoEngine\Domain\Sale\Exception\InvalidPriceValueException;

/**
 * Class Product
 * @package Karolak\EcoEngine\Domain\Sale\ValueObject
 */
class Product implements ValueObjectInterface
{
    /** @var string */
    private $id;

    /** @var int */
    private $price;

    /**
     * Product constructor.
     * @param string $id
     * @param int $price
     * @throws InvalidPriceValueException
     */
    public function __construct(string $id, int $price)
    {
        if ($price < 0) {
            throw new InvalidPriceValueException();
        }
        $this->id = $id;
        $this->price = $price;
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
     * @param ValueObjectInterface|Product $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Product
            && $this->id == $object->getId()
            && $this->price == $object->getPrice();
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