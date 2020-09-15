<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;

/**
 * Class ItemsPercentDiscountAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class ItemsPercentDiscountAction implements ActionInterface
{
    /** @var float */
    private $value;

    /** @var ConditionInterface */
    private $condition;

    /**
     * PercentDiscountItemsAction constructor.
     * @param float $value
     * @param ConditionInterface|null $condition
     * @throws InvalidPercentValueException
     */
    public function __construct(float $value, ?ConditionInterface $condition = null)
    {
        if ($value < 0 || $value > 100) {
            throw new InvalidPercentValueException();
        }

        $this->value = $value;
        $this->condition = $condition ?? new EmptyCondition();
    }

    /**
     * @return float
     */
    public function getValue(): float
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