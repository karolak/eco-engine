<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\ValueObject;

use Karolak\EcoEngine\Domain\Common\ValueObject\ValueObjectInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;

/**
 * Class PromotionProduct
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\ValueObject
 */
class PromotionProduct implements ValueObjectInterface
{
    /** @var string */
    private $id;

    /** @var int */
    private $price;

    /** @var int */
    private $limit;

    /** @var ConditionInterface */
    private $condition;

    /**
     * PromotionProduct constructor.
     * @param string $id
     * @param int $price
     * @param int $limit
     * @param ?ConditionInterface $condition
     */
    public function __construct(string $id, int $price, int $limit = 1, ?ConditionInterface $condition = null)
    {
        $this->id = $id;
        $this->price = $price;
        $this->limit = $limit;
        $this->condition = $condition !== null ? $condition : new EmptyCondition();
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return ConditionInterface
     */
    public function getCondition(): ConditionInterface
    {
        return $this->condition;
    }

    /**
     * @param ValueObjectInterface $object
     * @return bool
     */
    public function equals(ValueObjectInterface $object): bool
    {
        return ($object instanceof PromotionProduct) && $object->getId() === $this->id;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function __toString(): string
    {
        return (string) $this->id;
    }
}