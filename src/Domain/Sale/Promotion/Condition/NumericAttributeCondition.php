<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\NumericAttributesComparator;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class NumericAttributeCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class NumericAttributeCondition implements ConditionInterface
{
    /** @var NumericAttribute */
    private $attribute;

    /** @var string */
    private $comparison;

    /**
     * NumericAttributeCondition constructor.
     * @param NumericAttribute $attribute
     * @param string $comparison
     */
    public function __construct(NumericAttribute $attribute, string $comparison = NumericAttributesComparator::EQUALS)
    {
        $this->attribute = $attribute;
        $this->comparison = $comparison;
    }

    /**
     * @param Order $order
     * @return bool
     */
    public function isSatisfiedBy(Order $order): bool
    {
        if ($order->isEmpty()) {
            return false;
        }

        $name = $this->attribute->getName();
        $items = $order->getItems();
        $result = false;
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
                $result = true;
                break;
            }
        }

        return $result;
    }
}