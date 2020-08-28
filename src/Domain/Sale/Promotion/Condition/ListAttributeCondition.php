<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\ListAttributesComparator;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class ListAttributeCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class ListAttributeCondition implements ConditionInterface
{
    /** @var ListAttribute */
    private $attribute;

    /** @var string */
    private $comparison;

    /**
     * ListAttributeCondition constructor.
     * @param ListAttribute $attribute
     * @param string $comparison
     */
    public function __construct(ListAttribute $attribute, string $comparison = ListAttributesComparator::STRICT)
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

            if (!($attr instanceof ListAttribute)) {
                continue;
            }

            if (ListAttributesComparator::compare($attr, $this->comparison, $this->attribute)) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}