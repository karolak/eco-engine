<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidGroupSizeException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;

/**
 * Class CheapestItemPercentDiscountAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class CheapestItemPercentDiscountAction implements ActionInterface
{
    /** @var float */
    private $percentDiscount;

    /** @var int */
    private $inEveryGroupOf;

    /** @var ConditionInterface */
    private $condition;

    /**
     * CheapestItemPercentDiscountAction constructor.
     * @param float $percentDiscount
     * @param int $inEveryGroupOf
     * @param ConditionInterface|null $condition
     * @throws InvalidGroupSizeException
     * @throws InvalidPercentValueException
     */
    public function __construct(float $percentDiscount = 100.00, int $inEveryGroupOf = 0, ?ConditionInterface $condition = null)
    {
        if ($percentDiscount < 0 || $percentDiscount > 100) {
            throw new InvalidPercentValueException();
        }

        if ($inEveryGroupOf < 0) {
            throw new InvalidGroupSizeException();
        }

        $this->percentDiscount = $percentDiscount;
        $this->inEveryGroupOf = $inEveryGroupOf;
        $this->condition = $condition ?? new EmptyCondition();
    }

    /**
     * @return float
     */
    public function getPercentDiscount(): float
    {
        return $this->percentDiscount;
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