<?php
namespace Karolak\EcoEngine\Domain\Sale\Model;

/**
 * Class Product
 * @package Karolak\EcoEngine\Domain\Sale\Model
 */
class Product
{
    /** @var string */
    private $id;

    /** @var int */
    private $price;

    /**
     * Product constructor.
     * @param string $id
     * @param int $price
     */
    public function __construct(string $id, int $price = 0)
    {
        $this->id = $id;
        $this->price = $price;
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
    public function getPrice(): float
    {
        return $this->price;
    }
}