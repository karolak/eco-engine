<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\Comparator\ListAttributesComparator;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class ListAttributeFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class ListAttributeFilter implements FilterInterface
{
    /** @var ListAttribute */
    private $attribute;

    /** @var string */
    private $comparison;

    /**
     * ListAttributeFilter constructor.
     * @param ListAttribute $attribute
     * @param string $comparison
     */
    public function __construct(ListAttribute $attribute, string $comparison = ListAttributesComparator::STRICT)
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
        $name = $this->attribute->getName();
        foreach ($items as $item) {
            try {
                $attr = $item->getProduct()->getAttributeByName($name);
            } catch (AttributeNotFoundException $e) {
                continue;
            }

            if (!($attr instanceof ListAttribute)) {
                continue;
            }

            if (ListAttributesComparator::compare($attr, $this->comparison, $this->attribute)) {
                $result[] = $item;
            }
        }

        return $result;
    }
}