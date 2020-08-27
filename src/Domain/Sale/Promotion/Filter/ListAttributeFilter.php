<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class ListAttributeFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class ListAttributeFilter implements FilterInterface
{
    const STRICT = 'strict';
    const IN = 'in';

    /** @var ListAttribute */
    private $attribute;

    /** @var string */
    private $comparison;

    /**
     * ListAttributeFilter constructor.
     * @param ListAttribute $attribute
     * @param string $comparison
     */
    public function __construct(ListAttribute $attribute, string $comparison = self::STRICT)
    {
        $this->attribute = $attribute;
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
        $value = $this->attribute->getValue();
        $name = $this->attribute->getName();
        foreach ($items as $item) {
            try {
                $attr = $item->getProduct()->getAttributeByName($name);
            } catch (AttributeNotFoundException $e) {
                continue;
            }

            if ($this->check($attr->getValue(), $this->comparison, $value)) {
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @param array $a
     * @param string $comparison
     * @param array $b
     * @return bool
     */
    private function check(array $a, string $comparison, array $b)
    {
        $result = false;
        switch ($comparison) {
            case self::STRICT:
                $result = count($a) === count($b) && array_diff($a, $b) === array_diff($b, $a);
                break;
            case self::IN:
                $result = !empty(array_intersect($a, $b));
                break;
        }

        return $result;
    }
}