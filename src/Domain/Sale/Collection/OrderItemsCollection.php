<?php
namespace Karolak\EcoEngine\Domain\Sale\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Model\OrderItem;

/**
 * Class OrderItemsCollection.
 * @package Karolak\EcoEngine\Domain\Sale\Collection
 */
class OrderItemsCollection extends Collection
{
    /**
     * OrderItemsArrayCollection constructor.
     * @param OrderItem ...$items
     */
    public function __construct(OrderItem ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param OrderItem $orderItem
     */
    public function add(OrderItem $orderItem): void
    {
        $this->items[] = $orderItem;
    }

    /**
     * @param OrderItem $orderItem
     * @return bool
     */
    public function remove(OrderItem $orderItem): bool
    {
        $key = $this->keyOf($orderItem);
        if (null === $key) {
            return false;
        }

        unset($this->items[$key]);
        $this->items = array_values($this->items);

        return true;
    }

    /**
     * @param OrderItem $orderItem
     * @return int|null
     */
    public function keyOf(OrderItem $orderItem): ?int
    {
        $result = array_search($orderItem, $this->items, true);

        return $result !== false ? $result : null;
    }

    /**
     * @param OrderItem $orderItem
     * @return bool
     */
    public function has(OrderItem $orderItem): bool
    {
        return in_array($orderItem, $this->items, true);
    }

    /**
     * @param int $key
     * @return OrderItem|null
     */
    public function get(int $key): ?OrderItem
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @param int $key
     * @param OrderItem $orderItem
     */
    public function set(int $key, OrderItem $orderItem): void
    {
        $this->items[$key] = $orderItem;
    }
}