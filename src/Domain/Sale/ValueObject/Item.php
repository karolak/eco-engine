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

    /**
     * Item constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param ValueObjectInterface|Item $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Item
            && $this->getProduct()->equals($object->getProduct());
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->product->getId();
    }
}