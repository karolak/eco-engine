<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Collection\PromotionProductsCollection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\PromotionProductAlreadyAddedException;
use Karolak\EcoEngine\Domain\Sale\Promotion\ValueObject\PromotionProduct;
use PHPUnit\Framework\TestCase;

/**
 * Class PromotionProductsCollectionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Collection
 */
class PromotionProductsCollectionTest extends TestCase
{
    /** @var PromotionProductsCollection */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new PromotionProductsCollection();
    }

    /**
     * @test
     */
    public function Should_ExtendCollection()
    {
        // Assert
        $this->assertInstanceOf(Collection::class, $this->obj);
    }

    /**
     * @test
     * @throws PromotionProductAlreadyAddedException
     */
    public function Should_AddItem()
    {
        // Arrange
        $item = $this->createStub(PromotionProduct::class);

        // Act
        $this->obj->add($item);

        // Assert
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_ThrowException_AddSameItemTwice()
    {
        // Assert
        $this->expectException(PromotionProductAlreadyAddedException::class);

        // Arrange
        $item = new PromotionProduct('1', 1);

        // Act
        $this->obj->add($item);
        $this->obj->add($item);
    }
}