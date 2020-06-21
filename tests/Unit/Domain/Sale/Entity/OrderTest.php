<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Entity;

use Karolak\EcoEngine\Domain\Common\ValueObject\Country;
use Karolak\EcoEngine\Domain\Common\ValueObject\GeoPoint;
use Karolak\EcoEngine\Domain\Common\ValueObject\HomeAddress;
use Karolak\EcoEngine\Domain\Common\ValueObject\PickupPointAddress;
use Karolak\EcoEngine\Domain\Sale\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Exception\InvalidItemQuantityException;
use Karolak\EcoEngine\Domain\Sale\Exception\ProductNotFoundException;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Shipment;
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
     * @throws InvalidItemQuantityException
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
     * @throws InvalidItemQuantityException
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
    public function Should_TrowException_When_AddProductWithInvalidQuantity()
    {
        // Assert
        $this->expectException(InvalidItemQuantityException::class);

        // Arrange
        $product = new Product("1");

        // Act
        $this->obj->addProduct($product, 0);
    }

    /**
     * @test
     * @throws InvalidItemQuantityException
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
     * @throws InvalidItemQuantityException
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
     * @throws InvalidItemQuantityException
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
     * @throws InvalidItemQuantityException
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
        $first = reset($items);
        $second = end($items);

        // Assert
        $this->assertCount(2, $items);
        $this->assertFalse($first->equals($second));
    }

    /**
     * @test
     * @throws InvalidItemQuantityException
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

    /**
     * @test
     * @throws InvalidItemQuantityException
     * @throws ProductNotFoundException
     */
    public function Should_ChangeProductQuantity()
    {
        // Arrange
        $product1 = new Product("1");
        $product2 = new Product("2");
        $this->obj->addProduct($product1, 1);
        $this->obj->addProduct($product2, 1);

        // Act
        $this->obj->changeProductQuantity($product2, 3);

        // Assert
        $this->assertEquals(4, $this->obj->getTotalProductsQuantity());
    }

    /**
     * @test
     * @throws InvalidItemQuantityException
     * @throws ProductNotFoundException
     */
    public function Should_ThrowException_When_ChangeProductQuantityToInvalid()
    {
        // Assert
        $this->expectException(InvalidItemQuantityException::class);

        // Arrange
        $product = new Product("1");
        $this->obj->addProduct($product, 1);

        // Act
        $this->obj->changeProductQuantity($product, 0);
    }

    /**
     * @test
     * @throws InvalidItemQuantityException
     */
    public function Should_ThrowException_When_ChangeMissingProductQuantity()
    {
        // Assert
        $this->expectException(ProductNotFoundException::class);

        // Arrange
        $product = new Product("1");
        $missingProduct = new Product("2");
        $this->obj->addProduct($product, 1);

        // Act
        $this->obj->changeProductQuantity($missingProduct, 3);
    }

    /**
     * @test
     * @throws InvalidItemQuantityException
     * @throws ProductNotFoundException
     */
    public function Should_RemoveProduct()
    {
        // Arrange
        $product1 = new Product("1");
        $product2 = new Product("2");
        $this->obj->addProduct($product1, 1);
        $this->obj->addProduct($product2, 1);

        // Act
        $this->obj->removeProduct($product1);

        // Assert
        $this->assertEquals(1, $this->obj->getTotalProductsQuantity());
    }

    /**
     * @test
     * @throws InvalidItemQuantityException
     */
    public function Should_ThrowException_When_RemoveMissingProduct()
    {
        // Assert
        $this->expectException(ProductNotFoundException::class);

        // Arrange
        $product = new Product("1");
        $missingProduct = new Product("2");
        $this->obj->addProduct($product, 1);

        // Act
        $this->obj->removeProduct($missingProduct);
    }

    /**
     * @test
     */
    public function Should_SetHomeShipment()
    {
        // Arrange
        $address = new HomeAddress(
            new Country('PL', 'Polska'),
            '',
            'Warszawa',
            '00-000',
            'testowa',
            '12',
            ''
        );
        $shipment = new Shipment('ups', $address);

        // Act
        $this->obj->setShipment($shipment);

        // Assert
        $this->assertTrue($shipment->equals($this->obj->getShipment()));
    }

    /**
     * @test
     */
    public function Should_SetPickupShipment()
    {
        // Arrange
        $address = new PickupPointAddress(
            '12345',
            'Normal access point',
            new GeoPoint(52.123, 38.123)
        );
        $shipment = new Shipment('ups_access_point', $address);

        // Act
        $this->obj->setShipment($shipment);

        // Assert
        $this->assertTrue($shipment->equals($this->obj->getShipment()));
    }
}