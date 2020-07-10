<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;

/**
 * Class ItemsPercentDiscountAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class ItemsPercentDiscountAction implements ActionInterface
{
    /** @var float */
    private $value;

    /**
     * PercentDiscountItemsAction constructor.
     * @param float $value
     * @throws InvalidPercentValueException
     */
    public function __construct(float $value)
    {
        if ($value < 0 || $value > 100) {
            throw new InvalidPercentValueException();
        }

        $this->value = $value;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}