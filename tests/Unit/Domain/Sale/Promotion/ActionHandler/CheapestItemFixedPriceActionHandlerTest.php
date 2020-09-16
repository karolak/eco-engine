<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\CheapestItemFixedPriceAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsFixedDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ActionHandlerInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\CheapestItemFixedPriceActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidActionException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidGroupSizeException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;
use PHPUnit\Framework\TestCase;

/**
 * Class CheapestItemFixedPriceActionHandlerTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\ActionHandler
 */
class CheapestItemFixedPriceActionHandlerTest extends TestCase
{
    /** @var CheapestItemFixedPriceActionHandler */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new CheapestItemFixedPriceActionHandler();
    }

    /**
     * @test
     */
    public function Should_ImplementActionHandlerInterface()
    {
        // Assert
        $this->assertInstanceOf(ActionHandlerInterface::class, $this->obj);
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws InvalidPercentValueException
     */
    public function Should_ThrowException_When_HandleIncorrectAction()
    {
        // Assert
        $this->expectException(InvalidActionException::class);

        // Arrange
        $order = new Order();
        $promotion = new Promotion('TEST', 'coupon');
        $action = new ItemsFixedDiscountAction(50);

        // Act
        $this->obj->handle($action, $promotion, $order);
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPercentValueException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws InvalidGroupSizeException
     */
    public function Should_DoNothing_When_EmptyOrder()
    {
        // Arrange
        $order = new Order();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new CheapestItemFixedPriceAction(50);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);

        // Assert
        $this->assertEquals(0, $order->getTotalProductsPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPercentValueException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws InvalidGroupSizeException
     */
    public function Should_CorrectDiscountOrderItems_When_DefaultAction()
    {
        // Arrange
        $product1 = new Product('1', 2000);
        $product2 = new Product('2', 4000);
        $product3 = new Product('3', 1000);
        $product4 = new Product('4', 3000);
        $order = new Order();
        $order->addProduct($product1);
        $order->addProduct($product2);
        $order->addProduct($product3);
        $order->addProduct($product4);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new CheapestItemFixedPriceAction(100);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);

        // Assert
        $this->assertEquals(900, $totalProductsPrice - $order->getTotalProductsPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPercentValueException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws InvalidGroupSizeException
     */
    public function Should_CorrectDiscountOrderItems_When_OnlyOneItemInOrder()
    {
        // Arrange
        $product1 = new Product('1', 2000);
        $order = new Order();
        $order->addProduct($product1);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new CheapestItemFixedPriceAction(50);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);

        // Assert
        $this->assertEquals(1950, $totalProductsPrice - $order->getTotalProductsPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPercentValueException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws InvalidGroupSizeException
     */
    public function Should_CorrectDiscountOrderItems_When_ActionOnEveryGroupOfTwoItems()
    {
        // Arrange
        $product1 = new Product('1', 2000);
        $product2 = new Product('2', 4000);
        $product3 = new Product('3', 1000);
        $product4 = new Product('4', 3000);
        $order = new Order();
        $order->addProduct($product1);
        $order->addProduct($product2);
        $order->addProduct($product3);
        $order->addProduct($product4);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new CheapestItemFixedPriceAction(100, 2);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);

        // Assert
        $this->assertEquals(2800, $totalProductsPrice - $order->getTotalProductsPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPercentValueException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws InvalidGroupSizeException
     */
    public function Should_CorrectDiscountOrderItems_When_ActionOnEveryGroupOfOneItem()
    {
        // Arrange
        $product1 = new Product('1', 2000);
        $product2 = new Product('2', 4000);
        $product3 = new Product('3', 1000);
        $product4 = new Product('4', 3000);
        $order = new Order();
        $order->addProduct($product1);
        $order->addProduct($product2);
        $order->addProduct($product3);
        $order->addProduct($product4);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new CheapestItemFixedPriceAction(100, 1);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);

        // Assert
        $this->assertEquals(9600, $totalProductsPrice - $order->getTotalProductsPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPercentValueException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws InvalidGroupSizeException
     */
    public function Should_DoNothing_When_PromotionPriceIsHigherThanItemPrice()
    {
        // Arrange
        $product1 = new Product('1', 2000);
        $product2 = new Product('2', 4000);
        $product3 = new Product('3', 1000);
        $product4 = new Product('4', 3000);
        $order = new Order();
        $order->addProduct($product1);
        $order->addProduct($product2);
        $order->addProduct($product3);
        $order->addProduct($product4);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new CheapestItemFixedPriceAction(100000);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);

        // Assert
        $this->assertEquals(0, $totalProductsPrice - $order->getTotalProductsPrice());
    }
}