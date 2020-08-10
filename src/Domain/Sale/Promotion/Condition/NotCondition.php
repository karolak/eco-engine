<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class NotCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class NotCondition implements ConditionInterface
{
    /**
     * @var ConditionInterface
     */
    private $condition;

    /**
     * NotCondition constructor.
     * @param ConditionInterface $condition
     */
    public function __construct(ConditionInterface $condition)
    {
        $this->condition = $condition;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        return !$this->condition->isSatisfiedBy($order);
    }
}