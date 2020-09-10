<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition;

use DateTimeImmutable;
use Karolak\EcoEngine\Domain\Common\Comparator\DateComparator;
use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\OrderDateCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderDateConditionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Condition
 */
class OrderDateConditionTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ReturnWrightResult_When_SameCompare()
    {
        // Arrange
        $order = new Order();
        $order->setCreated(new DateTimeImmutable('2020-01-01 10:00:00'));
        $condition1 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:00'), DateComparator::SAME);
        $condition2 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:01'), DateComparator::SAME);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }

    /**
     * @test
     */
    public function Should_ReturnWrightResult_When_SameOrLatestCompare()
    {
        // Arrange
        $order = new Order();
        $order->setCreated(new DateTimeImmutable('2020-01-01 10:00:00'));
        $condition1 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:00'), DateComparator::SAME_OR_LATEST);
        $condition2 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:01'), DateComparator::SAME_OR_LATEST);
        $condition3 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 09:59:59'), DateComparator::SAME_OR_LATEST);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertFalse($result2);
        $this->assertTrue($result3);
    }

    /**
     * @test
     */
    public function Should_ReturnWrightResult_When_SameOrOlderCompare()
    {
        // Arrange
        $order = new Order();
        $order->setCreated(new DateTimeImmutable('2020-01-01 10:00:00'));
        $condition1 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:00'), DateComparator::SAME_OR_OLDER);
        $condition2 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:01'), DateComparator::SAME_OR_OLDER);
        $condition3 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 09:59:59'), DateComparator::SAME_OR_OLDER);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertTrue($result1);
        $this->assertTrue($result2);
        $this->assertFalse($result3);
    }

    /**
     * @test
     */
    public function Should_ReturnWrightResult_When_LatestCompare()
    {
        // Arrange
        $order = new Order();
        $order->setCreated(new DateTimeImmutable('2020-01-01 10:00:00'));
        $condition1 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:00'), DateComparator::LATEST);
        $condition2 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:01'), DateComparator::LATEST);
        $condition3 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 09:59:59'), DateComparator::LATEST);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result1);
        $this->assertFalse($result2);
        $this->assertTrue($result3);
    }

    /**
     * @test
     */
    public function Should_ReturnWrightResult_When_OlderCompare()
    {
        // Arrange
        $order = new Order();
        $order->setCreated(new DateTimeImmutable('2020-01-01 10:00:00'));
        $condition1 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:00'), DateComparator::OLDER);
        $condition2 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 10:00:01'), DateComparator::OLDER);
        $condition3 = new OrderDateCondition(new DateTimeImmutable('2020-01-01 09:59:59'), DateComparator::OLDER);

        // Act
        $result1 = $condition1->isSatisfiedBy($order);
        $result2 = $condition2->isSatisfiedBy($order);
        $result3 = $condition3->isSatisfiedBy($order);

        // Assert
        $this->assertFalse($result1);
        $this->assertTrue($result2);
        $this->assertFalse($result3);
    }
}