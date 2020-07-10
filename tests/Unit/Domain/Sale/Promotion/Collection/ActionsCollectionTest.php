<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Collection;

use Karolak\EcoEngine\Domain\Common\Collection\Collection;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Collection\ActionsCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class ActionsCollectionTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Collection
 */
class ActionsCollectionTest extends TestCase
{
    /** @var ActionsCollection */
    private $obj;

    /**
     * Set up.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new ActionsCollection();
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
        $item = $this->createStub(ActionInterface::class);

        // Act
        $this->obj->add($item);

        // Assert
        $this->assertFalse($this->obj->isEmpty());
    }
}