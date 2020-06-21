<?php
namespace Karolak\EcoEngine\Domain\Sale\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;

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
     */
    public function __construct(string $id, int $price = 0)
    {
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
    public function getPrice(): float
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