<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsFixedDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidActionException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;

/**
 * Class ItemsFixedDiscountActionHandler
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler
 */
class ItemsFixedDiscountActionHandler extends ItemsPercentDiscountActionHandler implements ActionHandlerInterface
{
    /**
     * @param ActionInterface $action
     * @param Promotion $promotion
     * @param Order $order
     * @return Order
     * @throws InvalidActionException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     * @throws InvalidPercentValueException
     */
    public function handle(ActionInterface $action, Promotion $promotion, Order $order): Order
    {
        if (!($action instanceof ItemsFixedDiscountAction)) {
            throw new InvalidActionException();
        }

        if ($order->isEmpty()) {
            return $order;
        }

        $percentDiscount = $this->getPercentDiscount($action->getValue(), $order->getTotalProductsPrice());

        return parent::handle(new ItemsPercentDiscountAction($percentDiscount), $promotion, $order);
    }

    /**
     * @param int $totalDiscount
     * @param int $itemsPrice
     * @return float
     */
    private function getPercentDiscount(int $totalDiscount, int $itemsPrice): float
    {
        if ($totalDiscount > $itemsPrice) {
            $totalDiscount = $itemsPrice;
        }

        return $totalDiscount / $itemsPrice * 100;
    }
}