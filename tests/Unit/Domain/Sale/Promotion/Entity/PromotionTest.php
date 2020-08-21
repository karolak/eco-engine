<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Entity;

use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\EmptyCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Condition\MinimumItemsQuantityCondition;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\EmptyFilter;
use Karolak\EcoEngine\Domain\Sale\Promotion\Filter\ProductAttributeFilter;
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
        $this->obj = new Promotion('test', 'coupon');
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
     */
    public function Should_ReturnType()
    {
        // Assert
        $this->assertEquals('coupon', $this->obj->getType());
    }

    /**
     * @test
     */
    public function Should_ReturnEmptyConditionByDefault()
    {
        // Act
        $condition = $this->obj->getCondition();

        // Asset
        $this->assertInstanceOf(EmptyCondition::class, $condition);
    }

    /**
     * @test
     */
    public function Should_SetCondition()
    {
        // Arrange
        $condition = new MinimumItemsQuantityCondition(3);

        // Act
        $this->obj->setCondition($condition);
        $condition = $this->obj->getCondition();

        // Assert
        $this->assertInstanceOf(MinimumItemsQuantityCondition::class, $condition);
    }

    /**
     * @test
     */
    public function Should_ReturnEmptyFilterByDefault()
    {
        // Act
        $filter = $this->obj->getFilter();

        // Asset
        $this->assertInstanceOf(EmptyFilter::class, $filter);
    }

    /**
     * @test
     */
    public function Should_SetFilter()
    {
        // Arrange
        $filter = new ProductAttributeFilter(new TextAttribute('test', 'test'));

        // Act
        $this->obj->setFilter($filter);
        $filter = $this->obj->getFilter();

        // Assert
        $this->assertInstanceOf(ProductAttributeFilter::class, $filter);
    }

    /**
     * @test
     */
    public function Should_AddAction()
    {
        // Arrange
        $action = $this->createStub(ActionInterface::class);

        // Act
        $this->obj->addAction($action);
        $actions = $this->obj->getActions();

        // Assert
        $this->assertCount(1, $actions);
    }
}