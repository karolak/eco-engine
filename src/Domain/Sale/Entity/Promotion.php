<?php
namespace Karolak\EcoEngine\Domain\Sale\Entity;

use Karolak\EcoEngine\Domain\Sale\Collection\PromotionActionsCollection;
use Karolak\EcoEngine\Domain\Sale\Collection\PromotionConditionsCollection;
use Karolak\EcoEngine\Domain\Sale\Exception\PromotionActionAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\Exception\PromotionConditionAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\ValueObject\PromotionAction;
use Karolak\EcoEngine\Domain\Sale\ValueObject\PromotionCondition;

/**
 * Class Promotion
 * @package Karolak\EcoEngine\Domain\Sale\Entity
 */
class Promotion
{
    /** @var string */
    private $name;

    /** @var PromotionConditionsCollection|PromotionCondition[] */
    private $promotionConditions;

    /** @var PromotionActionsCollection|PromotionAction[] */
    private $promotionActions;

    /**
     * Promotion constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->promotionConditions = new PromotionConditionsCollection();
        $this->promotionActions = new PromotionActionsCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param PromotionCondition $promotionCondition
     * @throws PromotionConditionAlreadyAddedException
     */
    public function addPromotionCondition(PromotionCondition $promotionCondition)
    {
        if ($this->promotionConditions->isEmpty()) {
            $this->promotionConditions->add($promotionCondition);
            return;
        }

        foreach ($this->promotionConditions as $p) {
            if ($promotionCondition->equals($p)) {
                throw new PromotionConditionAlreadyAddedException();
            }
        }

        $this->promotionConditions->add($promotionCondition);
    }

    /**
     * @param PromotionAction $promotionAction
     * @throws PromotionActionAlreadyAddedException
     */
    public function addPromotionAction(PromotionAction $promotionAction)
    {
        if ($this->promotionActions->isEmpty()) {
            $this->promotionActions->add($promotionAction);
            return;
        }

        foreach ($this->promotionActions as $a) {
            if ($promotionAction->equals($a)) {
                throw new PromotionActionAlreadyAddedException();
            }
        }

        $this->promotionActions->add($promotionAction);
    }

    /**
     * @return PromotionCondition[]
     */
    public function getPromotionConditions(): array
    {
        return $this->promotionConditions->toArray();
    }

    /**
     * @return PromotionAction[]
     */
    public function getPromotionActions(): array
    {
        return $this->promotionActions->toArray();
    }
}