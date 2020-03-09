<?php
namespace Karolak\EcoEngine\Domain\Sale\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Model\Item;

/**
 * Class ItemsCollection.
 * @package Karolak\EcoEngine\Domain\Sale\Collection
 */
class ItemsCollection extends Collection
{
    /**
     * ItemsArrayCollection constructor.
     * @param Item ...$items
     */
    public function __construct(Item ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param Item $item
     */
    public function add(Item $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @param int $key
     * @return Item|null
     */
    public function get(int $key): ?Item
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @param int $key
     */
    public function remove(int $key): void
    {
        unset($this->items[$key]);
        $this->items = array_values($this->items);
    }

    /**
     * @param int $key
     * @param Item $item
     */
    public function set(int $key, Item $item): void
    {
        $this->items[$key] = $item;
    }
}