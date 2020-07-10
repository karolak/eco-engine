<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\Registry;

use Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler\ActionHandlerInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerAlreadyRegisteredException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerNotFoundException;

/**
 * Class ActionRegistry
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\Registry
 */
class ActionRegistry
{
    /** @var array|ActionHandlerInterface[] */
    private $handlers;

    /**
     * ActionRegistry constructor.
     */
    public function __construct()
    {
        $this->handlers = [];
    }

    /**
     * @param string $action
     * @param ActionHandlerInterface $actionHandler
     * @throws ActionHandlerAlreadyRegisteredException
     */
    public function set(string $action, ActionHandlerInterface $actionHandler)
    {
        if (isset($this->handlers[$action])) {
            throw new ActionHandlerAlreadyRegisteredException();
        }

        $this->handlers[$action] = $actionHandler;
    }

    /**
     * @param string $action
     * @return ActionHandlerInterface
     * @throws ActionHandlerNotFoundException
     */
    public function get(string $action): ActionHandlerInterface
    {
        if (!isset($this->handlers[$action])) {
            throw new ActionHandlerNotFoundException();
        }

        return $this->handlers[$action];
    }
}