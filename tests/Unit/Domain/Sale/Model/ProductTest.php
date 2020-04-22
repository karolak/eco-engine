<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Model;

use Karolak\EcoEngine\Domain\Sale\Model\Product;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Model
 */
class ProductTest extends TestCase
{
    /** @var Product */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new Product('1');
    }

    /**
     * @test
     */
    public function Should_CreateProduct()
    {
        // Assert
        $this->assertInstanceOf(Product::class, $this->obj);
    }

    /**
     * @test
     */
    public function Should_ReturnId()
    {
        // Assert
        $this->assertEquals('1', $this->obj->getId());
    }

    /**
     * @test
     */
    public function Should_ReturnDefaultPrice()
    {
        // Assert
        $this->assertEquals(0, $this->obj->getPrice());
    }

    /**
     * @test
     */
    public function Should_ReturnPrice()
    {
        // Arrange
        $price = 150;
        $product = new Product('1', $price);

        // Assert
        $this->assertEquals($price, $product->getPrice());
    }
}