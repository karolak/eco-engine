<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Condition;

use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\ValueObject\AttributeInterface;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
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

            if ($this->checkAttribute($attr)) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * @param AttributeInterface $attr
     * @return bool
     */
    private function checkAttribute(AttributeInterface $attr): bool
    {
        if ($this->strict && $attr->equals($this->attribute)) {
            return true;
        }

        if ($this->attribute instanceof TextAttribute && $attr instanceof TextAttribute) {
            return (bool) mb_stripos($attr->getValue(), $this->attribute->getValue());
        }

        if ($this->attribute instanceof NumericAttribute && $attr instanceof NumericAttribute) {
            return $attr->equals($this->attribute);
        }

        if ($this->attribute instanceof ListAttribute && $attr instanceof ListAttribute) {
            return !empty(array_intersect($attr->getValue(), $this->attribute->getValue()));
        }

        return false;
    }
}