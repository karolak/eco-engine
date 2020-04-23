<?php
namespace Karolak\EcoEngine\Domain\Sale\ValueObject;

/**
 * Class Item
 * @package Karolak\EcoEngine\Domain\Sale\ValueObject
 */
class Item
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
}