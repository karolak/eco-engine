<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class AndCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class AndCondition implements ConditionInterface
{
    /** @var array */
    private $conditions;

    /**
     * AndCondition constructor.
     * @param ConditionInterface ...$conditions
     */
    public function __construct(ConditionInterface ...$conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        $result = true;
        foreach ($this->conditions as $condition) {
            if (!$condition->isSatisfiedBy($order)) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}