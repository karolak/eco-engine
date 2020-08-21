<?php
namespace Karolak\EcoEngine\Domain\Sale\Order\Entity;

use Karolak\EcoEngine\Domain\Sale\Order\Collection\AdjustmentsCollection;
use Karolak\EcoEngine\Domain\Sale\Order\Collection\ItemsCollection;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Adjustment;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Customer;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Invoice;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Payment;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Shipment;
use Karolak\EcoEngine\Domain\Sale\Promotion\Collection\PromotionsCollection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;

/**
 * Class Order
 * @package Karolak\EcoEngine\Domain\Sale\Order\Entity
 */
class Order
{
    /** @var ItemsCollection|Item[] */
    private $items;

    /** @var PromotionsCollection|Promotion[] */
    private $promotions;

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
        $this->promotions = new PromotionsCollection();
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
     * @param Item $item
     * @throws ItemNotFoundException
     */
    public function removeItem(Item $item)
    {
        $key = $this->findItemKey($item);
        if ($key === null) {
            throw new ItemNotFoundException();
        }

        $this->items->remove($key);
    }

    /**
     * @param Adjustment $adjustment
     * @param Item $item
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     */
    public function addAdjustmentToItem(Adjustment $adjustment, Item $item)
    {
        if ($adjustment->getValue() < 0 && abs($adjustment->getValue()) > $item->getPrice()) {
            throw new InvalidPriceValueException();
        }

        $itemKey = $this->findItemKey($item);
        if ($itemKey === null) {
            throw new ItemNotFoundException();
        }

        $adjustments = new AdjustmentsCollection(...$item->getAdjustments());
        $adjustments->add($adjustment);
        $this->items->set($itemKey, new Item($item->getProduct(), $adjustments));
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items->toArray();
    }

    /**
     * @param Promotion $promotion
     */
    public function addPromotion(Promotion $promotion)
    {
        $this->promotions->add($promotion);
    }

    /**
     * @return array|Promotion[]
     */
    public function getPromotions()
    {
        return $this->promotions->toArray();
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
        return $this->getProductsPrice($this->items->toArray());
    }

    /**
     * @param array|Item[] $items
     * @return int
     */
    public function getProductsPrice(array $items): int
    {
        if (count($items) === 0) {
            return 0;
        }

        return array_sum(
            array_map(
                function (Item $item) {
                    return $item->getPrice();
                },
                $items
            )
        );
    }

    /**
     * @return int
     */
    public function getShipmentPrice(): int
    {
        if (empty($this->shipment)) {
            return 0;
        }

        return $this->shipment->getPrice();
    }

    /**
     * @return int
     */
    public function getTotalPrice(): int
    {
        return $this->getTotalProductsPrice() + $this->getShipmentPrice();
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
     * @param Item $item
     * @return int|null
     */
    private function findItemKey(Item $item): ?int
    {
        if ($this->items->count() === 0) {
            return null;
        }

        $result = null;
        foreach ($this->items as $key => $value) {
            if ($item->equals($value)) {
                $result = $key;
                break;
            }
        }

        return $result;
    }
}