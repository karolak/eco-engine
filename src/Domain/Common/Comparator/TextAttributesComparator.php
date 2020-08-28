<?php
namespace Karolak\EcoEngine\Domain\Common\Comparator;

use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;

/**
 * Class TextAttributesComparator
 * @package Karolak\EcoEngine\Domain\Common\Comparator
 */
class TextAttributesComparator
{
    const STRICT = 'strict';
    const CASE_INSENSITIVE = 'case_insensitive';
    const CONTAINS = 'contains';
    const CONTAINS_CASE_INSENSITIVE = 'contains_case_insensitive';

    /**
     * @param TextAttribute $toCheck
     * @param string $comparison
     * @param TextAttribute $toCompare
     * @return bool
     */
    public static function compare(TextAttribute $toCheck, string $comparison, TextAttribute $toCompare): bool
    {
        $result = false;
        $a = $toCheck->getValue();
        $b = $toCompare->getValue();

        switch ($comparison) {
            case self::STRICT:
                $result = $toCheck->equals($toCompare);
                break;
            case self::CASE_INSENSITIVE:
                $result = strcasecmp($a, $b) === 0;
                break;
            case self::CONTAINS:
                $result = !empty($b) && mb_strpos($a, $b) !== false;
                break;
            case self::CONTAINS_CASE_INSENSITIVE:
                $result = !empty($b) && mb_stripos($a, $b) !== false;
                break;
        }

        return $result;
    }
}