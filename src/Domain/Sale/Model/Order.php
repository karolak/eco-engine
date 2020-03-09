<?php
namespace Karolak\EcoEngine\Domain\Sale\Model;

use Karolak\EcoEngine\Domain\Sale\Collection\ItemsCollection;

/**
 * Class Order.
 * @package Karolak\EcoEngine\Domain\Sale\Model
 */
class Order
{
    /** @var ItemsCollection|Item[] */
    private $items;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->items = new ItemsCollection();
    }

    /**
     * @return ItemsCollection
     */
    public function getItems(): ItemsCollection
    {
        return $this->items;
    }

    /**
     * @param string $productId
     * @return Item|null
     */
    public function getItem(string $productId): ?Item
    {
        $key = $this->getItemKey($productId);
        if ($key === null) {
            return null;
        }

        return $this->items->get($key);
    }

    /**
     * @param string $productId
     * @param int $quantity
     */
    public function addProduct(string $productId, int $quantity = 1)
    {
        $item = $this->getItem($productId);
        if (!empty($item)) {
            $item->addQuantity($quantity);
            $this->items->set($this->getItemKey($productId), $item);
            return;
        }

        $this->items->add(new Item($productId, $quantity));
    }

    /**
     * @param string $productId
     * @return bool
     */
    public function hasProduct(string $productId): bool
    {
        return $this->getItemKey($productId) !== null;
    }

    /**
     * @param string $productId
     */
    public function removeProduct(string $productId)
    {
        $key = $this->getItemKey($productId);
        if ($key === null) {
            return;
        }

        $this->items->remove($key);
    }

    /**
     * @param string $productId
     * @param int $quantity
     */
    public function changeProductQuantity(string $productId, int $quantity)
    {
        $item = $this->getItem($productId);
        if (empty($item)) {
            return;
        }

        $item->setQuantity($quantity);
        $this->items->set($this->getItemKey($productId), $item);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    /**
     * @param string $productId
     * @return int|null
     */
    private function getItemKey(string $productId): ?int
    {
        if ($this->items->isEmpty()) {
            return null;
        }

        $result = null;
        foreach ($this->items as $key => $item) {
            if ($item->getProductId() === $productId) {
                $result = $key;
                break;
            }
        }

        return $result;
    }
}