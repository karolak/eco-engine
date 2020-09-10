<?php
namespace Karolak\EcoEngine\Test\Integration\Sale;

use Karolak\EcoEngine\Domain\Common\Comparator\ListAttributesComparator;
use Karolak\EcoEngine\Domain\Common\Comparator\NumericAttributesComparator;
use Karolak\EcoEngine\Domain\Common\ValueObject\ListAttribute;
use Karolak\EcoEngine\Domain\Common\ValueObject\NumericAttribute;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Service\PromotionApplicatorService;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\CheapestItemFixedDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\CheapestItemPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsFixedDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\PromotionProductsAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\CheapestItemFixedDiscountActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\CheapestItemPercentDiscountActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ItemsFixedDiscountActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ItemsPercentDiscountActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\PromotionProductsActionHandler;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\AndCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\ConditionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\MinimumItemsQuantityCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\NumericAttributeCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerAlreadyRegisteredException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidGroupSizeException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidLimitException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\PromotionProductAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\EmptyFilter;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\FilterInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\ListAttributeFilter;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\NumericAttributeFilter;
use Karolak\EcoEngine\Domain\Sale\Promotion\Registry\ActionRegistry;
use Karolak\EcoEngine\Domain\Sale\Promotion\ValueObject\PromotionProduct;
use PHPUnit\Framework\TestCase;

/**
 * Class PromotionsTest
 * @package Karolak\EcoEngine\Test\Integration\Sale
 */
class PromotionsTest extends TestCase
{
    /** @var PromotionApplicatorService */
    private $promotionApplicator;

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
        $actionRegistry->set(CheapestItemPercentDiscountAction::class, new CheapestItemPercentDiscountActionHandler());
        $actionRegistry->set(CheapestItemFixedDiscountAction::class, new CheapestItemFixedDiscountActionHandler());
        $actionRegistry->set(PromotionProductsAction::class, new PromotionProductsActionHandler());

