<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\Comparator\NumericAttributesComparator;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class NumericAttributeFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class NumericAttributeFilter implements FilterInterface
{
    /** @var NumericAttribute */
    private $attribute;

    /** @var string */
    private $comparison;

    /**
     * NumericAttributeFilter constructor.
     * @param NumericAttribute $attribute
     * @param string $comparison
     */
    public function __construct(NumericAttribute $attribute, string $comparison = NumericAttributesComparator::EQUALS)
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

            if (!($attr instanceof NumericAttribute)) {
                continue;
            }

            if (NumericAttributesComparator::compare($attr, $this->comparison, $this->attribute)) {
                $result[] = $item;
            }
        }

        return $result;
    }
}