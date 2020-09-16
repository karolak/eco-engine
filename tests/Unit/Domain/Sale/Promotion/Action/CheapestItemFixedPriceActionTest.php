<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action;

use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\CheapestItemFixedPriceAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidGroupSizeException;
use PHPUnit\Framework\TestCase;

/**
 * Class CheapestItemFixedPriceActionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Action
 */
class CheapestItemFixedPriceActionTest extends TestCase
{
    /**
     * @test
     * @throws InvalidPriceValueException
     * @throws InvalidGroupSizeException
     */
    public function Should_ImplementActionInterface()
    {
        // Assert
        $this->assertInstanceOf(ActionInterface::class, new CheapestItemFixedPriceAction(100));
    }

    /**
     * @test
     * @dataProvider invalidPriceValueDataProvider
     * @param int $value
     * @throws InvalidGroupSizeException
     * @throws InvalidPriceValueException
     */
    public function Should_ThrowException_When_InvalidPriceValue(int $value)
    {
        // Assert
        $this->expectException(InvalidPriceValueException::class);

        // Act
        $action = new CheapestItemFixedPriceAction($value);
    }

    /**
     * @test
     * @dataProvider invalidInEveryGroupOfDataProvider
     * @param int $value
     * @throws InvalidGroupSizeException
     * @throws InvalidPriceValueException
     */
    public function Should_ThrowException_When_InvalidInEveryGroupOfValue(int $value)
    {
        // Assert
        $this->expectException(InvalidGroupSizeException::class);

        // Act
        $action = new CheapestItemFixedPriceAction(100, $value);
    }

    /**
     * @test
     * @throws InvalidGroupSizeException
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnDefaultInEveryGroupOfValue()
    {
        // Act
        $action = new CheapestItemFixedPriceAction(100);

        // Assert
        $this->assertEquals(0, $action->getInEveryGroupOf());
    }

    /**
     * @test
     * @throws InvalidGroupSizeException
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnCorrectFixedValue()
    {
        // Act
        $action = new CheapestItemFixedPriceAction(50);

        // Assert
        $this->assertEquals(50, $action->getFixedPrice());
    }

    /**
     * @return array
     */
    public function invalidPriceValueDataProvider()
    {
        return [
            [-10],
            [-1]
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