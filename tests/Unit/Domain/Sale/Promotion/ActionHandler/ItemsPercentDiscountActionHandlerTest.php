<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsFixedDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ActionHandlerInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ItemsPercentDiscountActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidActionException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;
use PHPUnit\Framework\TestCase;

/**
 * Class ItemsPercentDiscountActionHandlerTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\ActionHandler
 */
class ItemsPercentDiscountActionHandlerTest extends TestCase
{
    /** @var ItemsPercentDiscountActionHandler */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new ItemsPercentDiscountActionHandler();
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
     */
    public function Should_DoNothing_When_EmptyOrder()
    {
        // Arrange
        $order = new Order();

        $promotion = new Promotion('TEST', 'coupon');
        $action = new ItemsPercentDiscountAction(50);

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
     */
    public function Should_CorrectDiscountOrderItems()
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
        $action = new ItemsPercentDiscountAction(50);

        // Act
        $order = $this->obj->handle($action, $promotion, $order);
        $items = $order->getItems();

        // Assert
        $this->assertEquals(5000, $totalProductsPrice - $order->getTotalProductsPrice());
        $this->assertEquals(1000, $items[0]->getPrice());
        $this->assertEquals(2000, $items[1]->getPrice());
        $this->assertEquals(500, $items[2]->getPrice());
        $this->assertEquals(1500, $items[3]->getPrice());
    }
}