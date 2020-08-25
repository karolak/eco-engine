<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class PriceFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class PriceFilter implements FilterInterface
{
    const EQUALS = '=';
    const EQUALS_OR_HIGHER = '>=';
    const EQUALS_OR_LOWER = '<=';
    const HIGHER = '>';
    const LOWER = '<';

    /** @var int */
    private $value;

    /** @var string */
    private $comparison;

    /**
     * PriceFilter constructor.
     * @param int $value
     * @param string $comparison
     */
    public function __construct(int $value, string $comparison)
    {
        $this->value = $value;
        $this->comparison = $comparison;
    }
    
    /**
     * @param array|Item[] $items
     * @return array|Item[]
     */
    public function filter(array $items): array
    {
        if (empty($items)) {
            return $items;
        }

        $result = [];
        foreach ($items as $item) {
            if ($this->check($item->getPrice(), $this->comparison, $this->value)) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @param int $a
     * @param string $comparison
     * @param int $b
     * @return bool
     */
    private function check(int $a, string $comparison, int $b)
    {
        $result = false;
        switch ($comparison) {
            case self::EQUALS:
                $result = $a == $b;
                break;
            case self::EQUALS_OR_HIGHER:
                $result = $a >= $b;
                break;
            case self::EQUALS_OR_LOWER:
                $result = $a <= $b;
                break;
            case self::HIGHER:
                $result = $a > $b;
                break;
            case self::LOWER:
                $result = $a < $b;
                break;
        }

        return $result;
    }
}