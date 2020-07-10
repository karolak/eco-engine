<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;

/**
 * Interface ActionHandlerInterface
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler
 */
interface ActionHandlerInterface
{
    /**
     * @param ActionInterface $action
     * @param Promotion $promotion
     * @param Order $order
     * @return Order
     */
    public function handle(ActionInterface $action, Promotion $promotion, Order $order): Order;
}