<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Model;

use Karolak\EcoEngine\Domain\Sale\Model\OrderItem;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderItemTest.
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Model
 */
class OrderItemTest extends TestCase
{
    /** @var OrderItem */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new OrderItem();
    }

    /**
     * @test
     */
    public function Should_CreateOrderItem()
    {
        // Assert
        $this->assertInstanceOf(OrderItem::class, $this->obj);
    }
}