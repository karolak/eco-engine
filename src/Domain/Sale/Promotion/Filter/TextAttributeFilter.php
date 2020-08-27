<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Filter;

use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;

/**
 * Class TextAttributeFilter
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Filter
 */
class TextAttributeFilter implements FilterInterface
{
    const STRICT = 'strict';
    const CASE_INSENSITIVE = 'case_insensitive';
    const CONTAINS = 'contains';
    const CONTAINS_CASE_INSENSITIVE = 'contains_case_insensitive';

    /** @var TextAttribute */
    private $attribute;

    /** @var string */
    private $comparison;

    /**
     * TextAttributeFilter constructor.
     * @param TextAttribute $attribute
     * @param string $comparison
     */
    public function __construct(TextAttribute $attribute, string $comparison = self::STRICT)
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
     * @param string $a
     * @param string $comparison
     * @param string $b
     * @return bool
     */
    private function check(string $a, string $comparison, string $b)
    {
        $result = false;
        switch ($comparison) {
            case self::STRICT:
                $result = $a == $b;
                break;
            case self::CASE_INSENSITIVE:
                $result = strcasecmp($a, $b) === 0;
                break;
            case self::CONTAINS:
                $result = empty($b) || mb_strpos($a, $b) !== false;
                break;
            case self::CONTAINS_CASE_INSENSITIVE:
                $result = empty($b) || mb_stripos($a, $b) !== false;
                break;
        }

        return $result;
    }
}