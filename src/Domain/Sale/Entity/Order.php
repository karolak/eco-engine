<?php
namespace Karolak\EcoEngine\Domain\Sale\Entity;

use Karolak\EcoEngine\Domain\Sale\Collection\ItemsCollection;
use Karolak\EcoEngine\Domain\Sale\Exception\InvalidItemQuantityException;
use Karolak\EcoEngine\Domain\Sale\Exception\ProductNotFoundException;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Customer;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Payment;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Shipment;

/**
 * Class Order
 * @package Karolak\EcoEngine\Domain\Sale\Entity
 */
class Order
{
    /** @var ItemsCollection|Item[] */
    private $items;

    /** @var Customer|null */
    private $customer;

    /** @var Shipment|null */
    private $shipment;

    /** @var Payment|null */
    private $payment;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->items = new ItemsCollection();
        $this->customer = null;
        $this->shipment = null;
        $this->payment = null;
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
     * @param Customer|null $customer
     */
    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Shipment|null $shipment
     */
    public function setShipment(?Shipment $shipment): void
    {
        $this->shipment = $shipment;
    }

    /**
     * @return Shipment|null
     */
    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    /**
     * @param Payment|null $payment
     */
    public function setPayment(?Payment $payment): void
    {
        $this->payment = $payment;
    }

    /**
     * @return Payment|null
     */
    public function getPayment(): ?Payment
    {
        return $this->payment;
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