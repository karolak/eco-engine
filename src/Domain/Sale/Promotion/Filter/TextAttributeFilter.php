<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\Comparator\TextAttributesComparator;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class TextAttributeFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class TextAttributeFilter implements FilterInterface
{
    /** @var TextAttribute */
    private $attribute;

    /** @var string */
    private $comparison;

    /**
     * TextAttributeFilter constructor.
     * @param TextAttribute $attribute
     * @param string $comparison
     */
    public function __construct(TextAttribute $attribute, string $comparison = TextAttributesComparator::STRICT)
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

            if (!($attr instanceof TextAttribute)) {
                continue;
            }

            if (TextAttributesComparator::compare($attr, $this->comparison, $this->attribute)) {
                $result[] = $item;
            }
        }

        return $result;
    }
}