<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Model;

use Karolak\EcoEngine\Domain\Sale\Collection\OrderItemsCollection;
use Karolak\EcoEngine\Domain\Sale\Model\Order;
use Karolak\EcoEngine\Domain\Sale\Model\OrderItem;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderTest.
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Model
 */
class OrderTest extends TestCase
{
    /** @var Order */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new Order();
    }

    /**
     * @test
     */
    public function Should_CreateOrder()
    {
        // Assert
        $this->assertInstanceOf(Order::class, $this->obj);
    }

    /**
     * @test
     */
    public function Should_AddItem_When_OrderIsEmpty()
    {
        // Arrange
        $orderItem = new OrderItem();
        $isEmpty = $this->obj->isEmpty();

        // Act
        $this->obj->addItem($orderItem);

        // Assert
        $this->assertTrue($isEmpty);
        $this->assertTrue($this->obj->hasItem($orderItem));
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_AddItem_When_OrderIsNotEmpty()
    {
        // Arrange
        $orderItem = new OrderItem();
        $this->obj->addItem(new OrderItem());
        $isEmpty = $this->obj->isEmpty();

        // Act
        $this->obj->addItem($orderItem);

        // Assert
        $this->assertFalse($isEmpty);
        $this->assertTrue($this->obj->hasItem($orderItem));
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_RemoveItem_When_IsTheOnlyItemInOrder()
    {
        // Arrange
        $orderItem = new OrderItem();
        $this->obj->addItem($orderItem);

        // Act
        $result = $this->obj->removeItem($orderItem);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($this->obj->hasItem($orderItem));
        $this->assertTrue($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_RemoveItem_When_IsNotTheOnlyItemInOrder()
    {
        // Arrange
        $orderItemToRemove = new OrderItem();
        $orderItemNotToRemove = new OrderItem();
        $this->obj->addItem($orderItemToRemove);
        $this->obj->addItem($orderItemNotToRemove);

        // Act
        $result = $this->obj->removeItem($orderItemToRemove);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($this->obj->hasItem($orderItemToRemove));
        $this->assertTrue($this->obj->hasItem($orderItemNotToRemove));
    }

    /**
     * @test
     */
    public function Should_ReturnItems_When_NotEmptyOrder()
    {
        // Arrange
        $orderItem = new OrderItem();
        $this->obj->addItem($orderItem);

        // Act
        $result = $this->obj->getItems();

        // Assert
        $this->assertInstanceOf(OrderItemsCollection::class, $result);
        $this->assertTrue($result->has($orderItem));
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_ReturnItems_When_OrderIsEmpty()
    {
        // Act
        $result = $this->obj->getItems();

        // Assert
        $this->assertInstanceOf(OrderItemsCollection::class, $result);
        $this->assertTrue($result->isEmpty());
        $this->assertTrue($this->obj->isEmpty());
    }
}