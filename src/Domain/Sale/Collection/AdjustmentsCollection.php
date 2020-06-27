<?php
namespace Karolak\EcoEngine\Domain\Sale\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Adjustment;

/**
 * Class AdjustmentsCollection
 * @package Karolak\EcoEngine\Domain\Sale\Collection
 */
class AdjustmentsCollection extends Collection
{
    /**
     * AdjustmentsArrayCollection constructor.
     * @param Adjustment ...$items
     */
    public function __construct(Adjustment ...$items)
    {
        $this->items = $items;
    }

    /**
     * @param Adjustment $item
     */
    public function add(Adjustment $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @param int $key
     * @return Adjustment|null
     */
    public function get(int $key): ?Adjustment
    {
        return $this->items[$key] ?? null;
    }

    /**
     * @param int $key
     */
    public function remove(int $key): void
    {
        unset($this->items[$key]);
        $this->items = array_values($this->items);
    }

    /**
     * @param int $key
     * @param Adjustment $item
     */
    public function set(int $key, Adjustment $item): void
    {
        $this->items[$key] = $item;
    }
}