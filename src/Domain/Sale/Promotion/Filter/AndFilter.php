<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class AndFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class AndFilter implements FilterInterface
{
    /** @var FilterInterface[] */
    private $filters;

    /**
     * AndFilter constructor.
     * @param FilterInterface ...$filters
     */
    public function __construct(FilterInterface ...$filters)
    {
        $this->filters = $filters;
    }


    /**
     * @param array|Item[] $items
     * @return array|Item[]
     */
    public function filter(array $items): array
    {
        $result = $items;
        foreach ($this->filters as $filter) {
            $result = $filter->filter($result);
        }

        return $result;
    }
}