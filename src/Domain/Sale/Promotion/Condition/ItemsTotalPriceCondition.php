<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\NumericAttributesComparator;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class ItemsTotalPriceCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class ItemsTotalPriceCondition implements ConditionInterface
{
    /** @var int */
    private $price;

    /** @var string */
    private $comparison;

    /**
     * ItemsTotalPriceCondition constructor.
     * @param int $price
     * @param string $comparison
     */
    public function __construct(int $price, string $comparison = NumericAttributesComparator::EQUALS)
    {
        $this->price = $price;
        $this->comparison = $comparison;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        return NumericAttributesComparator::compare(
            new NumericAttribute('price', $order->getTotalProductsPrice()),
            $this->comparison,
            new NumericAttribute('price', $this->price)
        );
    }
}