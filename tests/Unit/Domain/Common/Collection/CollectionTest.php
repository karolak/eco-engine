<?php
namespace Karolak\EcoEngine\Test\Unit\Infrastructure\Common\Collection;

use ArrayIterator;
use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectionTest.
 * @package Karolak\EcoEngine\Test\Unit\Infrastructure\Common\Collection
 */
class CollectionTest extends TestCase
{
    /**
     * @test
     */
    public function Should_ReturnArrayIterator()
    {
        // Assert
        $this->assertInstanceOf(ArrayIterator::class, ($this->getCollectionObject())->getIterator());
    }

    /**
     * @test
     */
    public function Should_ReturnTrue_When_Empty()
    {
        // Arrange
        $collection = $this->getCollectionObject();

        // Act
        $result = $collection->isEmpty();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function Should_ReturnFalse_When_NotEmpty()
    {
        // Arrange
        $collection = $this->getCollectionObject([1,2,3]);

        // Act
        $result = $collection->isEmpty();

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function Should_ClearCollection()
    {
        // Arrange
        $collection = $this->getCollectionObject([1,2,3]);

        // Act
        $collection->clear();
        $result = $collection->isEmpty();

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     * @dataProvider itemsDataProvider
     * @param array $items
     */
    public function Should_ReturnCount(array $items = [])
    {
        // Arrange
        $count = count($items);
        $collection = $this->getCollectionObject($items);

        // Act
        $result = $collection->count();

        // Assert
        $this->assertEquals($count, $result);
    }

    /**
     * @test
     */
    public function Should_ReturnArray()
    {
        // Assert
        $this->assertIsArray($this->getCollectionObject()->toArray());
    }

    /**
     * @return array
     */
    public function itemsDataProvider()
    {
        return [
            [[]],
            [[1]],
            [[1, 2]],
            [[1, 2, 3]]
        ];
    }

    /**
     * @param array $items
     * @return Collection
     */
    private function getCollectionObject(array $items = [])
    {
        return new class($items) extends Collection {
            public function __construct($items)
            {
                $this->items = $items;
            }
        };
    }
}