<?php
namespace Karolak\EcoEngine\Domain\Sale\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\ValueObject\PromotionAction;

/**
 * Class PromotionActionsCollection
 * @package Karolak\EcoEngine\Domain\Sale\Collection
 */
class PromotionActionsCollection extends Collection
{
    /**
     * PromotionActionsCollection constructor.
     * @param PromotionAction ...$items
     */
    public function __construct(PromotionAction ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param PromotionAction $item
     */
    public function add(PromotionAction $item): void
    {
        $this->items[] = $item;
    }
}