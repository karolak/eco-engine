<?php
namespace Karolak\EcoEngine\Domain\Sale\Order\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;
use Karolak\EcoEngine\Domain\Sale\Order\Collection\AdjustmentsCollection;

/**
 * Class Item
 * @package Karolak\EcoEngine\Domain\Sale\Order\ValueObject
 */
class Item implements ValueObjectInterface
{
    /** @var Product */
    private $product;

    /** @var AdjustmentsCollection|Adjustment[] */
    private $adjustments;

    /**
     * Item constructor.
     * @param Product $product
     * @param AdjustmentsCollection|null $adjustmentsCollection
     */
    public function __construct(Product $product, ?AdjustmentsCollection $adjustmentsCollection = null)
    {
        $this->product = $product;
        $this->adjustments = $adjustmentsCollection instanceof AdjustmentsCollection ?
            $adjustmentsCollection : new AdjustmentsCollection();
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        $result = $this->product->getPrice();
        if ($this->adjustments->isEmpty()) {
            return $result;
        }

        foreach ($this->adjustments as $adjustment) {
            $result += $adjustment->getValue();
        }

        return $result;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return Adjustment[]
     */
    public function getAdjustments(): array
    {
        return $this->adjustments->toArray();
    }

    /**
     * @param ValueObjectInterface|Item $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof Item
            && $this->getProduct()->equals($object->getProduct());
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return $this->product->getId();
    }
}