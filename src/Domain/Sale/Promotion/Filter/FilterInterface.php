<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Interface FilterInterface
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
interface FilterInterface
{
    /**
     * @param array|Item[] $items
     * @return array|Item[]
     */
    public function filter(array $items): array;
}