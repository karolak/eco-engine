<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\Comparator\AttributesComparator;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\AttributeInterface;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class ProductAttributeFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class ProductAttributeFilter implements FilterInterface
{
    /** @var AttributeInterface */
    private $attribute;

    /** @var bool */
    private $strict;

    /**
     * ProductAttributeFilter constructor.
     * @param AttributeInterface $attribute
     * @param bool $strict
     */
    public function __construct(AttributeInterface $attribute, bool $strict = true)
    {
        $this->attribute = $attribute;
        $this->strict = $strict;
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

        $name = $this->attribute->getName();
        $result = [];
        foreach ($items as $item) {
            try {
                $attr = $item->getProduct()->getAttributeByName($name);
            } catch (AttributeNotFoundException $e) {
                continue;
            }

            if (AttributesComparator::compare($attr, $this->attribute, $this->strict)) {
                $result[] = $item;
            }
        }

        return $result;
    }
}