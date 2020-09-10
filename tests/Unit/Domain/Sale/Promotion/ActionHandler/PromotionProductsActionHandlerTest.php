<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsFixedDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\PromotionProductsAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\PromotionProductsActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidActionException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidLimitException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\PromotionProductAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\Promotion\ValueObject\PromotionProduct;
use PHPUnit\Framework\TestCase;

/**
 * Class PromotionProductsActionHandlerTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\ActionHandler
 */
class PromotionProductsActionHandlerTest extends TestCase
{
    /** @var PromotionProductsActionHandler */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new PromotionProductsActionHandler();
    }

    /**
     * @test
     */
    public function Should_ImplementActionHandlerInterface()
    {
        // Assert
        $this->assertInstanceOf(PromotionProductsActionHandler::class, $this->obj);
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
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
     * @throws InvalidLimitException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws PromotionProductAlreadyAddedException
     */
    public function Should_DoNothing_When_EmptyOrder()
    {
        // Arrange
        $order = new Order();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new PromotionProductsAction([]);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);

        // Assert
        $this->assertEquals(0, $order->getTotalProductsPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidLimitException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws PromotionProductAlreadyAddedException
     */
    public function Should_DoNothing_When_NoPromotionProducts()
    {
        // Arrange
        $product1 = new Product('1', 2000);
        $product2 = new Product('2', 4000);
        $order = new Order();
        $order->addProduct($product1);
        $order->addProduct($product2);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new PromotionProductsAction([]);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);

        // Assert
        $this->assertEquals(0, $totalProductsPrice - $order->getTotalProductsPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPriceValueException
     * @throws InvalidLimitException
     * @throws ItemNotFoundException
     * @throws PromotionProductAlreadyAddedException
     */
    public function Should_CorrectDiscountOrderItems_When_DefaultTotalLimit()
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
        $action = new PromotionProductsAction(
            [new PromotionProduct('3', 1), new PromotionProduct('1', 1)]
        );

        // Act
        $order = $this->obj->handle($action, $promotion, $order);
        $items = $order->getItems();

        // Assert
        $this->assertEquals(1999, $totalProductsPrice - $order->getTotalProductsPrice());
        $this->assertEquals(1, $items[0]->getPrice());
        $this->assertEquals(4000, $items[1]->getPrice());
        $this->assertEquals(1000, $items[2]->getPrice());
        $this->assertEquals(3000, $items[3]->getPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPriceValueException
     * @throws InvalidLimitException
     * @throws ItemNotFoundException
     * @throws PromotionProductAlreadyAddedException
     */
    public function Should_CorrectDiscountOrderItems_When_SetTotalLimit()
    {
        // Arrange
        $product1 = new Product('1', 2000);
        $product2 = new Product('2', 4000);
        $product3 = new Product('3', 1000);
        $product4 = new Product('3', 1000);
        $order = new Order();
        $order->addProduct($product1);
        $order->addProduct($product2);
        $order->addProduct($product3);
        $order->addProduct($product4);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new PromotionProductsAction(
            [new PromotionProduct('3', 1), new PromotionProduct('1', 1), new PromotionProduct('2', 1)],
            2
        );

        // Act
        $order = $this->obj->handle($action, $promotion, $order);
        $items = $order->getItems();

        // Assert
        $this->assertEquals(5998, $totalProductsPrice - $order->getTotalProductsPrice());
        $this->assertEquals(1, $items[0]->getPrice());
        $this->assertEquals(1, $items[1]->getPrice());
        $this->assertEquals(1000, $items[2]->getPrice());
        $this->assertEquals(1000, $items[3]->getPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPriceValueException
     * @throws InvalidLimitException
     * @throws ItemNotFoundException
     * @throws PromotionProductAlreadyAddedException
     */
    public function Should_CorrectDiscountOrderItems_When_DefaultItemLimit()
    {
        // Arrange
        $product1 = new Product('3', 1000);
        $product2 = new Product('3', 1000);
        $product3 = new Product('3', 1000);
        $product4 = new Product('4', 1000);
        $order = new Order();
        $order->addProduct($product1);
        $order->addProduct($product2);
        $order->addProduct($product3);
        $order->addProduct($product4);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new PromotionProductsAction(
            [new PromotionProduct('3', 1), new PromotionProduct('1', 1), new PromotionProduct('2', 1)],
            3
        );

        // Act
        $order = $this->obj->handle($action, $promotion, $order);
        $items = $order->getItems();

        // Assert
        $this->assertEquals(999, $totalProductsPrice - $order->getTotalProductsPrice());
        $this->assertEquals(1, $items[0]->getPrice());
        $this->assertEquals(1000, $items[1]->getPrice());
        $this->assertEquals(1000, $items[2]->getPrice());
        $this->assertEquals(1000, $items[3]->getPrice());
    }

    /**
     * @test
     * @throws InvalidActionException
     * @throws InvalidPriceValueException
     * @throws InvalidLimitException
     * @throws ItemNotFoundException
     * @throws PromotionProductAlreadyAddedException
     */
    public function Should_CorrectDiscountOrderItems_When_SetItemLimit()
    {
        // Arrange
        $product1 = new Product('3', 1000);
        $product2 = new Product('3', 1000);
        $product3 = new Product('3', 1000);
        $product4 = new Product('4', 1000);
        $order = new Order();
        $order->addProduct($product1);
        $order->addProduct($product2);
        $order->addProduct($product3);
        $order->addProduct($product4);
        $totalProductsPrice = $order->getTotalProductsPrice();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new PromotionProductsAction(
            [new PromotionProduct('1', 1), new PromotionProduct('3', 1, 2), new PromotionProduct('2', 1)],
            3
        );

        // Act
        $order = $this->obj->handle($action, $promotion, $order);
        $items = $order->getItems();

        // Assert
        $this->assertEquals(1998, $totalProductsPrice - $order->getTotalProductsPrice());
        $this->assertEquals(1, $items[0]->getPrice());
        $this->assertEquals(1, $items[1]->getPrice());
        $this->assertEquals(1000, $items[2]->getPrice());
        $this->assertEquals(1000, $items[3]->getPrice());
    }
}