<?php
namespace Karolak\EcoEngine\Domain\Sale\Order\Service;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\ActionHandlerNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Registry\ActionRegistry;

/**
 * Class PromotionApplicatorService
 * @package Karolak\EcoEngine\Domain\Sale\Order\Service
 */
class PromotionApplicatorService
{
    /** @var ActionRegistry */
    private $actionRegistry;

    /**
     * PromotionApplicatorService constructor.
     * @param ActionRegistry $actionRegistry
     */
    public function __construct(ActionRegistry $actionRegistry)
    {
        $this->actionRegistry = $actionRegistry;
    }

    /**
     * @param Order $order
     * @param Promotion $promotion
     * @return Order
     * @throws ActionHandlerNotFoundException
     */
    public function apply(Order $order, Promotion $promotion): Order
    {
        if (!$promotion->getCondition()->isSatisfiedBy($order)) {
            return $order;
        }

        $actions = $promotion->getActions();
        if (empty($actions)) {
            $order->addPromotion($promotion);

            return $order;
        }

        foreach ($actions as $action) {
            $handler = $this->actionRegistry->get(get_class($action));
            $order = $handler->handle($action, $promotion, $order);
        }

        $order->addPromotion($promotion);

        return $order;
    }
}