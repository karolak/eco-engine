<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Common\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\AttributesCollection;
use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Common\ValueObject\AttributeInterface;
use Karolak\EcoEngine\Domain\Common\ValueObject\TextAttribute;
use PHPUnit\Framework\TestCase;

/**
 * Class AttributesCollectionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Common\Collection
 */
class AttributesCollectionTest extends TestCase
{
    /** @var AttributesCollection */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new AttributesCollection();
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
    public function Should_AddAttribute()
    {
        // Arrange
        $attribute = $this->createMock(AttributeInterface::class);

        // Act
        $this->obj->add($attribute);

        // Assert
        $this->assertFalse($this->obj->isEmpty());
    }

    /**
     * @test
     */
    public function Should_ReturnAttributeByName()
    {
        // Arrange
        $attribute = new TextAttribute('test', 'abc');

        // Act
        $this->obj->add($attribute);
        $foundAttribute = $this->obj->getByName('test');

        // Assert
        $this->assertEquals('test', $foundAttribute->getName());
    }

    /**
     * @test
     */
    public function Should_ReturnNull_When_AttributeByNameNotFound()
    {
        // Arrange
        $attribute = new TextAttribute('test', 'abc');

        // Act
        $this->obj->add($attribute);
        $foundAttribute = $this->obj->getByName('qwerty');

        // Assert
        $this->assertNull($foundAttribute);
    }
}
