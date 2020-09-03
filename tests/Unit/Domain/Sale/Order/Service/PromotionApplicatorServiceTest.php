<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Order\Service;

use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Service\PromotionApplicatorService;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\CheapestItemPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsFixedDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ItemsFixedDiscountActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ItemsPercentDiscountActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\MinimumItemsTotalPriceCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerAlreadyRegisteredException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\TextAttributeFilter;
use Karolak\EcoEngine\Domain\Sale\Promotion\Registry\ActionRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Class PromotionApplicatorServiceTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Order\Service
 */
class PromotionApplicatorServiceTest extends TestCase
{
    /** @var PromotionApplicatorService */
    private $obj;

    /**
     * Set up.
     * @throws ActionHandlerAlreadyRegisteredException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $actionRegistry = new ActionRegistry();
        $actionRegistry->set(ItemsPercentDiscountAction::class, new ItemsPercentDiscountActionHandler());
        $actionRegistry->set(ItemsFixedDiscountAction::class, new ItemsFixedDiscountActionHandler());

        $this->obj = new PromotionApplicatorService($actionRegistry);
    }

    /**
     * @test
     * @throws ActionHandlerNotFoundException
     * @throws InvalidPriceValueException
     */
    public function Should_DoNothing_When_PromotionConditionNotPassed()
    {
        // Arrange
        $order = new Order();
        $order->addProduct(new Product('1', 500));
        $promotion = new Promotion('TEST', 'coupon');
        $promotion->setCondition(new MinimumItemsTotalPriceCondition(1000));

        // Act
        $order = $this->obj->apply($order, $promotion);

        // Assert
        $this->assertEquals(500, $order->getTotalProductsPrice());
        $this->assertCount(0, $order->getPromotions());
    }

    /**
     * @test
     * @throws ActionHandlerNotFoundException
     * @throws InvalidPriceValueException
     */
    public function Should_OnlyAddPromotionToOrder_When_PromotionPassConditionAndHasNoActions()
    {
        // Arrange
        $order = new Order();
        $order->addProduct(new Product('1', 500));
        $promotion = new Promotion('TEST', 'coupon');

        // Act
        $order = $this->obj->apply($order, $promotion);

        // Assert
        $this->assertEquals(500, $order->getTotalProductsPrice());
        $this->assertCount(1, $order->getPromotions());
    }

    /**
     * @test
     * @throws ActionHandlerNotFoundException
     * @throws InvalidPriceValueException
     * @throws InvalidPercentValueException
     */
    public function Should_ApplyPromotionToOrder_When_PromotionPassConditionAndHasAction()
    {
        // Arrange
        $order = new Order();
        $order->addProduct(new Product('1', 500));
        $promotion = new Promotion('TEST', 'coupon');
        $promotion->addAction(new ItemsPercentDiscountAction(50.00));

        // Act
        $order = $this->obj->apply($order, $promotion);

        // Assert
        $this->assertEquals(250, $order->getTotalProductsPrice());
        $this->assertCount(1, $order->getPromotions());
    }

    /**
     * @test
     * @throws ActionHandlerNotFoundException
     * @throws InvalidPriceValueException
     * @throws InvalidPercentValueException
     */
    public function Should_DoNothing_When_PromotionPassConditionAndHasActionButFilterEmptyItems()
    {
        // Arrange
        $order = new Order();
        $order->addProduct(new Product('1', 500));
        $filter = new TextAttributeFilter(new TextAttribute('text', 'text'));
        $promotion = new Promotion('TEST', 'coupon');
        $promotion->addAction(new ItemsPercentDiscountAction(50.00));
        $promotion->setFilter($filter);

        // Act
        $order = $this->obj->apply($order, $promotion);

        // Assert
        $this->assertEquals(500, $order->getTotalProductsPrice());
        $this->assertCount(0, $order->getPromotions());
    }

    /**
     * @test
     * @throws ActionHandlerNotFoundException
     * @throws InvalidPriceValueException
     */
    public function Should_ThrowException_When_PromotionActionNotInRegistry()
    {
        // Assert
        $this->expectException(ActionHandlerNotFoundException::class);

        // Arrange
        $order = new Order();
        $order->addProduct(new Product('1', 500));
        $promotion = new Promotion('TEST', 'coupon');
        $promotion->addAction(new CheapestItemPercentDiscountAction());

        // Act
        $this->obj->apply($order, $promotion);
    }
}