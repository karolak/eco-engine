<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;

/**
 * Class ItemsFixedDiscountAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class ItemsFixedDiscountAction implements ActionInterface
{
    /** @var int */
    private $value;

    /** @var ConditionInterface */
    private $condition;

    /**
     * FixedDiscountItemsAction constructor.
     * @param int $value
     * @param ConditionInterface|null $condition
     * @throws InvalidPriceValueException
     */
    public function __construct(int $value, ?ConditionInterface $condition = null)
    {
        if ($value < 0) {
            throw new InvalidPriceValueException();
        }

        $this->value = $value;
        $this->condition = $condition ?? new EmptyCondition();
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return ConditionInterface
     */
    public function getCondition(): ConditionInterface
    {
        return $this->condition;
    }
}