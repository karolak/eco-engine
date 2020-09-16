<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidGroupSizeException;

/**
 * Class CheapestItemFixedPriceAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class CheapestItemFixedPriceAction implements ActionInterface
{
    /** @var int */
    private $fixedPrice;

    /** @var int */
    private $inEveryGroupOf;

    /** @var ConditionInterface */
    private $condition;

    /**
     * CheapestItemFixedPriceAction constructor.
     * @param int $fixedPrice
     * @param int $inEveryGroupOf
     * @param ConditionInterface|null $condition
     * @throws InvalidGroupSizeException
     * @throws InvalidPriceValueException
     */
    public function __construct(int $fixedPrice, int $inEveryGroupOf = 0, ?ConditionInterface $condition = null)
    {
        if ($fixedPrice < 0) {
            throw new InvalidPriceValueException();
        }

        if ($inEveryGroupOf < 0) {
            throw new InvalidGroupSizeException();
        }

        $this->fixedPrice = $fixedPrice;
        $this->inEveryGroupOf = $inEveryGroupOf;
        $this->condition = $condition ?? new EmptyCondition();
    }

    /**
     * @return int
     */
    public function getFixedPrice(): int
    {
        return $this->fixedPrice;
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