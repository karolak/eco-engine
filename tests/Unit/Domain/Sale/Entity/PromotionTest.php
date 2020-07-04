<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Entity;

use Karolak\EcoEngine\Domain\Sale\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Exception\PromotionActionAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\Exception\PromotionConditionAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\ValueObject\PromotionAction;
use Karolak\EcoEngine\Domain\Sale\ValueObject\PromotionCondition;
use PHPUnit\Framework\TestCase;

/**
 * Class PromotionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Entity
 */
class PromotionTest extends TestCase
{
    /** @var Promotion */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new Promotion('test');
    }

    /**
     * @test
     */
    public function Should_CreatePromotion()
    {
        // Assert
        $this->assertInstanceOf(Promotion::class, $this->obj);
    }

    /**
     * @test
     */
    public function Should_ReturnName()
    {
        // Assert
        $this->assertEquals('test', $this->obj->getName());
    }

    /**
     * @test
     * @throws PromotionConditionAlreadyAddedException
     */
    public function Should_AddPromotionCondition()
    {
        // Arrange
        $promotionCondition = new PromotionCondition('test');

        // Act
        $this->obj->addPromotionCondition($promotionCondition);
        $conditions = $this->obj->getPromotionConditions();

        // Assert
        $this->assertCount(1, $conditions);
        $this->assertTrue($promotionCondition->equals($conditions[0]));
    }

    /**
     * @test
     */
    public function Should_ThrowException_AddSamePromotionConditionTwice()
    {
        // Assert
        $this->expectException(PromotionConditionAlreadyAddedException::class);

        // Arrange
        $promotionCondition = new PromotionCondition('test');

        // Act
        $this->obj->addPromotionCondition($promotionCondition);
        $this->obj->addPromotionCondition($promotionCondition);
    }

    /**
     * @test
     * @throws PromotionConditionAlreadyAddedException
     */
    public function Should_AddTwoDifferentPromotionConditions()
    {
        // Arrange
        $promotionCondition1 = new PromotionCondition('test1');
        $promotionCondition2 = new PromotionCondition('test2');

        // Act
        $this->obj->addPromotionCondition($promotionCondition1);
        $this->obj->addPromotionCondition($promotionCondition2);
        $conditions = $this->obj->getPromotionConditions();

        // Assert
        $this->assertCount(2, $conditions);
        $this->assertTrue($promotionCondition1->equals($conditions[0]));
        $this->assertTrue($promotionCondition2->equals($conditions[1]));
    }

    /**
     * @test
     * @throws PromotionActionAlreadyAddedException
     */
    public function Should_AddPromotionAction()
    {
        // Arrange
        $promotionAction = new PromotionAction('test');

        // Act
        $this->obj->addPromotionAction($promotionAction);
        $actions = $this->obj->getPromotionActions();

        // Assert
        $this->assertCount(1, $actions);
        $this->assertTrue($promotionAction->equals($actions[0]));
    }

    /**
     * @test
     */
    public function Should_ThrowException_When_AddSamePromotionActionTwice()
    {
        // Assert
        $this->expectException(PromotionActionAlreadyAddedException::class);

        // Arrange
        $promotionAction = new PromotionAction('test');

        // Act
        $this->obj->addPromotionAction($promotionAction);
        $this->obj->addPromotionAction($promotionAction);
    }

    /**
     * @test
     * @throws PromotionActionAlreadyAddedException
     */
    public function Should_AddTwoDifferentPromotionActions()
    {
        // Arrange
        $promotionAction1 = new PromotionAction('test1');
        $promotionAction2 = new PromotionAction('test2');

        // Act
        $this->obj->addPromotionAction($promotionAction1);
        $this->obj->addPromotionAction($promotionAction2);
        $actions = $this->obj->getPromotionActions();

        // Assert
        $this->assertCount(2, $actions);
        $this->assertTrue($promotionAction1->equals($actions[0]));
        $this->assertTrue($promotionAction2->equals($actions[1]));
    }
}