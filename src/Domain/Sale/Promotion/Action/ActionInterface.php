<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;

/**
 * Interface ActionInterface
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Action
 */
interface ActionInterface
{
    /**
     * @return ConditionInterface
     */
    public function getCondition(): ConditionInterface;
}