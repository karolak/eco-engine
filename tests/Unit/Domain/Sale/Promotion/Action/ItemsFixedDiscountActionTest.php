<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsFixedDiscountAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ItemsFixedDiscountActionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action
 */
class ItemsFixedDiscountActionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ImplementActionInterface()
    {
        // Assert
        $this->assertInstanceOf(ActionInterface::class, new ItemsFixedDiscountAction(50));
    }

    /**
     * @test
     * @dataProvider invalidPriceDataProvider
     * @param $value
     */
    public function Should_ThrowException_When_InvalidPercentValue($value)
    {
        // Assert
        $this->expectException(InvalidPriceValueException::class);

        // Act
        $action = new ItemsFixedDiscountAction($value);
    }


    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnCorrectPriceValue()
    {
        // Act
        $action = new ItemsFixedDiscountAction(50);

        // Assert
        $this->assertEquals(50, $action->getValue());
    }

    /**
     * @return array
     */
    public function invalidPriceDataProvider()
    {
        return [
            [-10],
            [-1]
        ];
    }
}