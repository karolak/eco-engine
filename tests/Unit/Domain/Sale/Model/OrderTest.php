<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Model;

use Karolak\EcoEngine\Domain\Sale\Collection\ItemsCollection;
use Karolak\EcoEngine\Domain\Sale\Model\Item;
use Karolak\EcoEngine\Domain\Sale\Model\Order;
use Karolak\EcoEngine\Domain\Sale\Model\Product;
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
        $productId = '1';
        $isEmpty = $this->obj->isEmpty();

        // Act
        $this->obj->addItem($this->createDefaultItem($productId));

        // Assert
        $this->assertTrue($isEmpty);
        $this->assertTrue($this->obj->hasProduct($productId));
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_AddItem_When_OrderIsNotEmpty()
    {
        // Arrange
        $productId = '2';
        $this->obj->addItem($this->createDefaultItem('1'));
        $isEmpty = $this->obj->isEmpty();

        // Act
        $this->obj->addItem($this->createDefaultItem($productId));

        // Assert
        $this->assertFalse($isEmpty);
        $this->assertTrue($this->obj->hasProduct($productId));
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_RemoveItem_When_IsTheOnlyItemInOrder()
    {
        // Arrange
        $productId = '1';
        $this->obj->addItem($this->createDefaultItem($productId));

        // Act
        $this->obj->removeProduct($productId);

        // Assert
        $this->assertFalse($this->obj->hasProduct($productId));
        $this->assertTrue($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_RemoveItem_When_IsNotTheOnlyItemInOrder()
    {
        // Arrange
        $productIdToRemove = '1';
        $productIdNotToRemove = '2';
        $this->obj->addItem($this->createDefaultItem($productIdToRemove));
        $this->obj->addItem($this->createDefaultItem($productIdNotToRemove));

        // Act
        $this->obj->removeProduct($productIdToRemove);

        // Assert
        $this->assertFalse($this->obj->hasProduct($productIdToRemove));
        $this->assertTrue($this->obj->hasProduct($productIdNotToRemove));
    }

    /**
     * @test
     */
    public function Should_DoNothing_When_RemoveNoExistingItem()
    {
        // Arrange
        $hasProduct = $this->obj->hasProduct('12345');

        // Act
        $this->obj->removeProduct('12345');

        // Arrange
        $this->assertFalse($hasProduct);
    }

    /**
     * @test
     */
    public function Should_ReturnItemsCollection_When_NotEmptyOrder()
    {
        // Arrange
        $this->obj->addItem($this->createDefaultItem('1'));

        // Act
        $result = $this->obj->getItems();

        // Assert
        $this->assertInstanceOf(ItemsCollection::class, $result);
        $this->assertFalse($result->isEmpty());
    }

    /**
     * @test
     */
    public function Should_ReturnEmptyItemsCollection_When_OrderIsEmpty()
    {
        // Act
        $result = $this->obj->getItems();

        // Assert
        $this->assertInstanceOf(ItemsCollection::class, $result);
        $this->assertTrue($result->isEmpty());
        $this->assertTrue($this->obj->isEmpty());
    }

    /**
     * @param string $productId
     * @return Item
     */
    private function createDefaultItem(string $productId)
    {
        return new Item(new Product($productId));
    }
}