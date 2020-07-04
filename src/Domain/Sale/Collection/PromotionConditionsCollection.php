<?php
namespace Karolak\EcoEngine\Domain\Sale\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\ValueObject\PromotionCondition;

/**
 * Class PromotionConditionsCollection
 * @package Karolak\EcoEngine\Domain\Sale\Collection
 */
class PromotionConditionsCollection extends Collection
{
    /**
     * PromotionConditionsCollection constructor.
     * @param PromotionCondition ...$items
     */
    public function __construct(PromotionCondition ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param PromotionCondition $item
     */
    public function add(PromotionCondition $item): void
    {
        $this->items[] = $item;
    }
}