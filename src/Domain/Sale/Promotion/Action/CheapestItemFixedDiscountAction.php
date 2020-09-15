<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidGroupSizeException;

/**
 * Class CheapestItemFixedDiscountAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class CheapestItemFixedDiscountAction implements ActionInterface
{
    /** @var int */
    private $fixedDiscount;

    /** @var int */
    private $inEveryGroupOf;

    /** @var ConditionInterface */
    private $condition;

    /**
     * CheapestItemFixedDiscountAction constructor.
     * @param int $fixedDiscount
     * @param int $inEveryGroupOf
     * @param ConditionInterface|null $condition
     * @throws InvalidGroupSizeException
     * @throws InvalidPriceValueException
     */
    public function __construct(int $fixedDiscount, int $inEveryGroupOf = 0, ?ConditionInterface $condition = null)
    {
        if ($fixedDiscount < 0) {
            throw new InvalidPriceValueException();
        }

        if ($inEveryGroupOf < 0) {
            throw new InvalidGroupSizeException();
        }

        $this->fixedDiscount = $fixedDiscount;
        $this->inEveryGroupOf = $inEveryGroupOf;
        $this->condition = $condition ?? new EmptyCondition();
    }

    /**
     * @return int
     */
    public function getFixedDiscount(): int
    {
        return $this->fixedDiscount;
    }

    /**
     * @return int
     */
    public function getInEveryGroupOf(): int
    {
        return $this->inEveryGroupOf;
    }

    /**
     * @return ConditionInterface
     */
    public function getCondition(): ConditionInterface
    {
        return $this->condition;
    }
}