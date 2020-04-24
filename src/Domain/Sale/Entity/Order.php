<?php
namespace Karolak\EcoEngine\Domain\Sale\Entity;

use Karolak\EcoEngine\Domain\Sale\Collection\ItemsCollection;
use Karolak\EcoEngine\Domain\Sale\Exception\InvalidItemQuantityException;
use Karolak\EcoEngine\Domain\Sale\Exception\ProductNotFoundException;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Product;

/**
 * Class Order
 * @package Karolak\EcoEngine\Domain\Sale\Entity
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
     * @param Product $product
     * @param int $quantity
     * @throws InvalidItemQuantityException
     */
    public function addProduct(Product $product, int $quantity = 1)
    {
        if ($quantity <= 0) {
            throw new InvalidItemQuantityException();
        }

        $key = $this->findItemKeyForProduct($product);
        if ($key !== null) {
            $this->addQuantityToItem($key, $quantity);

            return;
        }

        $this->items->add(new Item($product, $quantity));
    }

    /**
     * @param Product $product
     * @throws ProductNotFoundException
     */
    public function removeProduct(Product $product)
    {
        $key = $this->findItemKeyForProduct($product);
        if ($key === null) {
            throw new ProductNotFoundException();
        }

        $this->items->remove($key);
    }

    /**
     * @param Product $product
     * @param int $quantity
     * @throws InvalidItemQuantityException
     * @throws ProductNotFoundException
     */
    public function changeProductQuantity(Product $product, int $quantity = 1)
    {
        if ($quantity <= 0) {
            throw new InvalidItemQuantityException();
        }

        $key = $this->findItemKeyForProduct($product);
        if ($key === null) {
            throw new ProductNotFoundException();
        }

        $this->setItemQuantity($key, $quantity);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items->toArray();
    }

    /**
     * @return int
     */
    public function getTotalProductsQuantity(): int
    {
        if ($this->isEmpty()) {
            return 0;
        }

        return array_sum(
            array_map(
                function (Item $item) {
                    return $item->getQuantity();
                },
                $this->items->toArray()
            )
        );
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    /**
     * @param Product $product
     * @return int|null
     */
    private function findItemKeyForProduct(Product $product): ?int
    {
        if ($this->items->count() === 0) {
            return null;
        }

        $result = null;
        foreach ($this->items as $key => $value) {
            if ($value->getProduct()->equals($product)) {
                $result = $key;
                break;
            }
        }

        return $result;
    }

    /**
     * @param int $key
     * @param int $quantity
     */
    private function addQuantityToItem(int $key, int $quantity)
    {
        $item = $this->items->get($key);
        $this->items->set(
            $key,
            new Item($item->getProduct(), $item->getQuantity() + $quantity)
        );
    }

    /**
     * @param int $key
     * @param int $quantity
     */
    private function setItemQuantity(int $key, int $quantity)
    {
        $item = $this->items->get($key);
        $this->items->set(
            $key,
            new Item($item->getProduct(), $quantity)
        );
    }
}