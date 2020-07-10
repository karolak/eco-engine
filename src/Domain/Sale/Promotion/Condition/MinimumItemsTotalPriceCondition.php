<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class MinimumItemsTotalPriceCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class MinimumItemsTotalPriceCondition implements ConditionInterface
{
    /** @var int */
    private $price;

    /**
     * MinimumItemsTotalPriceCondition constructor.
     * @param int $price
     */
    public function __construct(int $price)
    {
        $this->price = $price;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        return $order->getTotalProductsPrice() >= $this->price;
    }
}