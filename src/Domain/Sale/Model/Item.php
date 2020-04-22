<?php
namespace Karolak\EcoEngine\Domain\Sale\Model;

/**
 * Class Item.
 * @package Karolak\EcoEngine\Domain\Sale\Model
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
     * @return string
     */
    public function getProductId(): string
    {
        return $this->product->getId();
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function addQuantity(int $quantity)
    {
        $this->quantity += $quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }
}