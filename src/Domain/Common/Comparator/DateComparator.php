<?php
namespace Karolak\EcoEngine\Domain\Common\Comparator;

use DateTimeImmutable;

/**
 * Class DateComparator
 * @package Karolak\EcoEngine\Domain\Common\Comparator
 */
class DateComparator
{
    const SAME = '=';
    const SAME_OR_LATEST = '>=';
    const SAME_OR_OLDER = '<=';
    const LATEST = '>';
    const OLDER = '<';

    /**
     * @param DateTimeImmutable $a
     * @param string $comparison
     * @param DateTimeImmutable $b
     * @return bool
     */
    public static function compare(DateTimeImmutable $a, string $comparison, DateTimeImmutable $b): bool
    {
        $result = false;
        switch ($comparison) {
            case self::SAME:
                $result = $a == $b;
                break;
            case self::SAME_OR_LATEST:
                $result = $a >= $b;
                break;
            case self::SAME_OR_OLDER:
                $result = $a <= $b;
                break;
            case self::LATEST:
                $result = $a > $b;
                break;
            case self::OLDER:
                $result = $a < $b;
                break;
        }

        return $result;
    }
}