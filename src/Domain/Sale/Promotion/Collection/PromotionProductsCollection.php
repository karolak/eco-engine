<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\PromotionProductAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\Promotion\ValueObject\PromotionProduct;

/**
 * Class PromotionProductsCollection
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Collection
 */
class PromotionProductsCollection extends Collection
{
    /**
     * PromotionProductsCollection constructor.
     * @param PromotionProduct ...$items
     * @throws PromotionProductAlreadyAddedException
     */
    public function __construct(PromotionProduct ...$items)
    {
        $this->items = [];

        if (empty($items)) {
            return;
        }

        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * @param PromotionProduct $item
     * @throws PromotionProductAlreadyAddedException
     */
    public function add(PromotionProduct $item): void
    {
        if (empty($this->items)) {
            $this->items[] = $item;
            return;
        }

        foreach ($this->items as $current) {
            if ($current->equals($item)) {
                throw new PromotionProductAlreadyAddedException();
            }
        }

        $this->items[] = $item;
    }
}