<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class OrCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class OrCondition implements ConditionInterface
{
    /** @var array */
    private $conditions;

    /**
     * OrCondition constructor.
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
        $result = false;
        foreach ($this->conditions as $condition) {
            if ($condition->isSatisfiedBy($order)) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}