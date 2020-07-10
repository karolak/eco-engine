<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;

/**
 * Class ItemsFixedDiscountAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class ItemsFixedDiscountAction implements ActionInterface
{
    /** @var int */
    private $value;

    /**
     * FixedDiscountItemsAction constructor.
     * @param int $value
     * @throws InvalidPriceValueException
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidPriceValueException();
        }

        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}