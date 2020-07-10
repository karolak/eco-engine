<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Entity;

use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Collection\ActionsCollection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;

/**
 * Class Promotion
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Promotion\Entity
 */
class Promotion
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var ConditionInterface */
    private $condition;

    /** @var ActionsCollection|ActionInterface[] */
    private $actions;

    /**
     * Promotion constructor.
     * @param string $name
     * @param string $type
     */
    public function __construct(string $name, string $type = '')
    {
        $this->name = $name;
        $this->type = $type;
        $this->condition = new EmptyCondition();
        $this->actions = new ActionsCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return ConditionInterface
     */
    public function getCondition(): ConditionInterface
    {
        return $this->condition;
    }

    /**
     * @param ConditionInterface $condition
     */
    public function setCondition(ConditionInterface $condition)
    {
        $this->condition = $condition;
    }

    /**
     * @param ActionInterface $action
     */
    public function addAction(ActionInterface $action)
    {
        $this->actions->add($action);
    }

    /**
     * @return ActionInterface[]
     */
    public function getActions(): array
    {
        return $this->actions->toArray();
    }
}