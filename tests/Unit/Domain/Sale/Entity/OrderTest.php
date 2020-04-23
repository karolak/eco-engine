<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Entity;

use Karolak\EcoEngine\Domain\Sale\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Entity
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
    public function Should_AddOneProduct()
    {
        // Arrange
        $wasEmptyBefore = $this->obj->isEmpty();
        $totalQuantityBefore = $this->obj->getTotalProductsQuantity();
        $product = new Product("1");

        // Act
        $this->obj->addProduct($product);

        $isEmptyNow = $this->obj->isEmpty();
        $totalQuantityAfter = $this->obj->getTotalProductsQuantity();

        // Assert
        $this->assertTrue($wasEmptyBefore);
        $this->assertFalse($isEmptyNow);
        $this->assertEquals(0, $totalQuantityBefore);
        $this->assertEquals(1, $totalQuantityAfter);

    }

    /**
     * @test
     */
    public function Should_AddProductWithQuantity()
    {
        // Arrange
        $wasEmptyBefore = $this->obj->isEmpty();
        $totalQuantityBefore = $this->obj->getTotalProductsQuantity();
        $product = new Product("1");

        // Act
        $this->obj->addProduct($product, 4);

        $isEmptyNow = $this->obj->isEmpty();
        $totalQuantityAfter = $this->obj->getTotalProductsQuantity();

        // Assert
        $this->assertTrue($wasEmptyBefore);
        $this->assertFalse($isEmptyNow);
        $this->assertEquals(0, $totalQuantityBefore);
        $this->assertEquals(4, $totalQuantityAfter);
    }

    /**
     * @test
     */
    public function Should_AddSameProductTwice()
    {
        // Arrange
        $product = new Product("1");
        $wasEmptyBefore = $this->obj->isEmpty();
        $totalQuantityBefore = $this->obj->getTotalProductsQuantity();

        // Act
        $this->obj->addProduct($product);
        $this->obj->addProduct($product);

        $isEmptyNow = $this->obj->isEmpty();
        $totalQuantityAfter = $this->obj->getTotalProductsQuantity();

        // Assert
        $this->assertTrue($wasEmptyBefore);
        $this->assertFalse($isEmptyNow);
        $this->assertEquals(0, $totalQuantityBefore);
        $this->assertEquals(2, $totalQuantityAfter);
    }

    /**
     * @test
     */
    public function Should_AddSameProductTwice_When_AddWithDifferentQuantity()
    {
        // Arrange
        $product = new Product("1");
        $wasEmptyBefore = $this->obj->isEmpty();
        $totalQuantityBefore = $this->obj->getTotalProductsQuantity();

        // Act
        $this->obj->addProduct($product, 1);
        $this->obj->addProduct($product, 2);

        $isEmptyNow = $this->obj->isEmpty();
        $totalQuantityAfter = $this->obj->getTotalProductsQuantity();

        // Assert
        $this->assertTrue($wasEmptyBefore);
        $this->assertFalse($isEmptyNow);
        $this->assertEquals(0, $totalQuantityBefore);
        $this->assertEquals(3, $totalQuantityAfter);
    }

    /**
     * @test
     */
    public function Should_ReturnEmptyArrayOfItems_When_IsEmpty()
    {
        // Act
        $items = $this->obj->getItems();

        // Assert
        $this->assertTrue($this->obj->isEmpty());
        $this->assertEmpty($items);
        $this->assertIsArray($items);
    }

    /**
     * @test
     */
    public function Should_ReturnOneItem_When_AddOneProduct()
    {
        // Arrange
        $product = new Product("1");
        $this->obj->addProduct($product);

        // Act
        $items = $this->obj->getItems();

        // Assert
        $this->assertCount(1, $items);
    }

    /**
     * @test
     */
    public function Should_ReturnTwoItems_When_AddTwoDifferentProducts()
    {
        // Arrange
        $product1 = new Product("1");
        $product2 = new Product("2");
        $this->obj->addProduct($product1);
        $this->obj->addProduct($product2);

        // Act
        $items = $this->obj->getItems();

        // Assert
        $this->assertCount(2, $items);
    }

    /**
     * @test
     */
    public function Should_ReturnOneItem_When_AddSameProductTwice()
    {
        // Arrange
        $product = new Product("1");
        $this->obj->addProduct($product);
        $this->obj->addProduct($product);

        // Act
        $items = $this->obj->getItems();

        // Assert
        $this->assertCount(1, $items);
    }
}