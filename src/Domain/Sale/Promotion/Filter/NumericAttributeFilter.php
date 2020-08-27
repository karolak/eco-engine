<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class NumericAttributeFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class NumericAttributeFilter implements FilterInterface
{
    const EQUALS = '=';
    const EQUALS_OR_HIGHER = '>=';
    const EQUALS_OR_LOWER = '<=';
    const HIGHER = '>';
    const LOWER = '<';

    /** @var NumericAttribute */
    private $attribute;

    /** @var string */
    private $comparison;

    /**
     * NumericAttributeFilter constructor.
     * @param NumericAttribute $attribute
     * @param string $comparison
     */
    public function __construct(NumericAttribute $attribute, string $comparison = self::EQUALS)
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