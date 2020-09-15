<?php
namespace Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler;

use Karolak\EcoEngine\Domain\Sale\Order\Entity\Order;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\InvalidPriceValueException;
use Karolak\EcoEngine\Domain\Sale\Order\Exception\ItemNotFoundException;
use Karolak\EcoEngine\Domain\Sale\Order\ValueObject\Item;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ActionInterface;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\CheapestItemPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Action\ItemsPercentDiscountAction;
use Karolak\EcoEngine\Domain\Sale\Promotion\Entity\Promotion;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidActionException;
use Karolak\EcoEngine\Domain\Sale\Promotion\Exception\InvalidPercentValueException;

/**
 * Class CheapestItemPercentDiscountActionHandler
 * @package Karolak\EcoEngine\Domain\Sale\Promotion\ActionHandler
 */
class CheapestItemPercentDiscountActionHandler extends ItemsPercentDiscountActionHandler implements ActionHandlerInterface
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
        if (!($action instanceof CheapestItemPercentDiscountAction)) {
            throw new InvalidActionException();
        }

        if ($order->isEmpty()) {
            return $order;
        }

        $items = $promotion->getFilter()->filter($order->getItems());
        $cheapestItemsCount = $this->getGroupsCount(count($items), $action->getInEveryGroupOf());
        $cheapestItems = $this->getCheapestItems($items, $cheapestItemsCount);
        $discount = $this->getDiscountSum($cheapestItems, $action->getPercentDiscount());

        $percentDiscount = $discount / $order->getProductsPrice($items) * 100;

        return parent::handle(new ItemsPercentDiscountAction($percentDiscount), $promotion, $order);
    }

    /**
     * @param int $totalQuantity
     * @param int $groupSize
     * @return int
     */
    private function getGroupsCount(int $totalQuantity, int $groupSize): int
    {
        if ($groupSize > 1) {
            return intval(floor($totalQuantity / $groupSize));
        }

        if ($groupSize === 1) {
            return $totalQuantity;
        }

        return 1;
    }

    /**
     * @param array $items
     * @param int $count
     * @return array
     */
    private function getCheapestItems(array $items, int $count): array
    {
        usort($items, function (Item $a, Item $b) {
            return $a->getPrice() <=> $b->getPrice();
        });

        return array_slice($items, 0, $count);
    }

    /**
     * @param array $items
     * @param float $singlePercentDiscount
     * @return float
     */
    private function getDiscountSum(array $items, float $singlePercentDiscount): float
    {
        return array_sum(
            array_map(
                function (Item $item) use ($singlePercentDiscount) {
                    return $item->getPrice() * $singlePercentDiscount / 100;
                },
                $items
            )
        );
    }
}