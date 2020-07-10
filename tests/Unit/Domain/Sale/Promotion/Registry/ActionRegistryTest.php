<?php
namespace Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Registry;

use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ActionHandlerInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerAlreadyRegisteredException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Registry\ActionRegistry;
use PHPUnit\Framework\TestCase;

/**
 * Class ActionRegistryTest
 * @package Karolak\EcoEngine\Test\Unit\Domain\Sale\Promotion\Registry
 */
class ActionRegistryTest extends TestCase
{
    /** @var ActionRegistry */
    private $obj;

    /**
     * Set up.
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->obj = new ActionRegistry();
    }

    /**
     * @test
     * @throws ActionHandlerNotFoundException
     * @throws ActionHandlerAlreadyRegisteredException
     */
    public function Should_SetActionHandler()
    {
        // Arrange
        $actionHandler = $this->createStub(ActionHandlerInterface::class);

        // Act
        $this->obj->set('test', $actionHandler);

        // Assert
        $this->assertInstanceOf(ActionHandlerInterface::class, $this->obj->get('test'));
    }

    /**
     * @test
     */
    public function Should_ThrowException_When_ActionHandlerNotFound()
    {
        // Assert
        $this->expectException(ActionHandlerNotFoundException::class);

        // Act
        $this->obj->get('test');
    }

    /**
     * @test
     */
    public function Should_ThrowException_When_ActionHandlerAlreadyRegistered()
    {
        // Assert
        $this->expectException(ActionHandlerAlreadyRegisteredException::class);

        // Arrange
        $actionHandler = $this->createStub(ActionHandlerInterface::class);

        // Act
        $this->obj->set('test', $actionHandler);
        $this->obj->set('test', $actionHandler);
    }
}