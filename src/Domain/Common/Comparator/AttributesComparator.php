<?php
namespace Karolak\EcoEngine\Domain\Common\Comparator;

use Karolak\EcoEngine\Domain\Common\ValueObject\AttributeInterface;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;

/**
 * Class AttributesComparator
 * @package Karolak\EcoEngine\Domain\Common\Comparator
 */
class AttributesComparator
{
    /**
     * @param AttributeInterface $toCheck
     * @param AttributeInterface $toCompare
     * @param bool $strict
     * @return bool
     */
    public static function compare(AttributeInterface $toCheck, AttributeInterface $toCompare, bool $strict = true): bool
    {
        if ($strict && $toCheck->equals($toCompare)) {
            return true;
        }

        if ($toCompare instanceof TextAttribute && $toCheck instanceof TextAttribute) {
            return (bool) mb_stripos($toCheck->getValue(), $toCompare->getValue());
        }

        if ($toCompare instanceof NumericAttribute && $toCheck instanceof NumericAttribute) {
            return $toCheck->equals($toCompare);
        }

        if ($toCompare instanceof ListAttribute && $toCheck instanceof ListAttribute) {
            return !empty(array_intersect($toCheck->getValue(), $toCompare->getValue()));
        }

        return false;
    }
}