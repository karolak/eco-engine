<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\CheapestItemDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidGroupSizeException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;
use PHPUnit\Framework\TestCase;

/**
 * Class CheapestItemDiscountActionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action
 */
class CheapestItemDiscountActionTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ImplementActionInterface()
    {
        // Assert
        $this->assertInstanceOf(ActionInterface::class, new CheapestItemDiscountAction());
    }

    /**
     * @test
     * @dataProvider invalidPercentDataProvider
     * @param float $percent
     * @throws InvalidGroupSizeException
     */
    public function Should_ThrowException_When_InvalidPercentValue(float $percent)
    {
        // Assert
        $this->expectException(InvalidPercentValueException::class);

        // Act
        $action = new CheapestItemDiscountAction($percent);
    }

    /**
     * @test
     * @dataProvider invalidInEveryGroupOfDataProvider
     * @param int $value
     * @throws InvalidGroupSizeException
     * @throws InvalidPercentValueException
     */
    public function Should_ThrowException_When_InvalidInEveryGroupOfValue(int $value)
    {
        // Assert
        $this->expectException(InvalidGroupSizeException::class);

        // Act
        $action = new CheapestItemDiscountAction(100.00, $value);
    }

    /**
     * @test
     */
    public function Should_ReturnDefaultPercentValue()
    {
        // Act
        $action = new CheapestItemDiscountAction();

        // Assert
        $this->assertEquals(100.00, $action->getPercentDiscount());
    }

    /**
     * @test
     */
    public function Should_ReturnDefaultInEveryGroupOfValue()
    {
        // Act
        $action = new CheapestItemDiscountAction();

        // Assert
        $this->assertEquals(0, $action->getInEveryGroupOf());
    }

    /**
     * @test
     * @throws InvalidPercentValueException
     * @throws InvalidGroupSizeException
     */
    public function Should_ReturnCorrectPercentValue()
    {
        // Act
        $action = new CheapestItemDiscountAction(50.00);

        // Assert
        $this->assertEquals(50.00, $action->getPercentDiscount());
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

    /**
     * @return array
     */
    public function invalidInEveryGroupOfDataProvider()
    {
        return [
            [-10],
            [-1]
        ];
    }
}