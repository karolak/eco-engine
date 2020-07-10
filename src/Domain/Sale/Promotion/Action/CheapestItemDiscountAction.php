<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;

/**
 * Class CheapestItemDiscountAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
class CheapestItemDiscountAction implements ActionInterface
{
    /** @var float */
    private $percent;

    /**
     * CheapestItemDiscountAction constructor.
     * @param float $percent
     * @throws InvalidPercentValueException
     */
    public function __construct(float $percent = 100.00)
    {
        if ($percent < 0 || $percent > 100) {
            throw new InvalidPercentValueException();
        }

        $this->percent = $percent;
    }

    /**
     * @return float
     */
    public function getPercent(): float
    {
        return $this->percent;
    }
}