<?php
namespace Karolak\EcoEngine\Test\Unit\Infrastructure\Sale\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Collection\AdjustmentsCollection;
use Karolak\EcoEngine\Domain\Sale\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\ValueObject\Adjustment;
use PHPUnit\Framework\TestCase;

/**
 * Class AdjustmentsCollectionTest.
 * @package Karolak\EcoEngine\Test\Unit\Infrastructure\Sale\Collection
 */
class AdjustmentsCollectionTest extends TestCase
{
    /** @var AdjustmentsCollection */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new AdjustmentsCollection();
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
        // Act
        $this->obj->add(new Adjustment(-100, 'promo'));

        // Assert
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_RemoveItem()
    {
        // Arrange
        $this->obj->add(new Adjustment(-100, 'promo'));

        // Act
        $this->obj->remove(0);

        // Assert
        $this->assertTrue($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_NotReturnItemKey_When_ItemNotFound()
    {
        // Act
        $result = $this->obj->get(0);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function Should_ReturnItem_When_FoundByKey()
    {
        // Arrange
        $item = new Adjustment(-100, 'promo');
        $this->obj->add($item);

        // Act
        $result = $this->obj->get(0);

        // Assert
        $this->assertSame($item, $result);
    }

    /**
     * @test
     */
    public function Should_NotReturnItem_When_EmptyCollection()
    {
        // Act
        $result = $this->obj->get(1);

        // Assert
        $this->assertNull($result);
        $this->assertTrue($this->obj->isEmpty());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_NotReturnItem_When_NotFoundInNotEmptyCollection()
    {
        // Arrange
        $this->obj->add(new Adjustment(-100, 'promo'));

        // Act
        $result = $this->obj->get(3);

        // Assert
        $this->assertNull($result);
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_SetItem_When_EmptyCollection()
    {
        // Arrange
        $index = 3;
        $item = new Adjustment(-100, 'promo');

        // Act
        $this->obj->set($index, $item);
        $result = $this->obj->get($index);

        // Assert
        $this->assertSame($item, $result);
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_ReplaceItem()
    {
        // Arrange
        $index = 3;
        $item = new Adjustment(-100, 'promo');
        $this->obj->set($index, new Adjustment(-200, 'promo'));

        // Act
        $this->obj->set($index, $item);
        $result = $this->obj->get($index);

        // Assert
        $this->assertSame($item, $result);
    }
}