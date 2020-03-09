<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Model;

use Karolak\EcoEngine\Domain\Sale\Model\Item;
use PHPUnit\Framework\TestCase;

/**
 * Class ItemTest.
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Model
 */
class ItemTest extends TestCase
{
    /** @var Item */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new Item('1');
    }

    /**
     * @test
     */
    public function Should_CreateItem()
    {
        // Assert
        $this->assertInstanceOf(Item::class, $this->obj);
    }

    /**
     * @test
     */
    public function Should_ReturnProductId()
    {
        // Assert
        $this->assertEquals('1', $this->obj->getProductId());
    }

    /**
     * @test
     */
    public function Should_ReturnQuantity()
    {
        // Assert
        $this->assertEquals(1, $this->obj->getQuantity());
    }

    /**
     * @test
     */
    public function Should_AddQuantity()
    {
        // Act
        $this->obj->addQuantity(2);

        // Assert
        $this->assertEquals(3, $this->obj->getQuantity());
    }

    /**
     * @test
     */
    public function Should_SetQuantity()
    {
        // Act
        $this->obj->setQuantity(4);

        // Assert
        $this->assertEquals(4, $this->obj->getQuantity());
    }
}