<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;

/**
 * Class PromotionsCollection
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Collection
 */
class PromotionsCollection extends Collection
{
    /**
     * PromotionsCollection constructor.
     * @param Promotion ...$items
     */
    public function __construct(Promotion ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param Promotion $item
     */
    public function add(Promotion $item): void
    {
        $this->items[] = $item;
    }
}