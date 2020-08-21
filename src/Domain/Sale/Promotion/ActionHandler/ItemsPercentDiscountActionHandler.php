<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Adjustment;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidActionException;

/**
 * Class ItemsPercentDiscountActionHandler
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler
 */
class ItemsPercentDiscountActionHandler implements ActionHandlerInterface
{
    /**
     * @param ActionInterface $action
     * @param Promotion $promotion
     * @param Order $order
     * @return Order
     * @throws InvalidActionException
     * @throws InvalidPriceValueException
     * @throws ItemNotFoundException
     */
    public function handle(ActionInterface $action, Promotion $promotion, Order $order): Order
    {
        if (!($action instanceof ItemsPercentDiscountAction)) {
            throw new InvalidActionException();
        }

        if ($order->isEmpty()) {
            return $order;
        }

        $percentDiscount = $action->getValue() / 100;
        $items = $promotion->getFilter()->filter($order->getItems());
        $totalDiscount = round($order->getProductsPrice($items) * $percentDiscount);

        $lastItem = array_pop($items);

        if (!empty($items)) {
            foreach ($items as $item) {
                $discount = round($item->getPrice() * $percentDiscount);
                $totalDiscount -= $discount;
                $order->addAdjustmentToItem(
                    new Adjustment(-$discount, $promotion->getType(), $promotion->getName()),
                    $item
                );
            }
        }

        $order->addAdjustmentToItem(
            new Adjustment(-$totalDiscount, $promotion->getType(), $promotion->getName()),
            $lastItem
        );

        return $order;
    }
}