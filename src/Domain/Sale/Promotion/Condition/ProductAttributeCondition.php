<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Comparator\AttributesComparator;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\AttributeInterface;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;

/**
 * Class ProductAttributeCondition
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Condition
 */
class ProductAttributeCondition implements ConditionInterface
{
    /** @var AttributeInterface */
    private $attribute;

    /** @var bool */
    private $strict;

    /**
     * ProductAttributeCondition constructor.
     * @param AttributeInterface $attribute
     * @param bool $strict
     */
    public function __construct(AttributeInterface $attribute, bool $strict = true)
    {
        $this->attribute = $attribute;
        $this->strict = $strict;
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

            if (AttributesComparator::compare($attr, $this->attribute, $this->strict)) {
                $result = true;
                break;
            }
        }

        return $result;
    }
}