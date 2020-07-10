<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\CheapestItemDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidActionException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;

/**
 * Class CheapestItemDiscountAction
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler
 */
class CheapestItemDiscountActionHandler extends ItemsPercentDiscountActionHandler implements ActionHandlerInterface
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
        if (!($action instanceof CheapestItemDiscountAction)) {
            throw new InvalidActionException();
        }

        if ($order->isEmpty()) {
            return $order;
        }

        $discount = $this->getCheapestItemPrice($order->getItems()) * $action->getPercent() / 100;
        $percentDiscount = $discount / $order->getTotalProductsPrice() * 100;

        return parent::handle(new ItemsPercentDiscountAction($percentDiscount), $promotion, $order);
    }

    /**
     * @param array|Item[] $items
     * @return int
     */
    private function getCheapestItemPrice(array $items): int
    {
        $lastItem = array_pop($items);
        $result = $lastItem->getPrice();
        if (empty($items)) {
            return $result;
        }

        foreach ($items as $item) {
            if ($item->getPrice() < $result) {
                $result = $item->getPrice();
            }
        }

        return $result;
    }
}