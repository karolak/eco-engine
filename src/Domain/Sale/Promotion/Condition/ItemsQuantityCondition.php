<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\NumericAttributesComparator;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class ItemsQuantityCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class ItemsQuantityCondition implements ConditionInterface
{
    /** @var int */
    private $quantity;

    /** @var string */
    private $comparison;

    /**
     * ItemsQuantityCondition constructor.
     * @param int $quantity
     * @param string $comparison
     */
    public function __construct(int $quantity, string $comparison = NumericAttributesComparator::EQUALS)
    {
        $this->quantity = $quantity;
        $this->comparison = $comparison;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        return NumericAttributesComparator::compare(
            new NumericAttribute('q', $order->getTotalProductsQuantity()),
            $this->comparison,
            new NumericAttribute('q', $this->quantity)
        );
    }
}