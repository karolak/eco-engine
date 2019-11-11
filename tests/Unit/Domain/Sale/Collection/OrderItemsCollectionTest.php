<?php
namespace Karolak\EcoEngine\Test\Unit\Infrastructure\Sale\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Collection\OrderItemsCollection;
use Karolak\EcoEngine\Domain\Sale\Model\OrderItem;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderItemsArrayCollectionTest.
 * @package Karolak\EcoEngine\Test\Unit\Infrastructure\Sale\Collection
 */
class OrderItemsArrayCollectionTest extends TestCase
{
    /** @var OrderItemsCollection */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new OrderItemsCollection();
    }

    /**
     * @test
     */
    public function Should_ExtendCollection()
    {
        // Assert
        $this->assertInstanceOf(Collection::class, $this->obj);
    }

    /**
     * @test
     */
    public function Should_AddOrderItem()
    {
        // Arrange
        $orderItem = new OrderItem();

        // Act
        $this->obj->add($orderItem);

        // Assert
        $this->assertTrue($this->obj->has($orderItem));
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_RemoveOrderItem()
    {
        // Arrange
        $orderItem = new OrderItem();
        $this->obj->add($orderItem);

        // Act
        $result = $this->obj->remove($orderItem);

        // Assert
        $this->assertTrue($result);
        $this->assertTrue($this->obj->isEmpty());
        $this->assertFalse($this->obj->has($orderItem));
    }

    /**
     * @test
     */
    public function Should_NotRemoveOrderItemFromEmptyCollection()
    {
        // Arrange
        $orderItem = new OrderItem();

        // Act
        $result = $this->obj->remove($orderItem);

        // Assert
        $this->assertFalse($result);
        $this->assertTrue($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_NotRemoveAnyOrderItem_When_RemovingOrderItemNotInCollection()
    {
        // Arrange
        $orderItemToRemove = new OrderItem();
        $orderItemNotToRemove = new OrderItem();
        $this->obj->add($orderItemNotToRemove);

        // Act
        $result = $this->obj->remove($orderItemToRemove);

        // Assert
        $this->assertFalse($result);
        $this->assertFalse($this->obj->isEmpty());
        $this->assertTrue($this->obj->has($orderItemNotToRemove));
    }

    /**
     * @test
     */
    public function Should_ReturnOrderItemKey_When_OrderItemInCollection()
    {
        // Arrange
        $orderItem = new OrderItem();
        $this->obj->add($orderItem);

        // Act
        $result = $this->obj->keyOf($orderItem);

        // Assert
        $this->assertEquals(0, $result);
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_NotReturnOrderItemKey_When_EmptyCollection()
    {
        // Arrange
        $orderItem = new OrderItem();

        // Act
        $result = $this->obj->keyOf($orderItem);

        // Assert
        $this->assertNull($result);
        $this->assertTrue($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_NotReturnOrderItemKey_When_OrderItemNotFound()
    {
        // Arrange
        $orderItem = new OrderItem();
        $this->obj->add(new OrderItem());

        // Act
        $result = $this->obj->keyOf($orderItem);

        // Assert
        $this->assertNull($result);
        $this->assertFalse($this->obj->has($orderItem));
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_ReturnOrderItem_When_FoundByKey()
    {
        // Arrange
        $orderItem = new OrderItem();
        $this->obj->add($orderItem);

        // Act
        $result = $this->obj->get(0);

        // Assert
        $this->assertSame($orderItem, $result);
        $this->assertTrue($this->obj->has($orderItem));
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_NotReturnOrderItem_When_EmptyCollection()
    {
        // Act
        $result = $this->obj->get(1);

        // Assert
        $this->assertNull($result);
        $this->assertTrue($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_NotReturnOrderItem_When_NotFoundInNotEmptyCollection()
    {
        // Arrange
        $this->obj->add(new OrderItem());

        // Act
        $result = $this->obj->get(3);

        // Assert
        $this->assertNull($result);
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_SetOrderItem_WhenEmptyCollection()
    {
        // Arrange
        $index = 3;
        $orderItem = new OrderItem();

        // Act
        $this->obj->set($index, $orderItem);
        $result = $this->obj->get($index);

        // Assert
        $this->assertSame($orderItem, $result);
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_OverrideOrderItem()
    {
        // Arrange
        $index = 3;
        $orderItem = new OrderItem();
        $this->obj->set($index, new OrderItem());

        // Act
        $this->obj->set($index, $orderItem);
        $result = $this->obj->get($index);

        // Assert
        $this->assertSame($orderItem, $result);
    }
}