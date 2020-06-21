<?php
namespace Karolak\EcoEngine\Domain\Sale\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;

/**
 * Class Item
 * @package Karolak\EcoEngine\Domain\Sale\ValueObject
 */
class Item implements ValueObjectInterface
{
    /** @var Product */
    private $product;

    /** @var int */
    private $quantity;

    /**
     * Item constructor.
     * @param Product $product
     * @param int $quantity
     */
    public function __construct(Product $product, int $quantity = 1)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param ValueObjectInterface|Item $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Item
            && $this->getProduct()->equals($object->getProduct())
            && $this->getQuantity() === $object->getQuantity();
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->product->getId().'-'.$this->quantity;
    }
}