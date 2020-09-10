<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Order\Entity;

use DateTimeImmutable;
use Karolak\EcoEngine\Domain\Common\Exception\AttributeNotFoundException;
use Karolak\EcoEngine\Domain\Common\Exception\InvalidEmailException;
use Karolak\EcoEngine\Domain\Common\ValueObject\Country;
use Karolak\EcoEngine\Domain\Common\ValueObject\Email;
use Karolak\EcoEngine\Domain\Common\ValueObject\GeoPoint;
use Karolak\EcoEngine\Domain\Common\ValueObject\HomeAddress;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\PickupPointAddress;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Adjustment;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Customer;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Invoice;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Payment;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Shipment;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Order\Entity
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
    public function Should_ReturnTotalZero_When_EmptyOrder()
    {
        $this->assertEquals(0, $this->obj->getTotalProductsQuantity());
        $this->assertEquals(0, $this->obj->getTotalProductsPrice());
        $this->assertEquals(0, $this->obj->getShipmentPrice());
        $this->assertEquals(0, $this->obj->getTotalPrice());
    }

    /**
     * @test
     * @throws InvalidPriceValueException|AttributeNotFoundException
     */
    public function Should_AddOneProduct()
    {
        // Arrange
        $wasEmptyBefore = $this->obj->isEmpty();
        $totalQuantityBefore = $this->obj->getTotalProductsQuantity();
        $totalPriceBefore = $this->obj->getTotalProductsPrice();
        $sizeAttribute = new TextAttribute('size', 'M');
        $totalAttribute = new NumericAttribute('total', 12345);
        $product = new Product('1', 100, [
            $sizeAttribute,
            $totalAttribute
        ]);

        // Act
        $this->obj->addProduct($product);

        $isEmptyNow = $this->obj->isEmpty();
        $totalQuantityAfter = $this->obj->getTotalProductsQuantity();
        $totalPriceAfter = $this->obj->getTotalProductsPrice();
        $item = $this->obj->getItems()[0];

        // Assert
        $this->assertTrue($wasEmptyBefore);
        $this->assertFalse($isEmptyNow);
        $this->assertEquals(0, $totalQuantityBefore);
        $this->assertEquals(1, $totalQuantityAfter);
        $this->assertEquals(0, $totalPriceBefore);
        $this->assertEquals(100, $totalPriceAfter);
        $this->assertEquals(0, $this->obj->getShipmentPrice());
        $this->assertEquals(100, $this->obj->getTotalPrice());
        $this->assertTrue($item->getProduct()->equals($product));
        $this->assertTrue($item->getProduct()->getAttributeByName('size')->equals($sizeAttribute));
        $this->assertTrue($item->getProduct()->getAttributeByName('total')->equals($totalAttribute));
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_AddSameProductTwice()
    {
        // Arrange
        $product = new Product('1', 100);
        $wasEmptyBefore = $this->obj->isEmpty();
        $totalQuantityBefore = $this->obj->getTotalProductsQuantity();
        $totalPriceBefore = $this->obj->getTotalProductsPrice();

        // Act
        $this->obj->addProduct($product);
        $this->obj->addProduct($product);

        $isEmptyNow = $this->obj->isEmpty();
        $totalQuantityAfter = $this->obj->getTotalProductsQuantity();
        $totalPriceAfter = $this->obj->getTotalProductsPrice();

        // Assert
        $this->assertTrue($wasEmptyBefore);
        $this->assertFalse($isEmptyNow);
        $this->assertEquals(0, $totalQuantityBefore);
        $this->assertEquals(2, $totalQuantityAfter);
        $this->assertEquals(0, $totalPriceBefore);
        $this->assertEquals(200, $totalPriceAfter);
        $this->assertEquals(0, $this->obj->getShipmentPrice());
        $this->assertEquals(200, $this->obj->getTotalPrice());
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
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnOneItem_When_AddOneProduct()
    {
        // Arrange
        $product = new Product('1', 100);
        $this->obj->addProduct($product);

        // Act
        $items = $this->obj->getItems();

        // Assert
        $this->assertCount(1, $items);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnTwoItems_When_AddTwoDifferentProducts()
    {
        // Arrange
        $product1 = new Product('1', 100);
        $product2 = new Product('2', 200);
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
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnTwoItems_When_AddSameProductTwice()
    {
        // Arrange
        $product = new Product('1', 100);
        $this->obj->addProduct($product);
        $this->obj->addProduct($product);

        // Act
        $items = $this->obj->getItems();

        // Assert
        $this->assertCount(2, $items);
    }

    /**
     * @test
     */
    public function Should_ThrowException_When_AddProductWithInvalidPriceValue()
    {
        // Assert
        $this->expectException(InvalidPriceValueException::class);

        // Arrange
        $product = new Product('1', -100);

        // Act
        $this->obj->addProduct($product);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ThrowException_When_ProductAttributeNotFound()
    {
        // Assert
        $this->expectException(AttributeNotFoundException::class);

        // Arrange
        $product = new Product('1', 100);

        // Act
        $product->getAttributeByName('test');
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     */
    public function Should_RemoveItem()
    {
        // Arrange
        $product1 = new Product('1', 100);
        $product2 = new Product('2', 200);
        $this->obj->addProduct($product1);
        $this->obj->addProduct($product2);
        $items = $this->obj->getItems();

        // Act
        $this->obj->removeItem(reset($items));

        // Assert
        $this->assertEquals(1, $this->obj->getTotalProductsQuantity());
        $this->assertEquals(200, $this->obj->getTotalProductsPrice());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ThrowException_When_RemoveSameItemTwice()
    {
        // Assert
        $this->expectException(ItemNotFoundException::class);

        // Arrange
        $product = new Product('1', 100);
        $this->obj->addProduct($product);
        $item = new Item($product);

        // Act
        $this->obj->removeItem($item);
        $this->obj->removeItem($item);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ThrowException_When_RemoveItemFromEmptyOrder()
    {
        // Assert
        $this->assertTrue($this->obj->isEmpty());
        $this->expectException(ItemNotFoundException::class);

        // Arrange
        $product = new Product('1', 100);

        // Act
        $this->obj->removeItem(new Item($product));
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     */
    public function Should_AddAdjustmentToOneItem()
    {
        // Arrange
        $product1 = new Product('1', 1000);
        $product2 = new Product('1', 2000);
        $this->obj->addProduct($product1);
        $this->obj->addProduct($product2);
        $adjustment = new Adjustment(-200, 'coupon', 'PROMO');
        $items = $this->obj->getItems();
        $item = reset($items);

        // Act
        $this->obj->addAdjustmentToItem($adjustment, $item);

        $items = $this->obj->getItems();
        /** @var Item $item */
        $item = reset($items);
        $itemAdjustments = $item->getAdjustments();

        // Assert
        $this->assertEquals(2800, $this->obj->getTotalPrice());
        $this->assertEquals(800, $item->getPrice());
        $this->assertTrue($adjustment->equals(end($itemAdjustments)));
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     */
    public function Should_AddAdjustmentToOneItemTwice()
    {
        // Arrange
        $product1 = new Product('1', 1000);
        $product2 = new Product('1', 2000);
        $this->obj->addProduct($product1);
        $this->obj->addProduct($product2);
        $adjustment = new Adjustment(-200, 'coupon', 'PROMO');

        // Act
        for ($i = 0; $i < 2; $i++) {
            $items = $this->obj->getItems();
            $item = reset($items);
            $this->obj->addAdjustmentToItem($adjustment, $item);
        }

        $items = $this->obj->getItems();
        /** @var Item $item */
        $item = reset($items);
        $itemAdjustments = $item->getAdjustments();

        // Assert
        $this->assertEquals(2600, $this->obj->getTotalPrice());
        $this->assertEquals(600, $item->getPrice());
        $this->assertCount(2, $itemAdjustments);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     */
    public function Should_AddAdjustmentToTwoItems()
    {
        // Arrange
        $product1 = new Product('1', 1000);
        $product2 = new Product('1', 2000);
        $this->obj->addProduct($product1);
        $this->obj->addProduct($product2);
        $adjustment = new Adjustment(-200, 'coupon', 'PROMO');
        $items = $this->obj->getItems();
        $item1 = reset($items);
        $item2 = end($items);

        // Act
        $this->obj->addAdjustmentToItem($adjustment, $item1);
        $this->obj->addAdjustmentToItem($adjustment, $item2);

        $items = $this->obj->getItems();
        /** @var Item $item */
        $item1 = reset($items);
        $item2 = end($items);

        // Assert
        $this->assertEquals(2600, $this->obj->getTotalPrice());
        $this->assertEquals(800, $item1->getPrice());
        $this->assertEquals(1800, $item2->getPrice());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     */
    public function Should_ThrowException_When_AddAdjustmentToNotExistingItem()
    {
        // Assert
        $this->expectException(ItemNotFoundException::class);

        // Arrange
        $product = new Product('1', 1000);
        $item = new Item($product);
        $adjustment = new Adjustment(-200, 'coupon', 'PROMO');

        // Act
        $this->obj->addAdjustmentToItem($adjustment, $item);
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     */
    public function Should_ThrowException_When_AdjustmentValueIsTooBig()
    {
        // Assert
        $this->expectException(InvalidPriceValueException::class);

        // Arrange
        $product = new Product('1', 1000);
        $this->obj->addProduct($product);
        $adjustment = new Adjustment(-2000, 'coupon', 'PROMO');
        $items = $this->obj->getItems();
        $item = end($items);

        // Act
        $this->obj->addAdjustmentToItem($adjustment, $item);
    }

    /**
     * @test
     */
    public function Should_AddPromotion()
    {
        // Arrange
        $promotion = new Promotion('test');

        // Act
        $this->obj->addPromotion($promotion);

        // Assert
        $this->assertCount(1, $this->obj->getPromotions());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
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
        $shipment = new Shipment('ups', 100, $address);

        // Act
        $this->obj->setShipment($shipment);

        // Assert
        $this->assertTrue($shipment->equals($this->obj->getShipment()));
        $this->assertEquals(100, $this->obj->getShipmentPrice());
        $this->assertEquals(100, $this->obj->getTotalPrice());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_SetPickupShipment()
    {
        // Arrange
        $address = new PickupPointAddress(
            '12345',
            'Normal access point',
            new GeoPoint(52.123, 38.123)
        );
        $shipment = new Shipment('ups_access_point', 100, $address);

        // Act
        $this->obj->setShipment($shipment);

        // Assert
        $this->assertTrue($shipment->equals($this->obj->getShipment()));
        $this->assertEquals(100, $this->obj->getShipmentPrice());
        $this->assertEquals(100, $this->obj->getTotalPrice());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ThrowException_When_SetShipmentWithInvalidPrice()
    {
        // Assert
        $this->expectException(InvalidPriceValueException::class);

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
        $shipment = new Shipment('ups', -100, $address);

        // Act
        $this->obj->setShipment($shipment);
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
     * @test
     */
    public function Should_SetCreated()
    {
        // Arrange
        $date = new DateTimeImmutable();

        // Act
        $this->obj->setCreated($date);

        // Assert
        $this->assertEquals($date, $this->obj->getCreated());
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