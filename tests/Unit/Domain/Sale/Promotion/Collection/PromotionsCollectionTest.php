<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Collection\PromotionsCollection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use PHPUnit\Framework\TestCase;

/**
 * Class PromotionsCollectionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Collection
 */
class PromotionsCollectionTest extends TestCase
{
    /** @var PromotionsCollection */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new PromotionsCollection();
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
     */
    public function Should_AddItem()
    {
        // Arrange
        $item = $this->createStub(Promotion::class);

        // Act
        $this->obj->add($item);

        // Assert
        $this->assertFalse($this->obj->isEmpty());
    }
}