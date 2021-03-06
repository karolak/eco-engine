<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Order\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Order\Collection\ItemsCollection;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class ItemsCollectionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Order\Collection
 */
class ItemsCollectionTest extends TestCase
{
    /** @var ItemsCollection */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new ItemsCollection();
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
     * @throws InvalidPriceValueException
     */
    public function Should_AddItem()
    {
        // Arrange
        $productId = '1';
        $productPrice = 100;

        // Act
        $this->obj->add(new Item(new Product($productId, $productPrice)));

        // Assert
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_RemoveItem()
    {
        // Arrange
        $this->obj->add(new Item(new Product('1', 100)));

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
     * @throws InvalidPriceValueException
     */
    public function Should_ReturnItem_When_FoundByKey()
    {
        // Arrange
        $item = new Item(new Product('1', 100));
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
        $this->obj->add(new Item(new Product('1', 100)));

        // Act
        $result = $this->obj->get(3);

        // Assert
        $this->assertNull($result);
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_SetItem_When_EmptyCollection()
    {
        // Arrange
        $index = 3;
        $item = new Item(new Product('1', 100));

        // Act
        $this->obj->set($index, $item);
        $result = $this->obj->get($index);

        // Assert
        $this->assertSame($item, $result);
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     * @throws InvalidPriceValueException
     */
    public function Should_ReplaceItem()
    {
        // Arrange
        $index = 3;
        $item = new Item(new Product('1', 100));
        $this->obj->set($index, new Item(new Product('2', 200)));

        // Act
        $this->obj->set($index, $item);
        $result = $this->obj->get($index);

        // Assert
        $this->assertSame($item, $result);
    }
}