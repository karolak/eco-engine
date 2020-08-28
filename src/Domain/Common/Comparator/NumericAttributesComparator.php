<?php
namespace Karolak\EcoEngine\Domain\Common\Comparator;

use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;

/**
 * Class NumericAttributesComparator
 * @package Karolak\EcoEngine\Domain\Common\Comparator
 */
class NumericAttributesComparator
{
    const EQUALS = '=';
    const EQUALS_OR_HIGHER = '>=';
    const EQUALS_OR_LOWER = '<=';
    const HIGHER = '>';
    const LOWER = '<';

    /**
     * @param NumericAttribute $toCheck
     * @param string $comparison
     * @param NumericAttribute $toCompare
     * @return bool
     */
    public static function compare(NumericAttribute $toCheck, string $comparison, NumericAttribute $toCompare): bool
    {
        $result = false;
        $a = $toCheck->getValue();
        $b = $toCompare->getValue();

        switch ($comparison) {
            case self::EQUALS:
                $result = $toCheck->equals($toCompare);
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