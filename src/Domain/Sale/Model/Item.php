<?php
namespace Karolak\EcoEngine\Domain\Sale\Model;

/**
 * Class Item.
 * @package Karolak\EcoEngine\Domain\Sale\Model
 */
class Item
{
    /** @var string  */
    private $productId;

    /** @var int  */
    private $quantity;

    /**
     * Item constructor.
     * @param string $productId
     * @param int $quantity
     */
    public function __construct(string $productId, int $quantity = 1)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getProductId(): string
    {
        return $this->productId;
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