        $this->promotionApplicator = new PromotionApplicatorService($actionRegistry);
    }

    /**
     * @test
     * @param array|Product[] $products
     * @param array|ActionInterface[] $promotionActions
     * @param ConditionInterface|null $promotionCondition
     * @param FilterInterface|null $promotionFilter
     * @param array $results
     * @dataProvider provider
     * @throws ActionHandlerNotFoundException
     */
    public function Should_ApplyPromotions(
        array $products = [],
        array $promotionActions = [],
        ConditionInterface $promotionCondition = null,
        FilterInterface $promotionFilter = null,
        array $results = []
    )
    {
        // Arrange
        $order = new Order();
        if (!empty($products)) {
            foreach ($products as $product) {
                $order->addProduct($product);
            }
        }

        $promotion = new Promotion('TEST', 'test');
        if (!empty($promotionActions)) {
            foreach ($promotionActions as $action) {
                $promotion->addAction($action);
            }
        }
        $promotion->setCondition($promotionCondition ?? new EmptyCondition());
        $promotion->setFilter($promotionFilter ?? new EmptyFilter());

        // Act
        $order = $this->promotionApplicator->apply($order, $promotion);

        // Assert
        $this->assertEquals($results['totalPrice'], $order->getTotalPrice());

        if (isset($results['applyPromotion']) && $results['applyPromotion'] === false) {
            $this->assertEmpty($order->getPromotions());
        } else {
            $this->assertNotEmpty($order->getPromotions());
        }
    }

    /**
     * @return array
     * @throws InvalidGroupSizeException
     * @throws InvalidPercentValueException
     * @throws InvalidPriceValueException
     * @throws InvalidLimitException
     * @throws PromotionProductAlreadyAddedException
     */
    public function provider()
    {
        return [
            [
                // products
                [ new Product('1', 20000) ],
                // promotion actions
                [ new ItemsFixedDiscountAction(1000) ],
                // promotion conditions
                null,
                // promotion filters
                null,
                // results
                ['totalPrice' => 19000],
            ],
            [
                // products
                [ new Product('1', 20000), new Product('2', 10000) ],
                // promotion actions
                [ new ItemsFixedDiscountAction(10000) ],
                // promotion conditions
                null,
                // promotion filters
                null,
                // results
                ['totalPrice' => 20000],
            ],
            [
                // products
                [ new Product('1', 20000), new Product('2', 10000) ],
                // promotion actions
                [ new ItemsFixedDiscountAction(10000) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(2),
                // promotion filters
                null,
                // results
                ['totalPrice' => 20000],
            ],
            [
                // products
                [ new Product('1', 20000), new Product('2', 10000) ],
                // promotion actions
                [ new ItemsFixedDiscountAction(10000) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(2),
                // promotion filters
                null,
                // results
                ['totalPrice' => 20000],
            ],
            [
                // products
                [
                    new Product('1', 20000, [new NumericAttribute('total', 200)]),
                    new Product('2', 10000, [new NumericAttribute('total', 100)])
                ],
                // promotion actions
                [ new ItemsFixedDiscountAction(10000) ],
                // promotion conditions
                new AndCondition(
                    new MinimumItemsQuantityCondition(2),
                    new NumericAttributeCondition(new NumericAttribute('total', 150), NumericAttributesComparator::HIGHER)
                ),
                // promotion filters
                null,
                // results
                ['totalPrice' => 20000],
            ],
            [
                // products
                [
                    new Product('1', 20000, [new NumericAttribute('total', 200)]),
                    new Product('2', 10000, [new NumericAttribute('total', 100)])
                ],
                // promotion actions
                [ new ItemsPercentDiscountAction(10) ],
                // promotion conditions
                new AndCondition(
                    new MinimumItemsQuantityCondition(2),
                    new NumericAttributeCondition(new NumericAttribute('total', 150), NumericAttributesComparator::HIGHER)
                ),
                // promotion filters
                new NumericAttributeFilter(new NumericAttribute('total', 150), NumericAttributesComparator::HIGHER),
                // results
                ['totalPrice' => 28000],
            ],
            [
                // products
                [
                    new Product('1', 20000, [new NumericAttribute('total', 100)]),
                    new Product('2', 10000, [new NumericAttribute('total', 100)])
                ],
                // promotion actions
                [ new ItemsPercentDiscountAction(10) ],
                // promotion conditions
                new AndCondition(
                    new MinimumItemsQuantityCondition(2),
                    new NumericAttributeCondition(new NumericAttribute('total', 150), NumericAttributesComparator::HIGHER)
                ),
                // promotion filters
                new NumericAttributeFilter(new NumericAttribute('total', 150), NumericAttributesComparator::HIGHER),
                // results
                ['totalPrice' => 30000, 'applyPromotion' => false],
            ],
            [
                // products
                [
                    new Product('1', 20000),
                    new Product('2', 10000)
                ],
                // promotion actions
                [ new CheapestItemPercentDiscountAction(100) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(2),
                // promotion filters
                null,
                // results
                ['totalPrice' => 20000],
            ],
            [
                // products
                [
                    new Product('1', 20000),
                    new Product('2', 10000)
                ],
                // promotion actions
                [ new CheapestItemFixedDiscountAction(5000) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(2),
                // promotion filters
                null,
                // results
                ['totalPrice' => 25000],
            ],
            [
                // products
                [
                    new Product('1', 20000),
                    new Product('2', 10000)
                ],
                // promotion actions
                [ new CheapestItemPercentDiscountAction(100) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(3),
                // promotion filters
                null,
                // results
                ['totalPrice' => 30000, 'applyPromotion' => false],
            ],
            [
                // products
                [
                    new Product('1', 10000),
                    new Product('2', 20000),
                    new Product('3', 30000),
                    new Product('4', 40000),
                ],
                // promotion actions
                [ new CheapestItemPercentDiscountAction(100, 2) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(2),
                // promotion filters
                null,
                // results
                ['totalPrice' => 70000],
            ],
            [
                // products
                [
                    new Product('1', 10000),
                    new Product('2', 20000),
                    new Product('3', 30000),
                    new Product('4', 40000),
                ],
                // promotion actions
                [ new CheapestItemPercentDiscountAction(50, 2) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(2),
                // promotion filters
                null,
                // results
                ['totalPrice' => 85000],
            ],
            [
                // products
                [
                    new Product('1', 10000),
                    new Product('2', 20000),
                    new Product('3', 30000),
                    new Product('4', 40000),
                ],
                // promotion actions
                [ new CheapestItemFixedDiscountAction(5000, 2) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(2),
                // promotion filters
                null,
                // results
                ['totalPrice' => 90000],
            ],
            [
                // products
                [
                    new Product('1', 10000),
                    new Product('2', 20000),
                    new Product('3', 30000),
                    new Product('4', 40000),
                ],
                // promotion actions
                [ new CheapestItemPercentDiscountAction(50, 3) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(3),
                // promotion filters
                null,
                // results
                ['totalPrice' => 95000],
            ],
            [
                // products
                [
                    new Product('1', 10000, [new ListAttribute('categories', [1, 2])]),
                    new Product('2', 20000, [new ListAttribute('categories', [3, 4])]),
                    new Product('3', 30000),
                    new Product('4', 40000),
                ],
                // promotion actions
                [ new ItemsFixedDiscountAction(5000) ],
                // promotion conditions
                new MinimumItemsQuantityCondition(3),
                // promotion filters
                new ListAttributeFilter(new ListAttribute('categories', [1, 5]), ListAttributesComparator::IN),
                // results
                ['totalPrice' => 95000],
            ],
            [
                // products
                [
                    new Product('1', 10000),
                    new Product('2', 20000),
                    new Product('3', 30000),
                    new Product('4', 40000),
                ],
                // promotion actions
                [ new PromotionProductsAction(
                    [
                        new PromotionProduct('5', 1000),
                        new PromotionProduct('1', 1000),
                    ], 1)
                ],
                // promotion conditions
                new MinimumItemsQuantityCondition(2),
                // promotion filters
                null,
                // results
                ['totalPrice' => 91000],
            ],
        ];
    }
}