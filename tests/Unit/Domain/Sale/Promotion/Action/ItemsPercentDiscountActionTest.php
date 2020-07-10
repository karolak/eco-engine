<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;
use PHPUnit\Framework\TestCase;

/**
 * Class ItemsPercentDiscountActionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action
 */
class ItemsPercentDiscountActionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPercentValueException
     */
    public function Should_ImplementActionInterface()
    {
        // Assert
        $this->assertInstanceOf(ActionInterface::class, new ItemsPercentDiscountAction(50.00));
    }

    /**
     * @test
     * @dataProvider invalidPercentDataProvider
     * @param $percent
     */
    public function Should_ThrowException_When_InvalidPercentValue($percent)
    {
        // Assert
        $this->expectException(InvalidPercentValueException::class);

        // Act
        $action = new ItemsPercentDiscountAction($percent);
    }

    /**
     * @test
     * @throws InvalidPercentValueException
     */
    public function Should_ReturnCorrectPercentValue()
    {
        // Act
        $action = new ItemsPercentDiscountAction(50.00);

        // Assert
        $this->assertEquals(50.00, $action->getValue());
    }

    /**
     * @return array
     */
    public function invalidPercentDataProvider()
    {
        return [
            [-10],
            [-1],
            [-0.01],
            [101],
            [200]
        ];
    }
}