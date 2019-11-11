<?php
namespace Karolak\EcoEngine\Domain\Sale\Model;

use Karolak\EcoEngine\Domain\Sale\Collection\OrderItemsCollection;

/**
 * Class Order.
 * @package Karolak\EcoEngine\Domain\Sale\Model
 */
class Order
{
    /** @var OrderItemsCollection */
    private $items;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->items = new OrderItemsCollection();
    }

    /**
     * @return OrderItemsCollection
     */
    public function getItems(): OrderItemsCollection
    {
        return $this->items;
    }

    /**
     * @param OrderItem $orderItem
     */
    public function addItem(OrderItem $orderItem): void
    {
        $this->items->add($orderItem);
    }

    /**
     * @param OrderItem $orderItem
     * @return bool
     */
    public function hasItem(OrderItem $orderItem): bool
    {
        return $this->items->has($orderItem);
    }

    /**
     * @param OrderItem $orderItem
     * @return bool
     */
    public function removeItem(OrderItem $orderItem): bool
    {
        return $this->items->remove($orderItem);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }
}