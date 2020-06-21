<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Entity;

use Karolak\EcoEngine\Domain\Common\Exception\InvalidEmailException;
use Karolak\EcoEngine\Domain\Common\ValueObject\Country;
use Karolak\EcoEngine\Domain\Common\ValueObject\Email;
use Karolak\EcoEngine\Domain\Common\ValueObject\GeoPoint;
use Karolak\EcoEngine\Domain\Common\ValueObject\HomeAddress;
use Karolak\EcoEngine\Domain\Common\ValueObject\PickupPointAddress;
use Karolak\EcoEngine\Domain\Sale\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Exception\ProductNotFoundException;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Customer;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Invoice;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Payment;
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
        $first = reset($items);
        $second = end($items);

        // Assert
        $this->assertCount(2, $items);
        $this->assertFalse($first->equals($second));
    }

    /**
     * @test
     */
    public function Should_ReturnTwoItems_When_AddSameProductTwice()
    {
        // Arrange
        $product = new Product("1");
        $this->obj->addProduct($product);
        $this->obj->addProduct($product);

        // Act
        $items = $this->obj->getItems();

        // Assert
        $this->assertCount(2, $items);
    }

    /**
     * @test
     * @throws ProductNotFoundException
     */
    public function Should_RemoveProduct()
    {
        // Arrange
        $product1 = new Product("1");
        $product2 = new Product("2");
        $this->obj->addProduct($product1);
        $this->obj->addProduct($product2);

        // Act
        $this->obj->removeProduct($product1);

        // Assert
        $this->assertEquals(1, $this->obj->getTotalProductsQuantity());
    }

    /**
     * @test
     */
    public function Should_ThrowException_When_RemoveMissingProduct()
    {
        // Assert
        $this->expectException(ProductNotFoundException::class);

        // Arrange
        $product = new Product("1");
        $missingProduct = new Product("2");
        $this->obj->addProduct($product);

        // Act
        $this->obj->removeProduct($missingProduct);
    }

    /**
     * @test
     */
    public function Should_ThrowException_When_RemoveProductFromEmptyOrder()
    {
        // Assert
        $this->assertTrue($this->obj->isEmpty());
        $this->expectException(ProductNotFoundException::class);

        // Arrange
        $product = new Product("1");

        // Act
        $this->obj->removeProduct($product);
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

    /**
     * @test
     */
    public function Should_SetPayment()
    {
        // Arrange
        $payment = new Payment('cod');

        // Act
        $this->obj->setPayment($payment);

        // Assert
        $this->assertTrue($payment->equals($this->obj->getPayment()));
    }

    /**
     * @test
     */
    public function Should_SetCustomer()
    {
        // Arrange
        $customer = new Customer(
            'Test',
            'Testowy',
            '123123123',
            new Email('test@test.pl'),
            new HomeAddress(
                new Country('PL', 'Polska'),
                '',
                'Warszawa',
                '00-000',
                'testowa',
                '12',
                ''
            )
        );

        // Act
        $this->obj->setCustomer($customer);

        // Assert
        $this->assertTrue($customer->equals($this->obj->getCustomer()));
    }

    /**
     * @test
     * @dataProvider invalidEmailsDataProvider
     * @param string $email
     */
    public function Should_ThrowException_When_SetCustomerWithInvalidEmail(string $email)
    {
        // Assert
        $this->expectException(InvalidEmailException::class);

        // Arrange
        $customer = new Customer(
            'Test',
            'Testowy',
            '123123123',
            new Email($email),
            new HomeAddress(
                new Country('PL', 'Polska'),
                '',
                'Warszawa',
                '00-000',
                'testowa',
                '12',
                ''
            )
        );

        // Act
        $this->obj->setCustomer($customer);
    }

    /**
     * @test
     */
    public function Should_SetInvoice()
    {
        // Arrange
        $invoice = new Invoice(
            'CompanyName',
            '1231231231',
            new HomeAddress(
                new Country('PL', 'Polska'),
                '',
                'Warszawa',
                '00-000',
                'testowa',
                '12',
                ''
            )
        );

        // Act
        $this->obj->setInvoice($invoice);

        // Assert
        $this->assertTrue($invoice->equals($this->obj->getInvoice()));
    }

    /**
     * @return array
     */
    public function invalidEmailsDataProvider()
    {
        return [
            ['test'],
            ['test@'],
            ['test@test'],
            ['@test.pl'],
            ['@test'],
            ['test@test.']
        ];
    }
}