<?php
namespace Karolak\EcoEngine\Domain\Sale\Entity;

use Karolak\EcoEngine\Domain\Sale\Collection\ItemsCollection;
use Karolak\EcoEngine\Domain\Sale\Exception\ProductNotFoundException;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Customer;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Invoice;
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

    /** @var Invoice|null */
    private $invoice;

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
        $this->invoice = null;
        $this->shipment = null;
        $this->payment = null;
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $this->items->add(new Item($product));
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
        return $this->items->count();
    }

    /**
     * @return int
     */
    public function getTotalProductsPrice(): int
    {
        if ($this->items->isEmpty()) {
            return 0;
        }

        return array_sum(
            array_map(function (Item $item) {
                return $item->getPrice();
            },
            $this->items->toArray())
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
     * @param Invoice|null $invoice
     */
    public function setInvoice(?Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    /**
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
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
            if ($product->equals($value->getProduct())) {
                $result = $key;
                break;
            }
        }

        return $result;
    }
}