<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use DateTimeImmutable;
use Karolak\EcoEngine\Domain\Common\Comparator\DateComparator;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class OrderDateCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class OrderDateCondition implements ConditionInterface
{
    /** @var DateTimeImmutable */
    private $value;

    /** @var string */
    private $comparison;

    /**
     * OrderDateCondition constructor.
     * @param DateTimeImmutable $value
     * @param string $comparison
     */
    public function __construct(DateTimeImmutable $value, string $comparison)
    {
        $this->value = $value;
        $this->comparison = $comparison;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        return DateComparator::compare($order->getCreated(), $this->comparison, $this->value);
    }
}