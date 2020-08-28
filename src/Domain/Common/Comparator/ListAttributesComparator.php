<?php
namespace Karolak\EcoEngine\Domain\Common\Comparator;

use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;

/**
 * Class ListAttributesComparator
 * @package Karolak\EcoEngine\Domain\Common\Comparator
 */
class ListAttributesComparator
{
    const STRICT = 'strict';
    const IN = 'in';

    /**
     * @param ListAttribute $toCheck
     * @param string $comparison
     * @param ListAttribute $toCompare
     * @return bool
     */
    public static function compare(ListAttribute $toCheck, string $comparison, ListAttribute $toCompare): bool
    {
        $result = false;
        $a = $toCheck->getValue();
        $b = $toCompare->getValue();

        switch ($comparison) {
            case self::STRICT:
                $result = $toCheck->equals($toCompare);
                break;
            case self::IN:
                $result = !empty(array_intersect($a, $b));
                break;
        }

        return $result;
    }
}