<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class EmptyFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class EmptyFilter implements FilterInterface
{
    /**
     * @param array|Item[] $items
     * @return array|Item[]
     */
    public function filter(array $items): array
    {
        return $items;
    }
}