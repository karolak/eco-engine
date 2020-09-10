<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Adjustment;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\PromotionProductsAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidActionException;

/**
 * Class PromotionProductsActionHandler
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler
 */
class PromotionProductsActionHandler implements ActionHandlerInterface
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
        if (!($action instanceof PromotionProductsAction)) {
            throw new InvalidActionException();
        }

        if ($order->isEmpty()) {
            return $order;
        }

        $promotionProducts = $action->getProducts();
        if (empty($promotionProducts)) {
            return $order;
        }

        $items = $promotion->getFilter()->filter($order->getItems());
        $totalLimit = $action->getLimit();
        $applyPerProduct = [];
        $applyCount = 0;
        foreach ($items as $item) {
            if ($applyCount >= $totalLimit) {
                break;
            }

            $productId = $item->getProduct()->getId();
            foreach ($promotionProducts as $product) {
                if ($product->getId() !== $productId || !$product->getCondition()->isSatisfiedBy($order)) {
                    continue;
                }

                $applyPerProduct[$productId] = $applyPerProduct[$productId] ?? 0;
                if ($applyPerProduct[$productId] >= $product->getLimit()) {
                    break;
                }

                $discount = $item->getPrice() - $product->getPrice();
                $order->addAdjustmentToItem(
                    new Adjustment(-$discount, $promotion->getType(), $promotion->getName()),
                    $item
                );

                $applyCount++;
                $applyPerProduct[$productId]++;
                break;
            }
        }

        return $order;
    }
}