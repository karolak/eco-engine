<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\PromotionProductsAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidLimitException;
use Karolak\EcoEngine\Domain\Sale\Promotion\ValueObject\PromotionProduct;
use PHPUnit\Framework\TestCase;

/**
 * Class PromotionProductsActionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action
 */
class PromotionProductsActionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidLimitException
     */
    public function Should_ImplementActionInterface()
    {
        // Assert
        $this->assertInstanceOf(ActionInterface::class, new PromotionProductsAction([]));
    }

    /**
     * @test
     * @dataProvider invalidLimitDataProvider
     * @param int $limit
     */
    public function Should_ThrowException_When_InvalidLimit(int $limit)
    {
        // Assert
        $this->expectException(InvalidLimitException::class);

        // Act
        $action = new PromotionProductsAction([], $limit);
    }

    /**
     * @test
     * @throws InvalidLimitException
     */
    public function Should_ReturnCorrectProducts()
    {
        // Arrange
        $products = [
            new PromotionProduct('1', 1),
            new PromotionProduct('2', 1)
        ];

        // Act
        $action = new PromotionProductsAction($products);

        // Assert
        $this->assertEquals($products, $action->getProducts());
    }

    /**
     * @test
     * @throws InvalidLimitException
     */
    public function Should_ReturnDefaultLimit()
    {
        // Arrange
        $products = [
            new PromotionProduct('1', 1),
            new PromotionProduct('2', 1)
        ];

        // Act
        $action = new PromotionProductsAction($products);

        // Assert
        $this->assertEquals(1, $action->getLimit());
    }

    /**
     * @return \int[][]
     */
    public function invalidLimitDataProvider()
    {
        return [
            [-1],
            [-2],
            [-3]
        ];
    }
}